<?php

$user = 'admin';
$pass = 'admin123';
$db = 'modeldb';

$db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect");

echo "Great Work!!!";