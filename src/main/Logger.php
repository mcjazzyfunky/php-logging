<?php

namespace logging;

use InvalidArgumentException;
use logging\Log;
use logging\adapters\NullLoggerAdapter;

final class Logger {
    private static $adapter = null;
    private static $nullLog = null;
    private static $defaultThreshold = Log::NONE;
    
    private function Logger() {
    }
    
    static function getLog($name) {
        $isObject = is_object($name);
        $isString = is_string($name);
        
        if (!$isObject && !$isString) {
            throw new InvalidArgumentException(
                '[Logger.getLog] First argument $name must not be '
                . 'a string or an object');
        } else if ($isString
            && (strlen($name) === 0 || trim($name) !== $name)) {
            
            throw new InvalidArgumentException(
                '[Logger.getLog] First argument $name must not be '
                . "a valid name name ('$name' is invalid");
        }
        
        $logName =
            is_object($name)
            ? get_class($name)
            : $name;
        
        if (self::$adapter === null) {
            self::$adapter = new NullLoggerAdapter();
        }
        
        return self::$adapter->getLog($logName);
    }
    
    static function getNullLog() {
        if (self::$nullLog === null) {
            self::$nullLog = new NullLog(); 
        }
        
        return self::$nullLog;
    }
    
    static function setDefaultThreshold($level) {
        self::$defaultThreshold = $level;
    }
    
    static function getDefaultThreshold() {
        return self::$defaultThreshold; 
    }
    
    static function getThresholdByLogName($name) {
        return
            self::adapter === null
            ? Log::NONE
            : self::$adapter->getThresholdByLogName($name);
    }
    
    static function setAdapter(LoggerAdapter $adapter) {
        self::$adapter = $adapter;
    }
    
    static function getAdatper() {
        return self::$adapter;
    }
}