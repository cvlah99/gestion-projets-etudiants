<?php
// ============================================================
// tache_etudiant.php
// Shows the student their assigned tasks and lets them upload a file.
//
// This is accessed from the student dashboard sidebar.
// ============================================================

require "../auth.verif.php";
require "../Authentification/conexion_db.php";

// Make sure the student has a group
if (empty($_SESSION['id_groupe'])) {
    header("Location: /PFS/cree_rejoindre_grp/cree_rejoindre.html");
    exit;
}

$idGroupe = (int) $_SESSION['id_groupe'];

// --- Get all tasks assigned to this group ---
$sql = "SELECT 
            t.Id_Tache,
            t.titre_tache,
            t.description_tache,
            t.date_limite_tache,
            t.statut_tache,
            t.fichier_soumis,
            t.date_soumission_tache,
            e.nom_Encad,
            e.prenom_Encad
        FROM tache t
        LEFT JOIN encadrant e ON t.Id_Encad = e.Id_Encad
        WHERE t.Id_Groupe = :id_groupe
        ORDER BY t.date_creation_tache DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([":id_groupe" => $idGroupe]);
$taches = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Tâches — PFS</title>
    <link rel="stylesheet" href="../dashboard_etudiant/dashboard.css">
    <link rel="stylesheet" href="taches.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<!-- ===================== SIDEBAR (same as student dashboard) ===================== -->
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
        <a class="nav-link" href="/PFS/dashboard_etudiant/dashboard.php">Dashboard</a>
        <a class="nav-link active" href="/PFS/taches/tache_etudiant.php">Tâche à soumettre</a>
    </nav>

    <div class="sidebar-bottom">
        <form action="/PFS/logout.php" method="post">
            <button class="logout-btn" type="submit">Déconnexion</button>
        </form>
    </div>

</div>

<!-- ===================== MAIN CONTENT ===================== -->
<div class="main">

    <div class="top-bar">
        <h1>Mes Tâches à Soumettre</h1>
    </div>

    <div class="content">

        <?php if (empty($taches)): ?>
            <div class="card" style="padding: 30px; text-align: center; color: #999;">
                Aucune tâche assignée pour l'instant.
            </div>
        <?php endif; ?>

        <!-- Show each task as a card -->
        <?php foreach ($taches as $tache): ?>
        <div class="card tache_card">

            <!-- Task header -->
            <div class="tache_header">
                <h3><?php echo htmlspecialchars($tache['titre_tache']); ?></h3>

                <!-- Status badge -->
                <?php if ($tache['statut_tache'] === 'accepte'): ?>
                    <span class="badge_statut badge_accepte">✓ Accepté</span>
                <?php elseif ($tache['statut_tache'] === 'refuse'): ?>
                    <span class="badge_statut badge_refuse">✗ Refusé</span>
                <?php elseif ($tache['statut_tache'] === 'soumis'): ?>
                    <span class="badge_statut badge_soumis">⏳ En cours de révision</span>
                <?php else: ?>
                    <span class="badge_statut badge_attente">📋 En attente de soumission</span>
                <?php endif; ?>
            </div>

            <!-- Task details -->
            <p class="tache_description">
                <?php echo nl2br(htmlspecialchars($tache['description_tache'])); ?>
            </p>

            <div class="tache_meta">
                <span>
                    <i class="fa-solid fa-calendar-xmark"></i>
                    Date limite : <strong><?php echo htmlspecialchars($tache['date_limite_tache']); ?></strong>
                </span>
                <?php if (!empty($tache['nom_Encad'])): ?>
                <span>
                    <i class="fa-solid fa-user-tie"></i>
                    Assigné par : <?php echo htmlspecialchars($tache['prenom_Encad'] . ' ' . $tache['nom_Encad']); ?>
                </span>
                <?php endif; ?>
            </div>

            <!-- If already submitted, show the submitted file -->
            <?php if (!empty($tache['fichier_soumis'])): ?>
            <div class="tache_soumis_info">
                <i class="fa-solid fa-check-circle" style="color:green;"></i>
                Fichier soumis le <?php echo htmlspecialchars($tache['date_soumission_tache']); ?> :
                <a href="/PFS/uploads/taches/<?php echo htmlspecialchars($tache['fichier_soumis']); ?>"
                   target="_blank">
                    <?php echo htmlspecialchars($tache['fichier_soumis']); ?>
                </a>
            </div>
            <?php endif; ?>

            <!-- Upload form — only show if not yet accepted/refused -->
            <?php if ($tache['statut_tache'] === 'en attente' || $tache['statut_tache'] === 'refuse'): ?>
            <form action="soumettre_tache.php" method="POST" enctype="multipart/form-data" class="upload_form">
                <input type="hidden" name="id_tache" value="<?php echo $tache['Id_Tache']; ?>">

                <div class="form_group" style="margin-top: 15px;">
                    <label>Soumettre votre fichier (PDF) :</label>
                    <input type="file" name="fichier_tache" accept=".pdf" required class="input_field">
                </div>

                <button type="submit" class="btn_submit" style="margin-top: 10px;">
                    <i class="fa-solid fa-upload"></i>
                    Envoyer la soumission
                </button>
            </form>
            <?php endif; ?>

        </div>
        <?php endforeach; ?>

    </div>
</div>

</body>
</html>
