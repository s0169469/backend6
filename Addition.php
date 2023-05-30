<?php
header('Content-Type: text/html; charset=UTF-8');
$user = 'u51489'; 
$pass = '7565858'; 
$db = new PDO('mysql:host=localhost;dbname=u51489', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 
?>
