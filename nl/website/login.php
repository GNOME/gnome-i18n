<?php session_start();
include "functions.php";
must_be_logged_in();
header("Location: /");
