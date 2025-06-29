<?php
// /controller/ProfileController.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/UserModel.php';

class ProfileController {
    private $userModel;

    public function __construct(Database $database) {
        $db = $database->connect();
        $this->userModel = new UserModel($db);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
            header("Location: /MindCraft-Project/views/landingpage/login.php");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $action = $_POST['action'] ?? '';

        if ($action === 'update_profile') {
            $profileData = [
                'user_id' => $user_id,
                'full_name' => $_POST['full_name'] ?? '',
                'bio' => $_POST['bio'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'website' => $_POST['website'] ?? '',
                'linkedin' => $_POST['linkedin'] ?? '',
                'instagram' => $_POST['instagram'] ?? '',
                'youtube' => $_POST['youtube'] ?? '',
                'specialization' => $_POST['specialization'] ?? '',
                'experience_years' => $_POST['experience_years'] ?? 0
            ];
            
            if ($this->userModel->updateMentorProfile($profileData)) {
                header("Location: /MindCraft-Project/views/mentor/pengaturan.php?success=profile_updated");
            } else {
                header("Location: /MindCraft-Project/views/mentor/pengaturan.php?error=profile_failed");
            }
        } elseif ($action === 'update_password') {
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if (empty($current_password) || empty($new_password) || $new_password !== $confirm_password) {
                header("Location: /MindCraft-Project/views/mentor/pengaturan.php?error=password_mismatch");
                exit();
            }

            $user = $this->userModel->getMentorById($user_id);

            if ($user && password_verify($current_password, $user['password'])) {
                if ($this->userModel->updatePassword($user_id, $new_password)) {
                    header("Location: /MindCraft-Project/views/mentor/pengaturan.php?success=password_updated");
                } else {
                    header("Location: /MindCraft-Project/views/mentor/pengaturan.php?error=password_failed");
                }
            } else {
                header("Location: /MindCraft-Project/views/mentor/pengaturan.php?error=password_incorrect");
            }
        } else {
            header("Location: /MindCraft-Project/views/mentor/pengaturan.php?error=invalid_action");
        }
        exit();
    }
}

// Routing sederhana untuk controller ini
if (isset($_GET['action']) && $_GET['action'] == 'update_settings') {
    $database = new Database();
    $controller = new ProfileController($database);
    $controller->update();
}