<?php

require __DIR__ . '/_function.php';
require __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    checkCsrfToken();

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($email)) {
        responseJson("error", "กรุณาใส่อีเมล");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        responseJson("error", "กรุณาใส่อีเมลให้ถูกต้อง");
    }

    if (empty($password)) {
        responseJson("error", "กรุณาใส่รหัสผ่าน");
    }

    if (strlen($password) < 4) {
        responseJson("error", "รหัสผ่านต้อง 4 ตัวขึ้นไป");
    }

    if (empty($confirm_password)) {
        responseJson("error", "กรุณาใส่ยืนยันรหัสผ่าน");
    }

    if ($password != $confirm_password) {
        responseJson("error", "รหัสผ่านทั้ง 2 ช่องไม่สอดคล้องกัน");
    }

    try {
        $email_exist = $conn->prepare('SELECT EXISTS(SELECT 1 FROM users WHERE email = ?)');
        $email_exist->execute([$email]);

        if ($email_exist->fetchColumn()) {
            responseJson("error", "อีเมลนี้มีผู้ใช้งานแล้ว");
            
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('INSERT INTO users (email, password) VALUES (?, ?)');

            if ($stmt->execute([$email, $hashed_password])) {
                session_regenerate_id(true);
                responseJson("success", "สมัครสมาชิกสำเร็จ");
            } else {
                responseJson("error", "สมัครสมาชิกไม่สำเร็จ");
            }
        }
    } catch (PDOException $e) {
        responseJson("error", "เกิดข้อผิดพลาดบางอย่าง");
    }
}
