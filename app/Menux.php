<?php

namespace App;

use Log;

class Menux
{
    public static function logError($file, $line, $method, $e, $params = null)
    {
    	$traceArray = array();

    	if($e)
    	{
    		$traceArray = Menux::getTraceArray($e);
    	}
    	
    	Log::error($file.'@'.$line.'('.$method.')', array('trace' => $traceArray, 'params' => $params));	
    }

    public static function getTraceArray($e)
    {
        if(isset($e->getTrace()[0]['file']))
        {
            return array('msg' => $e->getMessage(), 'trc' => $e->getTrace()[0]['file'].' Line: '.$e->getTrace()[0]['line']);
        }
        else
        {
            return array('msg' => $e->getMessage());	
        }
    }
}