<?php
/* =============================================
   VÉRIFICATION SESSION + RÉCUPÉRATION DONNÉES
============================================= */
require "../auth.verif.php";

// Si l'étudiant n'a pas de groupe → redirection
if (empty($_SESSION['id_groupe'])) {
    header("Location: /PFS/cree_rejoindre_grp/cree_rejoindre.html");
    exit;
}

// Connexion base de données
require "../Authentification/conexion_db.php";

$id_groupe = $_SESSION['id_groupe'];

// Récupérer les membres du groupe
$sql = "SELECT nom_Etudiant, prenom_Etudiant
        FROM etudiant
        WHERE Id_Groupe = :id_groupe";

$stmt = $conn->prepare($sql);
$stmt->execute([":id_groupe" => $id_groupe]);
$membres = $stmt->fetchAll();

// Récupérer le code du groupe
$sql_code = "SELECT code_groupe FROM groupe WHERE Id_Groupe = :id_groupe";
$stmt_code = $conn->prepare($sql_code);
$stmt_code->execute([":id_groupe" => $id_groupe]);
$groupe     = $stmt_code->fetch();
$code_groupe = $groupe ? $groupe['code_groupe'] : '';


$sql_form = "SELECT statut FROM formulaire WHERE Id_Groupe = :id_groupe";
$stmt_form = $conn->prepare($sql_form);
$stmt_form->execute([":id_groupe" => $id_groupe]);
$formulaire = $stmt_form->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard — PFS</title>
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<!-- ===================== SIDEBAR ===================== -->
<div class="sidebar">

  <div class="logo">PFS</div>

  <div class="profile-box">
    <div class="profile-icon">👤</div>
    <div class="profile-text">
      <div class="profile-name">
        <?php echo htmlspecialchars($_SESSION['nom'] . " " . $_SESSION['prenom']); ?>
      </div>
      <div class="profile-role">Étudiant</div>
    </div>
  </div>

  <nav>
    <a class="nav-link active" href="/PFS/dashboard_etudiant/dashboard.php">Dashboard</a>
    <a class="nav-link" href="/PFS/pfs/tache.php">Tâche à soumettre</a>
    <a class="nav-link" href="/PFS/pfs/soutenance.php">Soutenance</a>
  </nav>

  <div class="sidebar-bottom">
    <form action="/PFS/logout.php" method="post">
      <button class="logout-btn" type="submit">Déconnexion</button>
    </form>
  </div>

</div>

<!-- ===================== CONTENU PRINCIPAL ===================== -->
<div class="main">

  <!-- En-tête -->
  <div class="top-bar">
    <div>
      <div class="group-code">
        CODE DU GROUPE :
        <span class="code-badge"><?php echo htmlspecialchars($code_groupe); ?></span>
      </div>
      <h1>Tableau de bord</h1>
    </div>

    <?php if ($formulaire): ?>
    <!-- Formulaire déjà soumis → badge orange -->
    <div class="badge-attente">Project : En attente de validation</div>
    <?php else: ?>
      <!-- Pas encore soumis → bouton bleu -->
      <a class="btn-formulaire" href="/PFS/dashboard_etudiant/formulaire.html">
        + Remplir le formulaire de projet
      </a>
    <?php endif; ?>
  </div>

  <!-- Contenu -->
  <div class="content">

    <!-- Membres du groupe -->
    <div class="card membres-card">
      <div class="card-header">
        <h2>Membres du groupe</h2>
        <span class="count-badge"><?php echo count($membres); ?></span>
      </div>

      <?php foreach ($membres as $membre): ?>
        <div class="membre-item">
          <div class="membre-avatar">
            <?php echo strtoupper(mb_substr($membre['prenom_Etudiant'], 0, 1)); ?>
          </div>
          <span class="membre-nom">
            <?php echo htmlspecialchars($membre['nom_Etudiant'] . " " . $membre['prenom_Etudiant']); ?>
          </span>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Colonne droite -->
    <div class="right-column">

      <!-- Calendrier -->
      <div class="card">
        <div class="cal-title" id="cal-month"></div>
        <div class="cal-grid" id="cal-grid"></div>
      </div>

      <!-- Notifications -->
      <div class="card">
        <h3 class="notif-title">Notifications</h3>
        <div class="notif-item">
          <div class="notif-dot"></div>
          <div>
            <div class="notif-text">Veuillez remplir le formulaire de projet</div>
            <div class="notif-time">Il y a 2 heures</div>
          </div>
        </div>
        <a href="#" class="voir-tout">Voir tout →</a>
      </div>

    </div>
  </div>

</div>

<!-- ===================== CALENDRIER (JavaScript) ===================== -->
<script src="calendrier.js"></script>

</body>
</html>
