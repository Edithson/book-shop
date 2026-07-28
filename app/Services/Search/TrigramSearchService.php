<?php

namespace App\Services\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class TrigramSearchService
{
    /**
     * Mots vides (stop-words) en français à ignorer pour éviter le bruit dans les recherches.
     *
     * @var array<string>
     */
    public static array $stopWords = [
        'le', 'la', 'les', 'un', 'une', 'des', 'du', 'de', 'd', 'l',
        'en', 'au', 'aux', 'sur', 'par', 'pour', 'avec', 'et', 'ou',
        'dans', 'ce', 'cet', 'cette', 'ces', 'sa', 'son', 'ses', 'mon',
        'ma', 'mes', 'ton', 'ta', 'tes', 'nos', 'vos', 'leur', 'leurs', 'a', 'à'
    ];

    /**
     * Normalise une chaîne de caractères (minuscules, sans accents, sans caractères spéciaux).
     */
    public static function normalize(string $text): string
    {
        $text = Str::ascii(mb_strtolower(trim($text)));
        return preg_replace('/[^a-z0-9\s]/', '', $text);
    }

    /**
     * Extrait les trigrammes (sous-chaînes de 3 caractères) d'un texte.
     *
     * @return array<string>
     */
    public static function extractTrigrams(string $text): array
    {
        $normalized = static::normalize($text);
        $cleaned = preg_replace('/\s+/', ' ', $normalized);
        $length = mb_strlen($cleaned);

        if ($length < 3) {
            return [];
        }

        $trigrams = [];
        for ($i = 0; $i <= $length - 3; $i++) {
            $tri = mb_substr($cleaned, $i, 3);
            if (trim($tri) !== '') {
                $trigrams[] = $tri;
            }
        }

        return array_values(array_unique($trigrams));
    }

    /**
     * Applique la recherche par trigramme et le calcul de pertinence sur une requête Eloquent.
     *
     * @param Builder $query
     * @param string $search
     * @param array<string, int> $customWeights
     * @return Builder
     */
    public static function apply(Builder $query, string $search, array $customWeights = []): Builder
    {
        $search = trim($search);
        if (empty($search)) {
            return $query;
        }

        $weights = array_merge([
            'title'       => 50,
            'author'      => 30,
            'description' => 10,
        ], $customWeights);

        $normalizedSearch = static::normalize($search);
        // Filtrer les mots vides (stop-words) et ne garder que les mots significatifs
        $words = array_values(array_filter(
            explode(' ', $normalizedSearch),
            fn($w) => mb_strlen($w) >= 2 && !in_array($w, static::$stopWords)
        ));
        $trigrams = static::extractTrigrams($search);

        $selectCases = [];
        $bindings = [];

        // 1. Match exact ou de début sur la recherche complète
        $selectCases[] = "(CASE WHEN LOWER(title) = ? THEN ? ELSE 0 END)";
        $bindings[] = mb_strtolower($search);
        $bindings[] = $weights['title'] * 2; // ex: 100

        $selectCases[] = "(CASE WHEN LOWER(title) LIKE ? THEN ? ELSE 0 END)";
        $bindings[] = mb_strtolower($search) . '%';
        $bindings[] = (int) ($weights['title'] * 1.5); // ex: 75

        $selectCases[] = "(CASE WHEN LOWER(title) LIKE ? THEN ? ELSE 0 END)";
        $bindings[] = '%' . mb_strtolower($search) . '%';
        $bindings[] = $weights['title']; // ex: 50

        $selectCases[] = "(CASE WHEN LOWER(author) = ? THEN ? ELSE 0 END)";
        $bindings[] = mb_strtolower($search);
        $bindings[] = $weights['author'] * 2; // ex: 60

        $selectCases[] = "(CASE WHEN LOWER(author) LIKE ? THEN ? ELSE 0 END)";
        $bindings[] = '%' . mb_strtolower($search) . '%';
        $bindings[] = $weights['author']; // ex: 30

        $selectCases[] = "(CASE WHEN LOWER(description) LIKE ? THEN ? ELSE 0 END)";
        $bindings[] = '%' . mb_strtolower($search) . '%';
        $bindings[] = $weights['description']; // ex: 10

        // 2. Pertinence par mots individuellement (hors stop-words)
        foreach ($words as $word) {
            $selectCases[] = "(CASE WHEN LOWER(title) LIKE ? THEN ? ELSE 0 END)";
            $bindings[] = '%' . $word . '%';
            $bindings[] = (int) ($weights['title'] * 0.4);

            $selectCases[] = "(CASE WHEN LOWER(author) LIKE ? THEN ? ELSE 0 END)";
            $bindings[] = '%' . $word . '%';
            $bindings[] = (int) ($weights['author'] * 0.4);

            $selectCases[] = "(CASE WHEN LOWER(description) LIKE ? THEN ? ELSE 0 END)";
            $bindings[] = '%' . $word . '%';
            $bindings[] = (int) ($weights['description'] * 0.3);
        }

        // 3. Pertinence par trigrammes (Fuzzy matching)
        foreach ($trigrams as $trigram) {
            $selectCases[] = "(CASE WHEN LOWER(title) LIKE ? THEN 5 ELSE 0 END)";
            $bindings[] = '%' . $trigram . '%';

            $selectCases[] = "(CASE WHEN LOWER(author) LIKE ? THEN 3 ELSE 0 END)";
            $bindings[] = '%' . $trigram . '%';

            $selectCases[] = "(CASE WHEN LOWER(description) LIKE ? THEN 1 ELSE 0 END)";
            $bindings[] = '%' . $trigram . '%';
        }

        $rawScoreSql = implode(' + ', $selectCases);

        // Sélectionner les colonnes du modèle si pas encore sélectionnées + la colonne virtuelle relevance_score
        $tableName = $query->getModel()->getTable();
        if (empty($query->getQuery()->columns)) {
            $query->select("{$tableName}.*");
        }
        $query->selectRaw("({$rawScoreSql}) AS relevance_score", $bindings);

        // Filtrage strict des résultats candidats
        $query->where(function ($q) use ($search, $words, $trigrams) {
            // 1. Recherche intégrale du terme sur titre, auteur ou description
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('author', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");

            // 2. Recherche par mots significatifs (hors stop-words) sur titre et auteur
            foreach ($words as $w) {
                $q->orWhere('title', 'like', "%{$w}%")
                  ->orWhere('author', 'like', "%{$w}%");
            }

            // 3. Fallback trigrammes sur le titre si au moins 2 trigrammes (tolérance fautes de frappe)
            if (count($trigrams) >= 2) {
                foreach ($trigrams as $t) {
                    $q->orWhere('title', 'like', "%{$t}%");
                }
            }
        });

        // Seuil minimal de pertinence pour éliminer le bruit et les fautes sans rapport
        $minScore = (mb_strlen($normalizedSearch) <= 3) ? 10 : 15;
        $query->having('relevance_score', '>=', $minScore);

        return $query;
    }
}
