<?php

namespace App\Http\Controllers;

use App\Models\Contact; // Indispensable pour enregistrer
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class ContactController extends Controller
{
    public function show()
    {
        return view('pages.contact');
    }

    public function submit(Request $request)
    {
        // 1. Validation
        $data = $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email',
            'subject'              => 'required|string|max:255',
            'message'              => 'required|string|min:10',
            'g-recaptcha-response' => 'required',
        ]);

        try {
            // 2. Vérification Captcha
            $verify = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => env('RECAPTCHA_SECRET_KEY'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]);

            if (!$verify->json('success')) {
                return back()->withInput()->withErrors(['captcha' => 'Échec du test robot. Réessayez.']);
            }

            // 3. SAUVEGARDE DANS LA BDD (C'est ce qui manquait !)
            Contact::create([
                'name'    => $data['name'],
                'email'   => $data['email'],
                'subject' => $data['subject'],
                'message' => $data['message'],
                'is_read' => false, // Nouveau message
            ]);

            // 4. Envoi de l'e-mail de notification
            Mail::send([], [], function ($message) use ($data) {
                $message->to('elkanakm@gmail.com')
                        ->subject('Contact Aldo_News : ' . $data['subject'])
                        ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                        ->html("
                            <div style='font-family: sans-serif; padding: 20px;'>
                                <h2>🚀 Nouveau message de {$data['name']}</h2>
                                <p><strong>Email :</strong> {$data['email']}</p>
                                <p><strong>Message :</strong></p>
                                <p style='background: #f4f4f4; padding: 15px;'>".nl2br(e($data['message']))."</p>
                            </div>
                        ");
            });

            return back()->with('success', 'Votre message a été envoyé et enregistré avec succès !');
            
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur : ' . $e->getMessage()]);
        }
    }
}