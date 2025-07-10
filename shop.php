<?php

require __DIR__ . '/config/config.php';

if (!isset($_SESSION['user_login']) || $_SESSION['logged_in'] !== true) {
    header('Location: ./index.php');
    exit();
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id=:id");
$stmt->execute([$_SESSION['user_login']]);
$user = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="robots" content="noindex, nofollow">

    <!-- title -->
    <title>หน้าหลัก</title>
    <!-- link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="wrapper" id="content">
        <main>
            <div class="container">
                <div class="row justify-content-center mx-1 mt-5">
                    <div class="text-center mb-2">
                        <h1>ยินดีต้อนรับ <?= $user['email'] ?></h1>
                        <a href="backend/logout_db.php" class="btn btn-danger" onclick="return confirm('ยืนยันที่จะออกจากระบบ?')">ออกจากระบบ</a>
                    </div>
                    
                </div>
            </div>

        </main>
    </div>

</body>

</html>