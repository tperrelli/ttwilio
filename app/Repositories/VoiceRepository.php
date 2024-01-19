<?php

namespace App\Repositories;

use App\Contracts\VoiceContract;
use Twilio\Rest\Client as Twilio;
use Twilio\TwiML\VoiceResponse;
use Illuminate\Support\Facades\Config;

class VoiceRepository implements VoiceContract
{
    protected $from;
    protected $provider;
    protected $response;

    public function __construct(Twilio $provider, VoiceResponse $response)
    {
        $this->from = Config::get('twilio.from');
        $this->provider = $provider;
        $this->response = $response;
    }

    public function voicemail()
    {
        $this->response->say('Please, leave a message at the beep. Press start when you finished.');
        $this->response->record([
            'action' => '/calls/sendmessage',
            'method' => 'POST',
            'maxLength' => 20,
            'finishOnKey' => '*'
        ]);
        $this->response->say('I did not receive a recording');

        return $this->response;
    }

    public function voiceCall(string $to, ?string $from = null)
    {
        $from = $from ?? $this->from;

        $call = $this->provider
            ->calls
            ->create(
                $to, 
                $from,
                [
                    'url' => 'http://demo.twilio.com/docs/voice.xml'
                    // 'url' => 'https://3628-2804-14d-54ae-86b2-ed0b-4c90-4e4d-d5c0.ngrok-free.app/calls/welcome'
                ]
            );

        return $call->sid;
    }

    public function welcome()
    {
        $gather = $this->response->gather([
            'numDigits' => 1,
            'action' => '/calls/flow',
            'method' => 'POST'
        ]);

        $gather->say('Welcome to Tiagos VOIP System. Press one to forward this call or press two to record a voice mail.');

        return $this->response;
    }

    public function menu(int $digit)
    {
        if ($digit === 1) {

            $gather = $this->response->gather([
                'numDigits' => 5,
                'action' => '/agents/call',
                'method' => 'POST'
            ]);
    
            $gather->say('Please enter agents 5-digits code to find an agent.');

        } else if ($digit === 2) {

            $this->response->say('Please, leave a message at the beep. \nPress start key when you finished.');
            $this->response->record([
                'finishOnKey' => '*',
                'recordingStatusCallback' => '/messages/send',
                'recordingStatusCallbackMethod' => 'POST'
            ]);
        }

        return $this->response;
    }

    public function dial(string $number)
    {
        $this->response->say('We are trying to establish a call with the agent.');
        $this->response->dial($number, [
            'method' => 'GET',
            'action' => 'calls/goodbye'
        ]);
    }

    public function fetchRecordings(string $sid)
    {
        $recording = $this->provider->recordings($sid)->fetch();

        return $recording;
    }

    public function hangup()
    {
        $this->response->say('Thank you for using Tiagos VOIP system. Have a good one!');
        $this->response->hangup();

        return $this->response;
    }
}