<?php
// src/db.php
$pdo = new PDO('sqlite:' . __DIR__ . '/../data/app.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
