<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'title' => 'Maîtriser Laravel 12 et Volt',
                'slug' => Str::slug('Maîtriser Laravel 12 et Volt'),
                'description' => 'Un guide complet pour créer des applications web modernes et réactives très rapidement sans se prendre la tête.',
                'price' => 5000, // 5000 FCFA
                // On utilise une API gratuite pour générer une fausse couverture provisoire
                'cover_path' => 'https://ui-avatars.com/api/?name=Laravel+12&background=FF2D20&color=fff&size=400&font-size=0.33',
                'file_path' => 'private/books/dummy1.pdf',
                'is_published' => true,
            ],
            [
                'title' => 'Le Guide du Développeur Freelance',
                'slug' => Str::slug('Le Guide du Développeur Freelance'),
                'description' => 'Comment trouver des clients, structurer ses projets et intégrer des paiements Mobile Money facilement.',
                'price' => 3500, // 3500 FCFA
                'cover_path' => 'https://ui-avatars.com/api/?name=Dev+Freelance&background=0D9488&color=fff&size=400&font-size=0.33',
                'file_path' => 'private/books/dummy2.pdf',
                'is_published' => true,
            ],
            [
                'title' => 'Design UI/UX avec TailwindCSS',
                'slug' => Str::slug('Design UI/UX avec TailwindCSS'),
                'description' => 'Créez des interfaces magnifiques et responsives sans jamais quitter vos fichiers Blade.',
                'price' => 4500, // 4500 FCFA
                'cover_path' => 'https://ui-avatars.com/api/?name=Tailwind+CSS&background=38BDF8&color=fff&size=400&font-size=0.33',
                'file_path' => 'private/books/dummy3.pdf',
                'is_published' => true,
            ]
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
