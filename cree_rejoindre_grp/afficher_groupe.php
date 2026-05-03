<?php
session_start();

if (!isset($_SESSION['code_groupe'])) {
    header("Location: creer_groupe.php");
    exit;
}

$code = $_SESSION['code_groupe'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cree_groupe.css">
    <title>Groupe créé</title>
</head>
<body>
    <div class="container">
        <h2>Groupe créé !</h2>
        <p>Partagez ce code avec vos coéquipiers.</p>
        <span><?php echo $code; ?></span>
        <a href="dashboard.php">Aller au tableau de bord</a>
    </div>
</body>
</html>