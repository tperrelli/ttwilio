<?php

namespace App\Repositories;

use App\Models\Agent;

class AgentRepository
{
    protected $agent;

    public function __construct(Agent $agent)
    {
        $this->agent = $agent;
    }

    public function findByCode(int $id)
    {
        $agent = $this->agent->find($id);
         
        return $agent;
    }
}