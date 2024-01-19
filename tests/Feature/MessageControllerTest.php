<?php

namespace Tests\Feature;

use App\Repositories\MessageRepository;
use App\Repositories\VoiceRepository;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    protected $callSid = 'RCALX021901';

    public function test_send_with_media_url()
    {
        $recording = new \stdClass;
        $recording->media_url = 'https://my-media.url/sound.mp3';

        $this->mock(VoiceRepository::class, function ($mock) use($recording) {
            $mock
                ->shouldReceive('fetchRecordings')
                ->with($this->callSid)
                ->once()
                ->andReturn($recording);
        });

        $this->mock(MessageRepository::class, function ($mock) use ($recording) {
            $mock
                ->shouldReceive('sendMessage')
                ->with($recording->media_url)
                ->once();
        });
        
        $response = $this->post('api/messages/send', ['CallSid' => $this->callSid]);

        $response->assertStatus(200);
    }

    public function test_send_message_hagup_callback()
    {
        $recording = new \stdClass;
        $recording->media_url = '';

        $this->mock(VoiceRepository::class, function ($mock) use($recording) {
            $mock
                ->shouldReceive('fetchRecordings')
                ->with($this->callSid)
                ->once()
                ->andReturn($recording);

            $mock
                ->shouldReceive('hangup')
                ->once();
        });

        $response = $this->post('api/messages/send', ['CallSid' => $this->callSid]);

        $response->assertStatus(200);
    }
}
