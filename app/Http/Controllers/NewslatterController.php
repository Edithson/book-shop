<?php

namespace App\Http\Controllers;

use App\Models\Newslatter;
use Illuminate\Http\Request;

class NewslatterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email'   => ['required', 'email', 'max:255'],
            'consent' => ['accepted'],
        ], [
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email'    => 'Veuillez entrer une adresse e-mail valide.',
            'consent.accepted' => 'Vous devez accepter de recevoir nos e-mails.',
        ]);

        $already = Newslatter::where('email', $request->email)->exists();

        if ($already) {
            return response()->json([
                'status'  => 'already',
                'message' => 'Cette adresse est déjà inscrite à notre newsletter.',
            ], 409);
        }

        Newslatter::create(['email' => $request->email]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Inscription confirmée ! Bienvenue 🎉',
        ], 201);
    }
}
