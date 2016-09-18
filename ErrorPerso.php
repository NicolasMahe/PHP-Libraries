<?php

class ErrorPerso
{
    private static $error = array();

    public static function add($error)
    {
        self::$error[] = $error;
    }

    public static function getAll()
    {
        return self::$error;
    }
}
