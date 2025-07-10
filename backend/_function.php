<?php

function responseJson($status, $msg)
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status' => $status, 'msg' => $msg]);
    exit;
}

function checkCsrfToken()
{
    if (empty($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        responseJson("error", "Invalid CSRF token");
    }
}

function checkUserLogin()
{
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user_login']) || !filter_var($_SESSION['user_login'], FILTER_VALIDATE_INT)) {
        responseJson("error", "กรุณาเข้าสู่ระบบ");
    }
}
