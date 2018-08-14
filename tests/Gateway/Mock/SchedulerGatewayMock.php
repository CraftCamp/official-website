<?php

namespace Tests\Gateway\Mock;

use App\Gateway\SchedulerGateway;

class SchedulerGatewayMock extends SchedulerGateway
{
    public function addTask(string $method, string $url, string $id, \DateTime $executedAt)
    {
        return true;
    }
}