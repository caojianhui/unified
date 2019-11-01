<?php

namespace Unified\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Unified\Events\SetGoKeyEvent;
use Unified\UnifiedManager;

class UnifiedMiddleware
{
    public function handle($request, Closure $next)
    {

        $config = config('unified');
        try {
            $user = Auth::guard($config['guard'])->user();
            $token = null;
            if(!empty($user) && $user->tenant_id>0){
                event(new SetGoKeyEvent($user->id,$user->tenant_id));
            }else{
                if($config['is_domain']===false){
                    if ($request->header('token') && !$request->filled('token')) {
                        $token = $$request->header('token');
                    }
                }
                $data = UnifiedManager::getInfo($token);
                UnifiedManager::saveSession($data);
            }
            return $next($request);
        } catch (\Exception $e) {
           throw new UnauthorizedException($e->getMessage());
        }
    }


}
