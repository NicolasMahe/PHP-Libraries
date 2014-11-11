<?php

class Log
{
	private static $data = array();

	public static function add($method, $action, $status, $message)
	{
        if(!empty($message)) {
            self::$data[] = array(
                'method' => ucfirst($method),
                'action' => ucfirst($action),
                'status' => $status,
                'message' => $message,
                'date' => date("Y-m-d\TH:i:s\Z")
            );
        } else {
            self::$data[] = array(
                'method' => ucfirst($method),
                'status' => ucfirst($action),
                'message' => $status,
                'date' => date("Y-m-d\TH:i:s\Z")
            );
        }
	}
	
	public static function write()
	{
		if(!empty(self::$data)) {
			$oldLog = self::getAll();
			if(!empty($oldLog))
				self::$data = array_merge($oldLog, self::$data);
				
			$retour = Storage::write(Config::get('storagePathLog'), self::$data);
			
			if($retour) {
				return true;
			} else {
				Error::add('error during writing the log to the storage file');
				return false;
			}
		}
	}
	
	public static function getAll()
	{
		$log = Storage::read(Config::get('storagePathLog'));
		
		if(!empty($log)) {
			return $log;
		} else {
			Error::add('error during reading the log file');
		}
	}
    
    public static function reset()
    {
        Storage::write(Config::get('storagePathLog'), array());
    }
}
