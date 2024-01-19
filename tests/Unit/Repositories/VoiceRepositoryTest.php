<?php

namespace Tests\Unit\Repositories;

use Twilio\Rest\Client as Twilio;
use Twilio\TwiML\VoiceResponse;
use App\Repositories\VoiceRepository;
use Illuminate\Support\Facades\Config;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class VoiceRepositoryTest extends TestCase
{
    protected $twilioMock;

    protected $mockRepository;

    protected $mockVoiceResponse;

    public function setUp(): void
    {   
        parent::setUp();

        Config::shouldReceive('get')
            ->with('twilio.from')
            ->andReturn('value');

        Config::shouldReceive('get')
            ->with('services.twilio.sid')
            ->andReturn('value');
        
        Config::shouldReceive('get')
            ->with('services.twilio.token')
            ->andReturn('value');
    }

    public function test_voicemail()
    {
        $this->mock(VoiceResponse::class, function ($mock) {
            $mock
                ->shouldReceive('say')
                ->twice();

            $mock
                ->shouldReceive('record')
                ->once();
        });
        
        $twilio = app(Twilio::class);
        $response = app(VoiceResponse::class);
        $repository = new  VoiceRepository($twilio, $response);
        
        $repository->voicemail();
    }

    public function test_welcome()
    {
        $gatherMock = $this->mock(\Twilio\TwiML\Voice\Gather::class);

        $gatherMock
            ->shouldReceive('say')
            ->once();

        $this->mock(VoiceResponse::class, function ($mock) use($gatherMock) {
            $mock
                ->shouldReceive('gather')
                ->once()
                ->andReturn($gatherMock);
        });
   
        $twilio = app(Twilio::class);
        $response = app(VoiceResponse::class);        
        $repository = new VoiceRepository($twilio, $response);

        $repository->welcome();
    }

    public function test_menu_digit_one()
    {
        $gatherMock = $this->mock(\Twilio\TwiML\Voice\Gather::class);

        $gatherMock
            ->shouldReceive('say')
            ->once();

        $this->mock(VoiceResponse::class, function ($mock) use($gatherMock) {
            $mock
                ->shouldReceive('gather')
                ->once()
                ->andReturn($gatherMock);
        });

        $twilio = app(Twilio::class);
        $response = app(VoiceResponse::class);
        $repository = new VoiceRepository($twilio, $response);

        $repository->menu(1);
    }

    public function test_menu_digit_two()
    {
        $this->mock(VoiceResponse::class, function ($mock) {
            $mock
                ->shouldReceive('say')
                ->once();
            $mock
                ->shouldReceive('record')
                ->once();
        });

        $twilio = app(Twilio::class);
        $response = app(VoiceResponse::class);
        $repository = new VoiceRepository($twilio, $response);

        $repository->menu(2);
    }

    public function test_dial()
    {
        $this->mock(VoiceResponse::class, function ($mock) {
            $mock
                ->shouldReceive('say')
                ->once();
            $mock
                ->shouldReceive('dial')
                ->once();
        });
   
   
        $twilio = app(Twilio::class);
        $response = app(VoiceResponse::class);
        $repository = new VoiceRepository($twilio, $response);

        $repository->dial(1);
    }
}
