<?php

namespace Unified\Login\Events;

class SetGoKeyEvent
{
    public $userId;
    public $tenantId;

    public function __construct($userId, $tenantId)
    {
        $this->userId = $userId;
        $this->tenantId = $tenantId;
    }
}
