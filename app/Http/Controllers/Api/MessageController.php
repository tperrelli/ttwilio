<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Repositories\VoiceRepository;
use App\Repositories\MessageRepository;
use Symfony\Component\HttpFoundation\Response as Status;

class MessageController extends Controller
{
    protected $voiceRepository;
    
    protected $messageRepository;

    public function __construct(
        VoiceRepository $voiceRepository,
        MessageRepository $messageRepository
        )
    {
        $this->voiceRepository = $voiceRepository;
        $this->messageRepository = $messageRepository;
    }

    public function send(Request $request)
    {
        $sid = $request->input('CallSid');

        $recording = $this->voiceRepository->fetchRecordings($sid);

        if ($recording->media_url) {
            $response = $this->messageRepository->sendMessage($recording->media_url);
        } else {
            $response = $this->voiceRepository->hangup();
        }

        return new Response($response, Status::HTTP_OK, self::VOIP_HEADERS);
    }
}