<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewCommentNotification extends Notification
{
    use Queueable;

    protected $comment;

    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    public function via($notifiable): array
    {
        // 'database' écrit dans la table notifications
        return ['database']; 
    }

    public function toArray($notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'user_name'  => $this->comment->name, // Utilise directement la colonne name
            'post_title' => $this->comment->post->title ?? 'Article supprimé',
            'message'    => 'a laissé un commentaire sur votre article.',
        ];
    }
}