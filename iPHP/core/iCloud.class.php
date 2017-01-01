<?php
/**
 * iPHP - i PHP Framework
 * Copyright (c) 2012 iiiphp.com. All rights reserved.
 *
 * @author coolmoo <iiiphp@qq.com>
 * @website http://www.iiiphp.com
 * @license http://www.iiiphp.com/license
 * @version 2.0.0
 */
class iCloud{
    public static $config = null;
    public static $error  = null;

    public static function init($config) {
        self::$config = $config;
    }
    public static function sdk($vendor=null) {
        if($vendor===null) return false;

        $conf = self::$config['sdk'][$vendor];
        if($conf['AccessKey'] && $conf['SecretKey']){
            iPHP::import(iPHP_LIB.'/'.$vendor.'.php');
            return new $vendor($conf);
        }else{
            return false;
        }
    }
    public static function write($frp,$local=null){
        if(!self::$config['enable']) return false;

        foreach ((array)self::$config['sdk'] as $vendor => $conf) {
            $fp     = ltrim(iFS::fp($frp,'-iPATH'),'/');
            $client = self::sdk($vendor);
            if($client){
                $res    = $client->uploadFile($frp,$conf['Bucket'],$fp);
                $res    = json_decode($res,true);
                if($res['error']){
                    self::$error[$vendor] = array(
                        'action' => 'write',
                        'code'   => 0,
                        'state'  => 'Error',
                        'msg'    => $res['msg']
                    );
                }
            }
        }
        // if(self::$config['local']){
        //     $local[0] && $value = call_user_func_array($local[0],(array)$local[1]);
        // }
    }
    public static function delete($frp) {
        if(!self::$config['enable']) return false;

        foreach ((array)self::$config['sdk'] as $vendor => $conf) {
            $fp     = ltrim(iFS::fp($frp,'-iPATH'),'/');
            $client = self::sdk($vendor);
            if($client){
                $res = $client->delete($conf['Bucket'],$fp);
                $res = json_decode($res,true);
                if($res['error']){
                    self::$error[$vendor] = array(
                        'action' => 'delete',
                        'code'   => 0,
                        'state'  => 'Error',
                        'msg'    => $res['msg']
                    );
                }
            }
        }
    }
}
