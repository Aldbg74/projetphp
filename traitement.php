<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require "connexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Accès interdit : le formulaire doit être envoyé en POST.");
}

$nom = trim($_POST["nom"] ?? "");
$email = trim($_POST["email"] ?? "");
$taille = trim($_POST["taille"] ?? "");
$age = trim($_POST["age"] ?? "");

if (
    $nom === "" ||
    $email === "" ||
    $taille === "" ||
    $age === ""
) {
    die("Erreur : tous les champs sont obligatoires.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Erreur : adresse email invalide.");
}

if (!is_numeric($taille) || !is_numeric($age)) {
    die("Erreur : l'âge et la taille doivent être numériques.");
}

try {

    $sql = "
    INSERT INTO utilisateurs
    (nom, email, taille, age)
    VALUES
    (:nom, :email, :taille, :age)
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":nom" => $nom,
        ":email" => $email,
        ":taille" => $taille,
        ":age" => $age
    ]);

    // ID de l'utilisateur qui vient d'être créé
    $id = $pdo->lastInsertId();

    // URL de la carte personnelle
    $urlCarte =
    "http://" .
    $_SERVER["HTTP_HOST"] .
    "/form/carte.php?id=" .
    $id;

    // Génération du QR Code
    $qrCodeUrl =
    "https://api.qrserver.com/v1/create-qr-code/" .
    "?size=200x200&data=" .
    urlencode($urlCarte);

} catch (PDOException $e) {

    die(
        "Erreur lors de l'enregistrement : " .
        $e->getMessage()
    );

}

?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"
>

<title>Carte créée</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;

    font-family: Arial, sans-serif;

    background: #f2f4f7;

    display: flex;
    justify-content: center;
    align-items: center;

    min-height: 100vh;

    padding: 30px;
}

.carte {
    width: 380px;

    padding: 30px;

    border-radius: 20px;

    background: linear-gradient(
        135deg,
        #2563eb,
        #7c3aed
    );

    color: white;

    box-shadow:
    0 10px 30px rgba(0,0,0,0.2);
}

.carte h1 {
    margin-top: 0;
    text-align: center;
}

.numero {
    text-align: center;
    opacity: 0.7;
    margin-bottom: 20px;
}

.info {
    margin-bottom: 15px;

    padding: 12px;

    background:
    rgba(255,255,255,0.15);

    border-radius: 8px;
}

.label {
    font-size: 12px;
    opacity: 0.7;

    text-transform: uppercase;
}

.value {
    margin-top: 4px;
    font-size: 17px;
}

.message {
    text-align: center;

    background:
    rgba(255,255,255,0.12);

    padding: 10px;

    border-radius: 8px;

    margin-top: 20px;
}

.qr {
    text-align: center;

    margin-top: 25px;
}

.qr img {
    width: 180px;
    height: 180px;

    background: white;

    padding: 10px;

    border-radius: 12px;
}

.qr p {
    margin-bottom: 5px;
}

.actions {
    margin-top: 25px;

    display: flex;
    flex-direction: column;

    gap: 10px;
}

.actions a {
    display: block;

    padding: 12px;

    text-align: center;

    background:
    rgba(255,255,255,0.15);

    color: white;

    text-decoration: none;

    border-radius: 8px;
}

.actions a:hover {
    background:
    rgba(255,255,255,0.25);
}

</style>

</head>

<body>

<div class="carte">

<h1>
<?= htmlspecialchars($nom) ?>
</h1>

<div class="numero">
Carte #<?= htmlspecialchars($id) ?>
</div>

<div class="info">

<div class="label">
Email
</div>

<div class="value">
<?= htmlspecialchars($email) ?>
</div>

</div>

<div class="info">

<div class="label">
Âge
</div>

<div class="value">
<?= htmlspecialchars($age) ?> ans
</div>

</div>

<div class="info">

<div class="label">
Taille
</div>

<div class="value">
<?= htmlspecialchars($taille) ?> cm
</div>

</div>

<div class="message">
✅ Carte enregistrée dans la base.
</div>

<div class="qr">

<h3>QR Code</h3>

<img
src="<?= htmlspecialchars($qrCodeUrl) ?>"
alt="QR Code de la carte utilisateur"
>

<p>
Scannez pour ouvrir cette carte
</p>

</div>

<div class="actions">

<a href="carte.php?id=<?= urlencode($id) ?>">
Ouvrir ma carte
</a>

<a href="index.html">
Créer une nouvelle carte
</a>

<a href="liste.php">
Voir toutes les cartes
</a>

</div>

</div>

</body>

</html>
