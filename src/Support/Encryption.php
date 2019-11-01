<?php


namespace Unified\Support;


class Encryption
{
    /**
     * @param $data
     * @param $key
     * @return string
     * 加密
     */
    public function encrypt($data)
    {
        $data = json_encode($data);
        $key = md5(config('go_key'));
        $x = 0;
        $len = strlen($data);
        $l = strlen($key);
        $str = $char = '';
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= $key{$x};
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
        }
        return base64_encode($str);
    }

    /**
     * @param $data
     * @return string
     * 解密
     */
    public function decrypt($data)
    {
        $key = md5(config('go_key'));
        $x = 0;
        $str = $char = '';
        $data =  base64_decode($data);
        $len = strlen($data);
        $l = strlen($key);
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            } else {
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        $data = json_decode($str,true);

        return $data;
    }
}
