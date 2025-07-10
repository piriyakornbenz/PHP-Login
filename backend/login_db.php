<?php

require __DIR__ . '/_function.php';
require __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    checkCsrfToken();

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email)) {
        responseJson("error", "กรุณาใส่อีเมล");
    }

    if (empty($password)) {
        responseJson("error", "กรุณาใส่รหัสผ่าน");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        responseJson("error", "กรุณาใส่อีเมลให้ถูกต้อง");
    }

    try {
        $stmt = $conn->prepare('SELECT id, password FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_login'] = $user['id'];
            $_SESSION['logged_in'] = true;
            responseJson("success", "เข้าสู่ระบบสำเร็จ");

        } else {
            $userId = $user['id'] ?? 0;
            responseJson("error", "อีเมลหรือรหัสผ่านผิด");
        }
    } catch (PDOException $e) {
        responseJson("error", "เกิดข้อผิดพลาดบางอย่าง");
    }
}
