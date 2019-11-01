<?php

namespace Unified\Listeners;

use Unified\Events\LogoutEvent;
use Unified\Events\SetGoKeyEvent;

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
            del_cookie($config['go_key']);
        } catch (\Exception $ex) {
            logger('设置同步信息异常：' . $ex->getMessage());
        }
    }

}
