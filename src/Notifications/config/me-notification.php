<?php

return [
  /**
   * List of the notification's delivery channels.
   */
  'channels' => ['mail'],
  /**
   * The Message
   */
  'message' => [
    'subject' => 'Test email',
    'greeting' => 'Hi!',
    'lines' => [
      'This is a',
      'Test Email',
      '...'
    ],
    'action' => [
      'text' => 'Link',
      'url' => '/'
    ],
    'footer' => 'Addio'
  ]
];
