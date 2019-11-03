<?php


namespace Unified\Login;


use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Unified\Login\Support\Encryption;
use Unified\Login\Support\UnifiedCookie;

class UnifiedManager
{


    /**
     * @param $token =null
     * @return mixed
     * 根据token解密获取用户信息
     */
    public static function getInfo($token = null)
    {
        $config = self::getConfig();
        if($config['is_domain']===true){
            $token = UnifiedCookie::getCookie($config['go_key']);
        }
        if(empty($token)) return [];
        $encrytion = new Encryption();
        $data = $encrytion->decrypt($token);
        return $data;
    }

    /**
     * @param $userId
     * @param $tenantId
     * @return string
     * 生成加密串并存储到本地cookie中
     */
    public static function setKey($userId, $tenantId)
    {
        $config = self::getConfig();
        $data = ['userId' => $userId, 'tenantId' => $tenantId];
        $encrytion = new Encryption();
        $key = $encrytion->encrypt($data);
        UnifiedCookie::setCookie($config['go_key'], $key, $config['minutes']);
        return $key;
    }

    /**
     * @param $info
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed
     * 存储session
     */
    public static function saveSession($info)
    {
        $config = self::getConfig();
        $tntUser = DB::table('tnt_users')->where('user_id', $info['userId']??0)
            ->where('tenant_id', $info['tenantId']??0)->first();
        if (empty($tntUser)) return false;
        auth($config['guard'])->loginUsingId($info['userId']);
        $data['tenant_id'] = $tntUser['tenant_id'];
        $data['is_admin'] = $tntUser['is_admin'];
        $data['tnt_user_id'] = $tntUser['id'];
        return session([$tntUser->user_id . '_tenant' => $data]);

    }

    /**
     * @param $key
     * @return string
     * 获取请求路径
     */
    public static function getGoUrl()
    {
        $config = self::getConfig();
        $token =  UnifiedCookie::getCookie($config['go_key']);
        $info = self::getInfo($token);
        $param = (!empty($info)?'?token='.self::setKey($info['userId'],$info['tenantId']):'');
        $url = $config['go_url'].$param;
        return $url;
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     * 获取配置信息
     */
    protected static function getConfig()
    {
        return config('unified');
    }
}
