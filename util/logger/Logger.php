<?php

namespace util\logger;

use util\config\Config;
use util\uploadfile\FileUploaderHelper;

class Logger {

    private static $uri;
    private static $instance;
    private static $backtrace;

    private $path_folder_logs;

    public function __construct() {
        FileUploaderHelper::createDirectoryIfNotExists(Config::get('path_logfiles'));
        $this->path_folder_logs = Config::get('path_logfiles') . DIRECTORY_SEPARATOR;
    }

    public static function getLogger() {
        if ( !isset(self::$instance) ) {
            self::$instance = new Logger();
        }

        return self::$instance;
    }

    public function system( $message ) {
        $this->log('system', $message);
    }

    public function warning( $message ) {
        $this->log('warning', $message);
    }

    public function debug( $message ) {
        $this->log('debug', $message);
    }

    public function error( $message ) {
        $this->log('error', $message);
    }

    public function info( $message ) {
        $this->log('info', $message);
    }

    public function logDelimiter( $delimiter, $log_file ) {
        $this->writeDelimiterToFile($delimiter, $log_file);
    }

    public function log( $level, $message ) {
        self::$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        self::$uri = is_array($_SERVER) && array_key_exists('REQUEST_URI', $_SERVER) ? $_SERVER['REQUEST_URI'] : "";

        $full_message = "[MESSAGE LOG START]\n";

        if ( is_a($message, 'Exception') ) {
            $full_message .= $message->getCode() . "\n";
            $full_message .= $message->getMessage() . "\n";
            $full_message .= $message->getTraceAsString() . "\n";
        } else {
            $full_message .= print_r($message, true) . "\n";
        }

        if ( !empty(self::$uri) ) {
            $full_message .= "[URL] " . self::$uri . " [URL]\n";
        }

        $full_message .= "[MESSAGE LOG END]\n";

        $this->writeToFile($level, $full_message);
    }

    private function writeToFile( $level, $data ) {
        $fd = fopen($this->path_folder_logs . $level . '.log', 'a');

        if ( !$fd ) {
            echo "<pre> $data </pre>";
        } else {
            if ( isset(self::$backtrace[1]) && !empty(self::$backtrace[1]) ) {
                if ( !fwrite($fd, date('Y-m-d H:i:s') . " [ " . self::$backtrace[1]["file"] . " (" . self::$backtrace[1]["line"] . ") ] " . $data) ) {
                    echo "<pre> $data </pre>";
                }
            } else {
                if ( !fwrite($fd, "[ " . date('Y-m-d H:i:s') . " ] " . $data) ) {
                    echo "<pre> $data </pre>";
                }
            }

            fclose($fd);
        }
    }

    private function writeDelimiterToFile( $delimiter, $log_file ) {
        $fd = fopen($this->path_folder_logs . $log_file . '.log', 'a');

        if ( !$fd ) {
            echo "<pre> $delimiter </pre>";
        } else {
            if ( !fwrite($fd, $delimiter . "\n") ) {
                echo "<pre> $delimiter </pre>";
            }

            fclose($fd);
        }
    }
}