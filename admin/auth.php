<?php
session_start();

// Function: check if logged in
function checkAuth()
{
    if (empty($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
        // Store the page they were trying to access
        $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
        header("Location: login.php");
        exit;
    }
}
