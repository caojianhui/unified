<?php

/**
 * Description of EventServiceProvider
 *
 * @author admin
 */

namespace Unified\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Unified\Events\LogoutEvent;
use Unified\Events\SetGoKeyEvent;
use Unified\Listeners\LogoutListener;
use Unified\Listeners\SetGoKeyListener;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        SetGoKeyEvent::class => [
            SetGoKeyListener::class
        ],
        LogoutEvent::class=>[
            LogoutListener::class
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
