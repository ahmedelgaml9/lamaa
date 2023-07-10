<?php

namespace App\helpers;
use Request;
use App\Models\LogActivity as LogActivityModel;

class LogActivity
{

    public static function addToLog($subject)
    {
    	$log = [];
    	$log['subject'] = $subject;
    	$log['url'] = Request::fullUrl();
    	$log['method'] = Request::method();
    	$log['ip'] = Request::ip();
    	$log['agent'] = Request::header('user-agent');
    	$log['user_id'] = auth()->check() ? auth()->user()->id : 1;
    	$log['user_name'] = auth()->check() ? auth()->user()->name :"admin";

    	LogActivityModel::create($log);
    }

	public function myTestAddToLog()
    {
        \LogActivity::addToLog('My Testing Add To Log.');

    }

    public static function logActivityLists()
    {
    	return LogActivityModel::orderBy('created_at','desc')->paginate(15);
    }
}