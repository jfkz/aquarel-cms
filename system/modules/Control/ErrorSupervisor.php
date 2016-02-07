<?php
/**
 * Class:   Engine
 * About:   Класс, отслеживающий ошибки системы
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Control;

class ErrorSupervisor
{
    public function __construct()
    {
        // Registration errors
        set_error_handler(array($this, 'OtherErrorCatcher'));
        
        // Interception of critical errors
        register_shutdown_function(array($this, 'FatalErrorCatcher'));
        
        // Creation of the output buffer
        ob_start();
    }
    
    public function OtherErrorCatcher($errno, $errstr)
    {
        // ToDo:
        // - write to the log
        
        return false;
    }
    
    public function FatalErrorCatcher()
    {
        $error = error_get_last();
        if (isset($error))
        {
            if($error['type'] & ( E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR))
            {
                // Reset the buffer, the buffer exit
                ob_end_clean();	
                
                // ToDo:
                // - write to the log
                
                // Return error code 500
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                
                // Return data
                echo '<h2>Произошла ошибка</h2>';
                echo '<pre>';
                print_r($error);
                echo '</pre>';
            }
            else
            {
                // The output buffer, the buffer exit
                ob_end_flush();
            }
        }
        else
        {
            // The output buffer, the buffer exit
            ob_end_flush();
        }
    }
}