<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DownloadController extends Controller
{
    // On passe le modèle Book directement via la route
    public function download(Request $request, Book $book)
    {

        // 0. Vérification du reCAPTCHA v3
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (!$response->json('success') || $response->json('score') < 0.5) {
            abort(403, 'Action suspecte détectée. C\'est encore toi le robot ?\nVilain robot !');
        }

        // 1. Vérification stricte : le livre doit être publié, gratuit et avoir un fichier
        if (!$book->is_published || !$book->is_free || !$book->file_path) {
            abort(403, 'Ce livre n\'est pas disponible au téléchargement public.');
        }

        // 2. Enregistrement du téléchargement dans la base de données
        Download::create([
            'book_id'    => $book->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id'    => auth()->check() ? auth()->id() : null,
        ]);

        // 3. Formatage d'un nom propre pour le fichier téléchargé
        $fileName = Str::slug($book->title) . '.pdf';

        // 4. Déclenchement du téléchargement depuis le disque 'private'
        return Storage::disk('private')->download($book->file_path, $fileName);
    }

    public function index(Request $request): View
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // 1. Requête pour la liste détaillée (avec filtres)
        $query = Download::with(['book', 'user'])->latest();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $downloads = $query->paginate(15)->withQueryString();

        // 2. Requête pour le Top 5 des livres (pour le visuel)
        // On compte les téléchargements de tous les temps pour le classement global
        $topBooks = Book::withCount('downloads')
            ->having('downloads_count', '>', 0)
            ->orderByDesc('downloads_count')
            ->take(5)
            ->get();

        // On récupère le nombre maximum pour calculer les pourcentages de nos barres de progression
        $maxDownloads = $topBooks->max('downloads_count') ?: 1;

        return view('admin.pages.downloads.index', [
            'downloads'    => $downloads,
            'topBooks'     => $topBooks,
            'maxDownloads' => $maxDownloads,
            'startDate'    => $startDate,
            'endDate'      => $endDate,
            'pageTitle'    => 'Statistiques des téléchargements',
        ]);
    }

    /**
     * Export des données de téléchargement au format CSV/Excel
     * Prends uniquement en compte les filtres de dates appliqués (ignore la pagination).
     * Si les filtres sont vides, exporte l'intégralité de l'historique depuis le début.
     */
    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $query = Download::with(['book.category', 'user'])->latest();

        if (!empty($startDate)) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if (!empty($endDate)) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $fileName = 'telechargements_zerolib_' . now()->format('Y-m-d_H-i') . '.csv';

        $headers = [
            "Content-Type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');

            // BOM pour la prise en charge native des caractères UTF-8 sous Microsoft Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // En-têtes CSV (Délimiteur point-virgule pour compatibilité Excel FR)
            fputcsv($file, [
                'ID',
                'Date & Heure',
                'Livre',
                'Auteur',
                'Catégorie',
                'Tarif',
                'Utilisateur / Visiteur',
                'Adresse IP',
                'Navigateur (User Agent)'
            ], ';', '"', '\\');

            // Parcourir l'intégralité des résultats par paquets de 500 sans bloquer la mémoire
            $query->lazy(500)->each(function ($download) use ($file) {
                $bookTitle  = $download->book ? $download->book->title : 'Livre supprimé';
                $bookAuthor = $download->book ? ($download->book->author ?? 'Non renseigné') : '—';
                $category   = $download->book && $download->book->category ? $download->book->category->name : '—';
                $tariff     = $download->book ? ($download->book->is_free ? 'Gratuit' : 'Premium (' . $download->book->formatted_price . ')') : '—';
                $userLabel  = $download->user ? $download->user->name . ' (' . $download->user->email . ')' : 'Visiteur non connecté';

                fputcsv($file, [
                    $download->id,
                    $download->created_at ? $download->created_at->format('d/m/Y H:i:s') : '—',
                    $bookTitle,
                    $bookAuthor,
                    $category,
                    $tariff,
                    $userLabel,
                    $download->ip_address ?? 'Inconnue',
                    $download->user_agent ?? 'Inconnu'
                ], ';', '"', '\\');
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
