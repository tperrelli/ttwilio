<?php

namespace App\Contracts;

interface VoiceContract
{
    public function welcome();
    
    public function menu(int $digit);

    public function dial(string $number);

    public function hangup();
}