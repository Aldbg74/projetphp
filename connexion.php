<?php

$host = "localhost";
$dbname = "formulaire_db";
$username = "formuser";
$password = "motdepasse123";

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {

    die("Erreur BDD : " . $e->getMessage());

}
