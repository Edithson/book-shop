<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    // Afficher la liste des catégories
    public function index(): View
    {
        // On récupère les catégories avec le nombre de livres associés
        $categories = Category::withCount('books')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.pages.categories.index', [
            'categories' => $categories,
            'pageTitle'  => 'Gestion des catégories',
        ]);
    }

    // Afficher le formulaire de création
    public function create(): View
    {
        return view('admin.pages.categories.create', [
            'pageTitle' => 'Ajouter une catégorie',
        ]);
    }

    // Enregistrer une nouvelle catégorie
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);

        Category::create([
            'name' => $validated['name'],
            // Le slug est généré automatiquement par le modèle
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'La catégorie « ' . $validated['name'] . ' » a été créée avec succès.');
    }

    // Afficher les détails d'une catégorie et ses livres
    public function show(Category $category): View
    {
        // On charge les livres de cette catégorie avec pagination
        $books = $category->books()->latest()->paginate(10);

        return view('admin.pages.categories.show', [
            'category' => $category,
            'books'    => $books,
            'pageTitle' => 'Catégorie : ' . $category->name,
        ]);
    }

    // Afficher le formulaire de modification
    public function edit(Category $category): View
    {
        return view('admin.pages.categories.edit', [
            'category'  => $category,
            'pageTitle' => 'Modifier la catégorie',
        ]);
    }

    // Mettre à jour la catégorie
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            // On ignore l'ID de la catégorie actuelle pour la règle unique
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
        ]);

        $category->update([
            'name' => $validated['name'],
            // Le slug sera mis à jour automatiquement par le modèle si le nom change
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'La catégorie « ' . $category->name . ' » a été mise à jour.');
    }

    // Supprimer la catégorie
    public function destroy(Category $category): RedirectResponse
    {
        $name = $category->name;

        // La suppression ne touche pas aux livres grâce au nullOnDelete() de ta migration
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'La catégorie « ' . $name . ' » a été supprimée. Ses livres sont désormais sans catégorie.');
    }
}
