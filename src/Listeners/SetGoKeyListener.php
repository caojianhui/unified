<?php

namespace Unified\Listeners;

use Unified\Events\SetGoKeyEvent;
use Unified\UnifiedManager;

/**
 * 设置用户信息
 *
 * @author wanglei 2018-03-13
 */
class SetGoKeyListener
{

    /**
     * @param SetGoKeyEvent $event
     * @return string
     */
    public function handle(SetGoKeyEvent $event)
    {
        try {
            $manager = new UnifiedManager();
            $userId = $event->userId;
            $tenantId = $event->tenantId;
            return $manager->setKey($userId, $tenantId);
        } catch (\Exception $ex) {
            logger('设置同步信息异常：' . $ex->getMessage());
        }
    }

}
