<?php
session_start();
if (isset($_SESSION['username']))
{
    unset($_SESSION['username']);
    unset($_SESSION['error']);
    unset($_SESSION['token']);
}
header("location:../View/login/login.php");
?>