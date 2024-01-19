<?php

namespace Tests\Feature;

use App\Repositories\VoiceRepository;
use Tests\TestCase;

class CallControllerTest extends TestCase
{
    public function test_calls_welcome()
    {
        $this->mock(VoiceRepository::class, function ($mock) {
            $mock
                ->shouldReceive('welcome')
                ->once();
        });

        $response = $this->get('api/calls/welcome');

        $response->assertStatus(200);
    }

    public function test_calls_flow()
    {
        $digits = 1;

        $this->mock(VoiceRepository::class, function ($mock) use($digits) {
            $mock
                ->shouldReceive('menu')
                ->with($digits)
                ->once();
        });

        $response = $this->post('api/calls/flow', [ 'Digits' => $digits ]);

        $response->assertStatus(200);
    }
    
    public function test_calls_goodbye()
    {
        $this->mock(VoiceRepository::class, function ($mock) {
            $mock
                ->shouldReceive('hangup')
                ->once();
        });

        $response = $this->get('api/calls/goodbye');

        $response->assertStatus(200);
    }
}
