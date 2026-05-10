<?php require "../auth.verif.admin.php"; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Créer Encadrant — PFS</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>

<!-- ===================== SIDEBAR ===================== -->
<div class="sidebar">

  <div class="logo">PFS</div>

  <div class="profile-box">
    <div class="profile-icon">👤</div>
    <div class="profile-text">
      <div class="profile-name">
        <?php echo htmlspecialchars($_SESSION['nom_admin']); ?>
      </div>
      <div class="profile-role">Administrateur</div>
    </div>
  </div>

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

<!-- ===================== CONTENU PRINCIPAL ===================== -->
<div class="main">

  <div class="top-bar">
    <h1>Créer un compte encadrant</h1>
  </div>

  <div class="form-card">

    <h2>Informations de l'encadrant</h2>
    <p class="sous-titre">La clé d'accès sera générée automatiquement</p>

    <form action="creer_encadrant.php" method="POST">

      <div class="champ">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom"
               placeholder="Ex : Benali" required minlength="3">
      </div>

      <div class="champ">
        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom"
               placeholder="Ex : Mohamed" required>
      </div>

      <button type="submit">Créer le compte</button>

    </form>

  </div>

</div>

</body>
</html>
