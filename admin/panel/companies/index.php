<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header('Location: ../../login.php');
    exit;
}
else {
  header("Location: ../index.php");
}
?>
