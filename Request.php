<?php

class Request
{
	private static $post = null;
	
	private static function init()
	{
		self::$post = json_decode(file_get_contents("php://input"), true);
	}
	
	public static function get($key)
	{
		if(isset($_GET[$key]))
		{
			return $_GET[$key];
		}
		
		return;
	}
	
	public static function post($key)
	{
		if(self::$post === null)
		{
			self::init();
		}
		
		if(isset(self::$post[$key]))
		{
			return self::$post[$key];
		}
		
		return;
	}
}
