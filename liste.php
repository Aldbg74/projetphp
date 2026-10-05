<?php

require "connexion.php";

$sql = "SELECT * FROM utilisateurs ORDER BY id DESC";

$stmt = $pdo->query($sql);

$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Toutes les cartes</title>

<style>

body {
    font-family: Arial, sans-serif;
    background: #f2f4f7;
    margin: 0;
    padding: 40px;
}

h1 {
    text-align: center;
}

.actions {
    text-align: center;
    margin-bottom: 30px;
}

.actions a {
    display: inline-block;
    padding: 10px 20px;
    background: #2563eb;
    color: white;
    text-decoration: none;
    border-radius: 8px;
}

.cartes {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

.carte {
    width: 300px;

    background: linear-gradient(
        135deg,
        #2563eb,
        #7c3aed
    );

    color: white;

    padding: 25px;

    border-radius: 15px;

    box-shadow:
    0 8px 20px rgba(0,0,0,0.20);
}

.carte h2 {
    margin-top: 0;
}

.info {
    background: rgba(255,255,255,0.15);

    padding: 10px;

    border-radius: 8px;

    margin-bottom: 10px;
}

.id {
    opacity: 0.6;
    font-size: 12px;
}

.aucune {
    text-align: center;
    font-size: 20px;
}

</style>

</head>

<body>

<h1>Cartes enregistrées</h1>

<div class="actions">

<a href="index.html">
+ Créer une nouvelle carte
</a>

</div>

<div class="cartes">

<?php if (count($utilisateurs) === 0): ?>

<p class="aucune">
Aucune carte enregistrée.
</p>

<?php else: ?>

<?php foreach ($utilisateurs as $user): ?>

<div class="carte">

<div class="id">
Carte #<?= $user["id"] ?>
</div>

<h2>
<?= htmlspecialchars($user["nom"]) ?>
</h2>

<div class="info">
<strong>Email :</strong><br>
<?= htmlspecialchars($user["email"]) ?>
</div>

<div class="info">
<strong>Âge :</strong><br>
<?= htmlspecialchars($user["age"]) ?> ans
</div>

<div class="info">
<strong>Taille :</strong><br>
<?= htmlspecialchars($user["taille"]) ?> cm
</div>

<div class="info">
<strong>Créée le :</strong><br>
<?= htmlspecialchars($user["date_creation"]) ?>
</div>

</div>

<?php endforeach; ?>

<?php endif; ?>

</div>

</body>

</html>
