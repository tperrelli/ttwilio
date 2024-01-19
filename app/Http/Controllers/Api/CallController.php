<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Repositories\AgentRepository;
use App\Repositories\VoiceRepository;
use Symfony\Component\HttpFoundation\Response as Status;

class CallController extends Controller
{
    protected $agentRepository;
    protected $voiceRepository;

    public function __construct(
        AgentRepository $agentRepository,
        VoiceRepository $voiceRepository
        )
    {
        $this->agentRepository = $agentRepository;
        $this->voiceRepository = $voiceRepository;
    }

    public function welcome()
    {
        $response = $this->voiceRepository->welcome();

        return new Response($response, Status::HTTP_OK, self::VOIP_HEADERS);
    }

    public function menu(Request $request)
    {
        $response = $this->voiceRepository->menu((int) $request->input('Digits'));

        return new Response($response, Status::HTTP_OK, self::VOIP_HEADERS);
    }

    public function hangup()
    {
        $response = $this->voiceRepository->hangup();

        return new Response($response, Status::HTTP_OK, self::VOIP_HEADERS);
    }
}