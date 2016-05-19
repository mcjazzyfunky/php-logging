<?php

namespace logging\adapters;

use logging\LogAdapter;
use logging\adapters\internal\CustomLog;

class CustomLogAdapter implements LogAdapter {
    private $logs;
    
    function __construct(
        callable $performLogRequest,
        callable $getThresholdByLogName = null) {
        
        $this->performLogRequest = $performLogRequest;
        $this->getThresholdByLogName = $getThresholdByLogName;
        $this->logs = [];
    }
    
    function getLog($name) {
        $ret = @$this->logs[$name];
        
        if ($ret === null) {
            $ret = new CustomLog(
                $name, $this->performLogRequest);
                
            $this->logs[$name] = $ret;
        }
        
        return $ret;
    }
    
    function getThresholdByLogName($name) {
        return Logger::getDefaultThreshold();
    }
}
