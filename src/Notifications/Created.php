<?php

namespace ME\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Created extends Notification
{
  use Queueable;

  /**
   * The global configurations
   * @var array
   */
  protected $config;

  /**
   * Create a new notification instance.
   *
   * @param array $config
   *
   * @return void
   */
  public function __construct(array $config = [])
  {
    $this->config = config('me-notification.created', []);

    // Configurations can be overriden
    $this->config = array_replace($this->config, $config);
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function via($notifiable)
  {
    return $this->config['channels'];
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
      ->subject($this->config['message']['subject'])
      ->greeting($this->config['message']['greeting'])
      ->with($this->config['message']['lines'])
      ->action($this->config['message']['action']['text'], $this->config['message']['action']['url'])
      ->line($this->config['message']['footer']);
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
