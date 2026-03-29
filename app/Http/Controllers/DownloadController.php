<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DownloadController extends Controller
{
    // On passe le modèle Book directement via la route
    public function download(Request $request, Book $book)
    {
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
}
