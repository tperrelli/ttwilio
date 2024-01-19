<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Agent;
use App\Repositories\AgentRepository;
use App\Repositories\VoiceRepository;

class AgentControllerTest extends TestCase
{
    public function test_agent_calls()
    {   
        $agent = new Agent();
        $agent->number = 1;

        $digits = 1;
        
        $this->mock(AgentRepository::class, function ($mock) use($agent, $digits) {
            $mock
                ->shouldReceive('findByCode')
                ->once()
                ->with($digits)
                ->andReturn($agent);
        });

        $this->mock(VoiceRepository::class, function ($mock) use($agent) {
            $mock
                ->shouldReceive('dial')
                ->with($agent->number)
                ->once();
        });

        $response = $this->post('api/agents/call', ['Digits' => $digits]);

        $response->assertStatus(200);
    }
}
