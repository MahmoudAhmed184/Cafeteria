<?php

class GuestMiddleware
{

    public static function handle()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role_id'] == 1) {
                redirect("/project/cafeteria/admin/orders");
            } else {
                redirect("/project/cafeteria/dashboard");
            }
        }
    }
}
