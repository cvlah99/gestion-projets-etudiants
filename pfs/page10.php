<?php
/***********************
 * 1. Sécurité & session
 ***********************/
require "../auth.verif.php";

// Vérification appartenance à un groupe
if (empty($_SESSION['id_groupe'])) {
    header("Location: /PFS/cree_rejoindre_grp/cree_rejoindre.html");
    exit;
}

// Connexion à la base de données
require "../Authentification/conexion_db.php";

/*************************
 * 2. Récupérer les membres
 *************************/
$id_groupe = $_SESSION['id_groupe'];

$sql = "SELECT nom_Etudiant, prenom_Etudiant
        FROM etudiant
        WHERE Id_Groupe = :id_groupe";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ":id_groupe" => $id_groupe
]);

$membres = $stmt->fetchAll();

// Récupérer le code du groupe
$sql_code = "SELECT code_groupe FROM groupe WHERE Id_Groupe = :id_groupe";
$stmt_code = $conn->prepare($sql_code);
$stmt_code->execute([
    ":id_groupe" => $id_groupe
]);

$groupe = $stmt_code->fetch();
$code_groupe = $groupe ? $groupe['code_groupe'] : '';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="page10.css">
<title>Dashboard Groupe</title>


</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="profile">
    <div class="avatar">👤</div>
    <div class="profile-info">
      <div class="name">
        <?php echo htmlspecialchars($_SESSION['nom'] . " " . $_SESSION['prenom']); ?>
      </div>
    </div>
  </div>

  
<nav class="nav">
  <a class="nav-item active" href="/PFS/pfs/page10.php">Dashboard</a>
  <a class="nav-item" href="/PFS/pfs/tache.php">Tache a soumettre</a>
  <a class="nav-item" href="/PFS/pfs/soutenance.php">Soutenance</a>
</nav>


  <div class="logout">
    <form action="/PFS/logout.php" method="post">
      <button class="logout-btn">Déconnexion</button>
    </form>
  </div>
</aside>

<main class="main">

  <!-- HEADER -->
  <div class="header">
    <div class="header-left">
      <div class="code-label">
        CODE DU GROUPE : <?php echo htmlspecialchars($code_groupe); ?>
      </div>
      <h1>Groupe Dashboard</h1>
    </div>

    <button class="btn-project">
      + Remplir le formulaire de project
    </button>
  </div>

  <!-- CONTENT -->
  <div class="content-row">

    <!-- LEFT -->
    <div class="members-card">

      <h2>Membres :</h2>

      <div class="member-list">
        <?php foreach ($membres as $membre): ?>
          <div class="member-item">
            <div class="member-avatar">👤</div>
            <span class="member-name">
              <?php echo htmlspecialchars($membre['nom_Etudiant']." ".$membre['prenom_Etudiant']); ?>
            </span>
          </div>
        <?php endforeach; ?>
      </div>

    </div>

    <!-- RIGHT -->
    <div class="right-col">

      <!-- CALENDAR -->
      <div class="calendar-card">
        <div class="cal-header">
          <span class="month" id="calendar-month"></span>
        </div>

        <div class="cal-grid" id="calendar-grid"></div>
      </div>

      <!-- NOTIFICATIONS -->
      <div class="notif-card">
        <h3>Notifications récentes</h3>

        <div class="notif-item">
          <div class="notif-text">
            Cher groupe veuillez remplir le formulaire
          </div>
          <div class="notif-time">2 HOURS AGO</div>
        </div>

        <a href="#" class="view-all">
          VIEW ALL NOTIFICATIONS
        </a>
      </div>

    </div>

  </div>

</main>




<script>
  /**************************************************
   * RÉCUPÉRATION DES ZONES HTML
   **************************************************/

  // Zone où afficher "Mai 2026", etc.
  const monthLabel = document.getElementById("calendar-month");

  // Grille où seront ajoutés les jours
  const calendarGrid = document.getElementById("calendar-grid");


  /**************************************************
   * DATE ACTUELLE
   **************************************************/

  // Date actuelle du système
  const now = new Date();

  // Année courante
  const year = now.getFullYear();

  // Mois courant (0 = Janvier, 11 = Décembre)
  const month = now.getMonth();

  // Jour actuel du mois
  const today = now.getDate();


  /**************************************************
   * NOMS DES MOIS (FR)
   **************************************************/

  const monthNames = [
    "Janvier", "Février", "Mars", "Avril",
    "Mai", "Juin", "Juillet", "Août",
    "Septembre", "Octobre", "Novembre", "Décembre"
  ];

  // Afficher le mois + année
  monthLabel.textContent = monthNames[month] + " " + year;


  /**************************************************
   * CALCUL DU MOIS
   **************************************************/

  // Jour de la semaine du 1er jour du mois
  const firstDay = new Date(year, month, 1).getDay();

  // Nombre de jours dans le mois
  const daysInMonth = new Date(year, month + 1, 0).getDate();


  /**************************************************
   * AFFICHER LES NOMS DES JOURS
   **************************************************/

  const weekDays = ["SU", "MO", "TU", "WE", "TH", "FR", "SA"];

  weekDays.forEach(day => {
    const label = document.createElement("div");
    label.className = "cal-day-label";
    label.textContent = day;
    calendarGrid.appendChild(label);
  });


  /**************************************************
   * CASES VIDES AVANT LE 1ER JOUR
   **************************************************/

  for (let i = 0; i < firstDay; i++) {
    const emptyCell = document.createElement("div");
    emptyCell.className = "cal-day other-month";
    calendarGrid.appendChild(emptyCell);
  }


  /**************************************************
   * AFFICHER TOUS LES JOURS DU MOIS
   **************************************************/

  for (let day = 1; day <= daysInMonth; day++) {

    const dayCell = document.createElement("div");
    dayCell.className = "cal-day";
    dayCell.textContent = day;

    // Surligner le jour actuel
    if (day === today) {
      dayCell.classList.add("today");
    }

    calendarGrid.appendChild(dayCell);
  }
</script>
</body>
</html>