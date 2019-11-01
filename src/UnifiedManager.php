<?php


namespace Unified;


use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Unified\Plugins\Encryption;

class UnifiedManager
{


    /**
     * @param $token =null
     * @return mixed
     * 根据token解密获取用户信息
     */
    public function getInfo($token = null)
    {
        $config = $this->getConfig();
        if($config['is_domain']===true){
            $token = get_cookie($config['go_key']);
        }
        if(empty($token)) return [];
        $encrytion = new Encryption();
        $data = $encrytion->decrypt($token);
        return json_decode($data,true);
    }

    /**
     * @param $userId
     * @param $tenantId
     * @return string
     * 生成加密串并存储到本地cookie中
     */
    public function setKey($userId, $tenantId)
    {
        $config = $this->getConfig();
        $data = json_encode(['userId' => $userId, 'tenantId' => $tenantId],true);
        $encrytion = new Encryption();
        $key = $encrytion->decrypt($data);
        set_cookie($config['go_key'], $key, $config['minutes']);
        return $key;
    }

    /**
     * @param $info
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed
     * 存储session
     */
    public function saveSession($info)
    {
        $config = $this->getConfig();
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
    public function getGoUrl()
    {
        $config = $this->getConfig();
        $token = get_cookie($config['go_key']);
        $info = $this->getInfo($token);
        $url = $config['go_url'].!empty($info)?'?token='.$this->setKey($info['userId'],$info['tenantId']):'';
        return $url;
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     * 获取配置信息
     */
    protected function getConfig()
    {
        return config('unified');
    }
}
