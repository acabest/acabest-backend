<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;


class StudentVerifyEmailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Please verify your email address')
                    ->markdown('emails.verify-email',[
                        'url' => $this->verificationUrl($notifiable),
                        'notifiable' => $notifiable
                    ]);
    }

         /*
   * Build the verification URL
   *
   * @return URL
   */
   protected function verificationUrl($notifiable)
   {

       
    //    $url  = 'http://localhost:8080/student/verify/'. $notifiable->getKey(). '/'. sha1($notifiable->getEmailForVerification());
   

        
          $tempURL = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(
                   Config::get('auth.verification.expire', 60)),
                     [
                       'id' => $notifiable->getKey(),
                       'hash' => sha1($notifiable->getEmailForVerification()),
                     ]     
            );
         
       
         $query = explode('verify', $tempURL)[1];
        
         return 'http://localhost:8080/student/verify' . $query;
         
    
   }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    
}


