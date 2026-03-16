<?php

require_once ROOT . "app/Models/Model.php";
require_once ROOT . "app/Database/Database.php";

class UserService
{

    public function getAllUsers(): array
    {
        $db = Database::connect();
        $connection = $db->getConnectionInstance();

        $result = $connection->query(
            "SELECT id, name, email, room_no, ext, profile_pic, role_id, is_active
             FROM users
             WHERE is_active = 1
             ORDER BY name"
        );

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    public function createUser(array $data, ?array $file = null): array
    {
        $db = Database::connect();
        $connection = $db->getConnectionInstance();

        $email = $connection->real_escape_string($data['email']);
        $result = $connection->query("SELECT id FROM users WHERE email = '$email'");
        if ($result->num_rows > 0) {
            return ["success" => false, "message" => "This email is already registered."];
        }

        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        $profilePic = null;
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $profilePic = $this->handleFileUpload($file, 'profiles');
            if ($profilePic === false) {
                return ["success" => false, "message" => "Failed to upload profile picture."];
            }
        }

        $name = $connection->real_escape_string($data['name']);
        $roomNo = $connection->real_escape_string($data['room_no']);
        $ext = $connection->real_escape_string($data['ext']);
        $profilePicValue = $profilePic ? "'$profilePic'" : "NULL";

        $connection->query(
            "INSERT INTO users (name, email, password, room_no, ext, profile_pic, role_id)
             VALUES ('$name', '$email', '$hashedPassword', '$roomNo', '$ext', $profilePicValue, 2)"
        );

        return ["success" => true, "message" => "User created successfully."];
    }

    public function updateUser(int $id, array $data, ?array $file = null): array
    {
        $db = Database::connect();
        $connection = $db->getConnectionInstance();

        $email = $connection->real_escape_string($data['email']);
        $result = $connection->query("SELECT id FROM users WHERE email = '$email' AND id != $id");
        if ($result->num_rows > 0) {
            return ["success" => false, "message" => "This email is already taken by another user."];
        }

        $name = $connection->real_escape_string($data['name']);
        $roomNo = $connection->real_escape_string($data['room_no']);
        $ext = $connection->real_escape_string($data['ext']);

        $setClause = "name = '$name', email = '$email', room_no = '$roomNo', ext = '$ext'";

        if (!empty($data['password'])) {
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
            $setClause .= ", password = '$hashedPassword'";
        }

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $profilePic = $this->handleFileUpload($file, 'profiles');
            if ($profilePic !== false) {
                $this->deleteOldProfilePic($id);
                $setClause .= ", profile_pic = '$profilePic'";
            }
        }

        $connection->query("UPDATE users SET $setClause WHERE id = $id");

        return ["success" => true, "message" => "User updated successfully."];
    }

    public function deleteUser(int $id): array
    {
        $db = Database::connect();
        $connection = $db->getConnectionInstance();

        $result = $connection->query("SELECT COUNT(*) AS count FROM orders WHERE user_id = $id");
        $row = $result->fetch_assoc();
        $hasOrders = $row['count'] > 0;

        if ($hasOrders) {
            $connection->query("UPDATE users SET is_active = 0 WHERE id = $id");
            return ["success" => true, "message" => "User has been deactivated (has existing orders)."];
        } else {
            $this->deleteOldProfilePic($id);
            $connection->query("DELETE FROM users WHERE id = $id");
            return ["success" => true, "message" => "User deleted successfully."];
        }
    }

    public function findUser(int $id): ?array
    {
        $db = Database::connect();
        $connection = $db->getConnectionInstance();

        $result = $connection->query("SELECT * FROM users WHERE id = $id");
        return $result->fetch_assoc() ?: null;
    }

    private function handleFileUpload(array $file, string $subfolder)
    {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $uploadDir = ROOT . "uploads/$subfolder/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $destination = $uploadDir . $filename;
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $filename;
        }

        return false;
    }

    private function deleteOldProfilePic(int $userId)
    {
        $db = Database::connect();
        $connection = $db->getConnectionInstance();

        $result = $connection->query("SELECT profile_pic FROM users WHERE id = $userId");
        $user = $result->fetch_assoc();

        if ($user && $user['profile_pic']) {
            $filePath = ROOT . "uploads/profiles/" . $user['profile_pic'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }
}
