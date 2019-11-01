<?php


namespace Unified\Support;


class UnifiedCookie
{
    /**
     * @param $name
     * @return bool
     * 获取cookie
     */
    public static function getCookie($name)
    {
        if (!empty($_COOKIE[$name])) {
            return $_COOKIE[$name];
        }
        return false;
    }


    /**
     * @param $name
     * @param $val
     * @param int $expire
     * @param null $domain
     * @param string $path
     * @return mixed
     * 存储cookie
     */
    public static function setCookie($name, $val, $expire = 1, $domain = null, $path = '/')
    {
        $expire > 0 ? $expire = time() + $expire : $expire;
        if (empty($domain)) {
            setcookie($name, $val, $expire, $path);
        } else {
            setcookie($name, $val, $expire, $path, $domain);
        }
        return $_COOKIE[$name] = $val;
    }

    /**
     * @param $name
     * @param string $path
     * @param string $domain
     * 删除cookie
     */

    public static function delCookie($name, $path = '/', $domain=null)
    {
        self::setCookie($name, '', time() - 3600, $path, $domain=null);
        $_COOKIE[$name] = '';
        unset($_COOKIE[$name]);
    }
}
