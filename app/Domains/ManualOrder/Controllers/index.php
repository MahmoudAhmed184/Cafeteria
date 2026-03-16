<?php

require_once ROOT . "middleware/AdminMiddleware.php";
require_once ROOT . "app/Domains/ManualOrder/Services/ManualOrderService.php";

AdminMiddleware::handle();

$manualOrderService = new ManualOrderService();

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'search':
        $searchTerm = $_GET['q'] ?? '';
        $users = $manualOrderService->searchUsers($searchTerm);

        header('Content-Type: application/json');
        echo json_encode($users);
        exit();

    case 'store':
        $userId = (int) ($_POST['user_id'] ?? 0);
        $roomNo = $_POST['room_no'] ?? '';
        $notes = $_POST['notes'] ?? null;
        $items = $_POST['items'] ?? [];

        $result = $manualOrderService->placeOrderForUser($userId, $roomNo, $notes, $items);

        header('Content-Type: application/json');
        echo json_encode($result);
        exit();

    default:
        view("ManualOrder/index");
        break;
}
