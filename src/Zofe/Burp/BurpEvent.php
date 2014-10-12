<?php

namespace Zofe\Burp;

/**
 * Class BurpEvent
 * simple event listener, to be used in Burp
 *
 * @package Zofe\Burp
 */
class BurpEvent
{
    public static $events = array();
    public static $queue = array();
    
    public static function listen($event, \Closure $func)
    {
        self::$events[$event][] = $func;
    }

    public static function queue($event, $args = array())
    {
        self::$queue[$event][] = $args;
    }

    public static function flush($event)
    {
        if(isset(self::$queue[$event]) AND isset(self::$events[$event]))
        {
            foreach(self::$queue[$event] as $ev=>$args)
            {
                $func = array_shift(self::$events[$event]);
                call_user_func_array($func, $args);
            }
            self::$queue[$event] = array();
        }
    }

    public static function flushAll()
    {
        foreach(self::$queue as $event => $events)
        {
            self::flush($event);
        }
    }
    
    public static function fire($event, $args = array())
    {
        if(isset(self::$events[$event]))
        {
            foreach(self::$events[$event] as $func)
            {
                call_user_func_array($func, $args);
            }
        }

    }
}