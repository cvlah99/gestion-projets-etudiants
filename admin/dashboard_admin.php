<?php

session_start();

if (empty($_SESSION['id_admin'])) {
    header("Location: login_admin.html");
    exit();
}

require "../Authentification/conexion_db.php";



// Nombre total d'étudiants
$sql = "SELECT COUNT(*) AS total FROM etudiant";
$stmt = $conn->prepare($sql);
$stmt->execute();
$total_etudiants = $stmt->fetch()['total'];

// Nombre total de groupes
$sql = "SELECT COUNT(*) AS total FROM groupe";
$stmt = $conn->prepare($sql);
$stmt->execute();
$total_groupes = $stmt->fetch()['total'];

// Nombre de formulaires soumis
$sql = "SELECT COUNT(*) AS total FROM formulaire";
$stmt = $conn->prepare($sql);
$stmt->execute();
$total_formulaires = $stmt->fetch()['total'];

// Nombre de formulaires acceptés
$sql = "SELECT COUNT(*) AS total FROM formulaire WHERE statut = 'accepté'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$total_acceptes = $stmt->fetch()['total'];

// Nombre de formulaires refusés
$sql = "SELECT COUNT(*) AS total FROM formulaire WHERE statut = 'refusé'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$total_refuses = $stmt->fetch()['total'];


$sql = "SELECT g.code_groupe, g.Date_creationGrp,
               f.titre, f.statut,
               COUNT(e.Id_Etudiant) AS nb_membres
        FROM groupe g
        LEFT JOIN formulaire f ON f.Id_Groupe = g.Id_Groupe
        LEFT JOIN etudiant e ON e.Id_Groupe = g.Id_Groupe
        GROUP BY g.Id_Groupe, g.code_groupe, g.Date_creationGrp, f.titre, f.statut";

$stmt = $conn->prepare($sql);
$stmt->execute();
$groupes = $stmt->fetchAll();


$sql = "SELECT e.nom_Etudiant, e.prenom_Etudiant, e.email,
               e.Group_cls, g.code_groupe
        FROM etudiant e
        LEFT JOIN groupe g ON g.Id_Groupe = e.Id_Groupe
        ORDER BY e.nom_Etudiant";

$stmt = $conn->prepare($sql);
$stmt->execute();
$etudiants = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin — PFS</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>

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
    <a class="nav-link active" href="dashboard_admin.php">Dashboard</a>
    <a class="nav-link" href="#section-groupes">Groupes</a>
    <a class="nav-link" href="#section-etudiants">Étudiants</a>
    <a class="nav-link" href="creer_encadrant.php">Créer encadrant</a>
  </nav>

  <div class="sidebar-bottom">
    <form action="logout_admin.php" method="post">
      <button class="logout-btn" type="submit">Déconnexion</button>
    </form>
  </div>

</div>

<div class="main">

  <!-- En-tête -->
  <div class="top-bar">
    <h1>Dashboard Administrateur</h1>
  </div>

  <!-- ===== STATISTIQUES ===== -->
  <div class="stats">

    <div class="stat-card">
      <div class="stat-nombre"><?php echo $total_etudiants; ?></div>
      <div class="stat-label">Étudiants inscrits</div>
    </div>

    <div class="stat-card">
      <div class="stat-nombre"><?php echo $total_groupes; ?></div>
      <div class="stat-label">Groupes créés</div>
    </div>

    <div class="stat-card">
      <div class="stat-nombre"><?php echo $total_formulaires; ?></div>
      <div class="stat-label">Formulaires soumis</div>
    </div>

    <div class="stat-card vert">
      <div class="stat-nombre"><?php echo $total_acceptes; ?></div>
      <div class="stat-label">Acceptés</div>
    </div>

    <div class="stat-card rouge">
      <div class="stat-nombre"><?php echo $total_refuses; ?></div>
      <div class="stat-label">Refusés</div>
    </div>

  </div>

  <!-- ===== GROUPES ===== -->
  <div class="section" id="section-groupes">

    <h2>Groupes</h2>

    <table>
      <thead>
        <tr>
          <th>Code groupe</th>
          <th>Date création</th>
          <th>Membres</th>
          <th>Titre projet</th>
          <th>Statut</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($groupes as $groupe): ?>
          <tr>
            <td><span class="code-badge"><?php echo htmlspecialchars($groupe['code_groupe']); ?></span></td>
            <td><?php echo htmlspecialchars($groupe['Date_creationGrp']); ?></td>
            <td><?php echo $groupe['nb_membres']; ?></td>
            <td>
              <?php echo $groupe['titre'] ? htmlspecialchars($groupe['titre']) : '<span class="pas-soumis">Pas encore soumis</span>'; ?>
            </td>
            <td>
              <?php if ($groupe['statut']): ?>
                <span class="badge-statut <?php echo $groupe['statut']; ?>">
                  <?php echo htmlspecialchars($groupe['statut']); ?>
                </span>
              <?php else: ?>
                <span class="badge-statut attente">—</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  </div>

  <!-- ===== ÉTUDIANTS ===== -->
  <div class="section" id="section-etudiants">

    <h2>Étudiants inscrits</h2>

    <table>
      <thead>
        <tr>
          <th>Nom</th>
          <th>Prénom</th>
          <th>Email</th>
          <th>Classe</th>
          <th>Groupe</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($etudiants as $etudiant): ?>
          <tr>
            <td><?php echo htmlspecialchars($etudiant['nom_Etudiant']); ?></td>
            <td><?php echo htmlspecialchars($etudiant['prenom_Etudiant']); ?></td>
            <td><?php echo htmlspecialchars($etudiant['email']); ?></td>
            <td><?php echo htmlspecialchars($etudiant['Group_cls']); ?></td>
            <td>
              <?php echo $etudiant['code_groupe']
                ? '<span class="code-badge">' . htmlspecialchars($etudiant['code_groupe']) . '</span>'
                : '<span class="pas-soumis">Sans groupe</span>'; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  </div>

</div>

</body>
</html>
