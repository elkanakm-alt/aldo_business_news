<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;
    public $content;

    public function __construct(Contact $contact, $content)
    {
        $this->contact = $contact;
        $this->content = $content;
    }

    public function build()
    {
        return $this->subject('Réponse ALDO_NEWS : ' . $this->contact->subject)
                    ->html("
                        <div style='font-family: sans-serif; max-width: 600px; margin: auto; border: 1px solid #eee; border-radius: 10px; padding: 20px;'>
                            <h2 style='color: #2563eb;'>Bonjour {$this->contact->name},</h2>
                            <p>Nous avons bien traité votre demande concernant : <strong>{$this->contact->subject}</strong></p>
                            <div style='background: #f8fafc; padding: 15px; border-left: 4px solid #2563eb; margin: 20px 0;'>
                                <p style='margin: 0;'>{$this->content}</p>
                            </div>
                            <p style='font-size: 12px; color: #64748b;'>Cordialement,<br>L'équipe AdminHub</p>
                        </div>
                    ");
    }
}