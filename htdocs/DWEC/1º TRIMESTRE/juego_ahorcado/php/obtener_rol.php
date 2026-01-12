<?php
session_start();
echo (int)($_SESSION['admin'] ?? 0);
?>