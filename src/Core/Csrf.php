<?php

function csrf_token() {
    if (!isset($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}

function verify_csrf_token() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['_csrf']) || $_POST['_csrf'] !== ($_SESSION['_csrf_token'] ?? '')) {
            http_response_code(403);
            exit('Invalid CSRF token');
        }
    }
}
