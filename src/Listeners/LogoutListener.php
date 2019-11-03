<?php

namespace Unified\Login\Listeners;

use Unified\Login\Events\LogoutEvent;
use Unified\Login\Events\SetGoKeyEvent;
use Unified\Login\Support\UnifiedCookie;

/**
 * 设置用户信息
 *
 * @author wanglei 2018-03-13
 */
class LogoutListener
{

    /**
     * @param SetGoKeyEvent $event
     * @return string
     */
    public function handle(LogoutEvent $event)
    {
        try {
            $config = config('unified');
            UnifiedCookie::delCookie($config['go_key']);
        } catch (\Exception $ex) {
            logger('设置同步信息异常：' . $ex->getMessage());
        }
    }

}
