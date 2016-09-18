<?php

class Response
{
    private static $data;
    private static $status;
    
    public static function setData($data)
    {
        self::$data = $data;
    }

    public static function setStatus($status)
    {
        self::$status = $status;
    }
    
    public static function send()
    {
        $response = array(
            'status' => self::$status,
            'data' => self::$data,
            'error' => ErrorPerso::getAll()
        );
        
        echo json_encode($response);
    }
}
