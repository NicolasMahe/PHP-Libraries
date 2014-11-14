<?php

class Config
{
    private static $config = null;
    
    private static function init()
    {
        self::$config = Storage::read('config.json', true);
        
        if(empty(self::$config)) {
            Error::add('Config file empty');
        }
    }
    
    public static function get($key)
    {
        if(self::$config === null) {
            self::init();
        }
        
        if(isset(self::$config[$key])) {
            return self::$config[$key];
        }
        
        Error::add('Key "'.$key.'" is unknown');
        
        return;
    }
}
