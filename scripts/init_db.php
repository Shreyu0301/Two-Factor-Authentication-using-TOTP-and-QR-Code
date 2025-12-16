<?php
// scripts/init_db.php
// Run: php scripts/init_db.php

if (!is_dir(__DIR__ . '/../data')) mkdir(__DIR__ . '/../data', 0755, true);
$pdo = new PDO('sqlite:' . __DIR__ . '/../data/app.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// create table
$pdo->exec("
CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  email TEXT UNIQUE NOT NULL,
  password_hash TEXT NOT NULL,
  twofa_secret TEXT,
  twofa_enabled INTEGER DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
");

// create a sample user (email: user@example.com / password: password123)
$passwordHash = password_hash('password123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT OR IGNORE INTO users (email, password_hash) VALUES (?, ?)");
$stmt->execute(['user@example.com', $passwordHash]);

echo "DB & sample user ready.\n";
