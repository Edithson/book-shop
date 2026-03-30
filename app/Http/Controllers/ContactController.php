<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validation des données du formulaire
        $validated = $request->validate([
            'motif'  => ['nullable', 'string', 'in:retrait,droit,suggestion,erreur,autre'],
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        // 2. Enregistrement en base de données (avec le mapping des champs)
        $contact = Contact::create([
            'motif'   => $validated['motif'] ?? 'autre',
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'sujet'   => $validated['subject'],
            'message' => $validated['message'],
        ]);

        // 3. Envoi du mail (sera mis en file d'attente grâce à ShouldQueue)
        Mail::to('moafogaus@gmail.com')->send(new ContactFormMail($contact));

        // 4. Retour de la réponse de succès en JSON
        return response()->json([
            'success' => true,
            'message' => 'Votre message a bien été envoyé.'
        ]);
    }
}
