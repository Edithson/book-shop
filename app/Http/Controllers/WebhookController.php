<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleNotchPay(Request $request)
    {
        // 1. Récupérer toutes les données envoyées par NotchPay
        $payload = $request->all();

        // (Optionnel pour le MVP, vital en Prod) : Vérifier la signature x-notch-signature
        // pour s'assurer que c'est bien NotchPay qui envoie la requête.

        // 2. On vérifie que c'est bien un événement de paiement réussi
        if (isset($payload['event']) && $payload['event'] === 'payment.complete') {

            $data = $payload['data'];

            // 3. Retrouver la commande grâce à la référence (ex: CMD-A1B2C3D4E5)
            $order = Order::where('reference', $data['reference'])->first();

            // 4. Si la commande existe et n'est pas encore payée
            if ($order && $order->status !== 'paid') {

                // On la marque comme payée
                $order->update(['status' => 'paid']);

                // On garde une trace dans la table payments
                Payment::create([
                    'order_id'       => $order->id,
                    'aggregator'     => 'notchpay',
                    'transaction_id' => $data['id'] ?? 'unknown',
                    'amount'         => $data['amount'],
                    'status'         => 'successful',
                ]);

                // Ici, tu pourrais déclencher l'envoi de l'email avec le PDF !
                Log::info("Commande {$order->reference} validée avec succès !");
            }
        }

        // 5. Toujours répondre un code 200 à NotchPay pour dire "Message bien reçu"
        return response()->json(['status' => 'success']);
    }
}
