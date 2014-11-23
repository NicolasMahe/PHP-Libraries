<?php

class Date
{
    /**
     * get date and time as a string in ISO 8601 representation
     * @return string (eg: 2014-11-22T06:57:17Z)
     */
    public static function isoString()
    {
        return gmdate("Y-m-d\TH:i:s\Z");
    }
}
