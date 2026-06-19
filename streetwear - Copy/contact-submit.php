<?php
include "DBConn.php";
session_start();
$store_name = 'Bpleasant.';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: chat.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($email === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['chat_error'] = 'Please enter a valid email address and a message.';
    header('Location: chat.php');
    exit;
}

$log_entry = sprintf(
    "[%s] %s\nEmail: %s\nMessage:\n%s\n---\n",
    date('Y-m-d H:i:s'),
    $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    $email,
    $message
);

$file = __DIR__ . '/chat-messages.txt';
file_put_contents($file, $log_entry, FILE_APPEND | LOCK_EX);

$_SESSION['chat_success'] = 'Your message has been sent. We will reply as soon as possible.';
header('Location: chat.php');
exit;
