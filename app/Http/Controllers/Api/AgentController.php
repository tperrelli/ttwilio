<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Repositories\VoiceRepository;
use App\Repositories\AgentRepository;
use Symfony\Component\HttpFoundation\Response as Status;

class AgentController extends Controller
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

    public function find(Request $request)
    {
        $code = $request->input('Digits');

        $agent = $this->agentRepository->findByCode($code);
        $response = $this->voiceRepository->dial($agent->number);

        return new Response($response, Status::HTTP_OK, self::VOIP_HEADERS);
    }
}