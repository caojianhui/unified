<?php

/**
 * Description of EventServiceProvider
 *
 * @author admin
 */

namespace Unified\Login\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Unified\Login\Events\LogoutEvent;
use Unified\Login\Events\SetGoKeyEvent;
use Unified\Login\Listeners\LogoutListener;
use Unified\Login\Listeners\SetGoKeyListener;

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
