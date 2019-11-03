<?php

namespace Unified\Login\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Unified\Login\Events\SetGoKeyEvent;
use Unified\Login\UnifiedManager;

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
                    if ($request->token && !empty($request->token)) {
                        $token = $request->token;
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
