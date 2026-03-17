<?php
session_start();

try {
    $db = new PDO("sqlite:profile.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // vytvoření tabulky
    $db->exec("
        CREATE TABLE IF NOT EXISTS interests (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL UNIQUE
        )
    ");
} catch (PDOException $e) {
    die("Chyba připojení: " . $e->getMessage());
}
