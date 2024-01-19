<?php

namespace App\Repositories;

use Twilio\Rest\Client as Twilio;
use Twilio\TwiML\MessagingResponse;

class MessageRepository
{
    protected $provider;
    protected $response;

    public function __construct(Twilio $provider, MessagingResponse $response)
    {
        $this->provider = $provider;
        $this->response = $response;
    }

    public function sendMessage(string $mediaUrl)
    {
        $data = [
            'MediaUrl' => $mediaUrl
        ];

        $this->response->message('Hey, here is the media!', $data);
        $this->response->redirect('calls/goodbye', ['method' => 'GET']);

        return $this->response;
    }   
}