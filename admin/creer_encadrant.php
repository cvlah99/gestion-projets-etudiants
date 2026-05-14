<?php
require "../auth.verif.admin.php";
/* =============================================
   VÉRIFICATION SESSION ADMIN
============================================= */
/* =============================================
   TRAITEMENT DU FORMULAIRE
============================================= */
if (isset($_POST["nom"]) && isset($_POST["prenom"])) {

    $nom      = trim($_POST["nom"]);
    $prenom   = trim($_POST["prenom"]);
    $id_admin = $_SESSION['id_admin'];

    /* ---- Vérification champs vides ---- */
    if (empty($nom) || empty($prenom)) {
        echo "Veuillez remplir tous les champs.";
        exit();
    }

    /* ---- Appeler le binaire C++ ---- */
    $exe = __DIR__ . "/../cpp/encadrant.exe";

    $commande = '"' . $exe . '" ' . escapeshellarg($nom) . " " . escapeshellarg($prenom) . " " . $id_admin;
    $cle = trim(shell_exec($commande));

    /* ---- Vérifier si la clé a été générée ---- */
    if (empty($cle) || $cle === "ERREUR") {
        echo "Erreur lors de la création du compte.";
        exit();
    }

    /* ---- Afficher la clé à l'admin ---- */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Compte créé — PFS</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="sidebar">
  <div class="logo">PFS</div>
  <nav>
    <a class="nav-link" href="dashboard_admin.php">Dashboard</a>
    <a class="nav-link active" href="#">Créer encadrant</a>
  </nav>
  <div class="sidebar-bottom">
    <form action="logout_admin.php" method="post">
      <button class="logout-btn" type="submit">Déconnexion</button>
    </form>
  </div>
</div>

<div class="main">

  <div class="top-bar">
    <h1>Compte créé avec succès</h1>
  </div>

  <div class="form-card">

    <h2>Informations de connexion</h2>
    <p class="sous-titre">Transmettez ces informations à l'encadrant</p>

    <div class="info-item">
      <span class="info-label">Nom complet</span>
      <span class="info-valeur"><?php echo htmlspecialchars($prenom . " " . $nom); ?></span>
    </div>

    <div class="info-item">
      <span class="info-label">Clé d'accès</span>
      <span class="code-badge"><?php echo htmlspecialchars($cle); ?></span>
    </div>

    <a href="dashboard_admin.php" class="btn-retour">Retour au dashboard</a>

  </div>

</div>

</body>
</html>

<?php
} else {
    header("Location: creer_encadrant_form.php");
    exit();
}
?>
