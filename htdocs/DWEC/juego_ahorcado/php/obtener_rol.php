<?php
session_start();
echo $_SESSION['admin'] ?? 0;
?>