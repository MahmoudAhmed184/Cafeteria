<?php

class ErrorHandler
{
    public static function register()
    {
        ini_set('display_errors', 0);
        error_reporting(E_ALL);

        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
    }

    public static function handleError($severity, $message, $file, $line)
    {
        self::log("Error: $message in $file on line $line");
    }

    public static function handleException($exception)
    {
        $message = $exception->getMessage();
        $file = $exception->getFile();
        $line = $exception->getLine();

        self::log("Exception: $message in $file on line $line");

        http_response_code(500);
        echo "Something went wrong.";
    }

    private static function log($message)
    {
        $date = date("Y-m-d H:i:s");

        $logMessage = "[$date] $message\n";

        file_put_contents(
            __DIR__ . "/../../logs/error.log",
            $logMessage,
            FILE_APPEND
        );
    }
}