<?php

class AdminMiddleware
{

    public static function handle()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            redirect("/project/cafeteria/login");
        }

        if ($_SESSION['role_id'] != 1) {
            redirect("/project/cafeteria/dashboard");
        }
    }
}
