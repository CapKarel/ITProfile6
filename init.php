<?php
session_start();

try {
    $db = new PDO("sqlite:profile.db");
    // Nastavení vyhazování výjimek v případě chyby
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vytvoření tabulky, pokud neexistuje
    $query = "CREATE TABLE IF NOT EXISTS interests (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL UNIQUE
              )";
    $db->exec($query);
} catch (PDOException $e) {
    die("Chyba databáze: " . $e->getMessage());
}
