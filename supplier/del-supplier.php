<?php

session_start();

if(!isset($_SESSION["ssLoginPOS"])){
  header("location: ../auth/login.php");
  exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-supplier.php";

$id = $_GET['id'];

if(delete($id)){
    echo "
        <script>document.location.href = '../supplier?msg=deleted';
        </script>
    ";
} else {
    echo "
        <script>document.location.href = '../supplier?msg=aborted';
        </script>
    ";
}