<?php
// ============================================================
// liste_taches_encadrant.php
// Shows the encadrant a list of all tasks they created,
// with each student's submission and status.
//
// This is the "task tracking interface" requested.
// It mirrors the style of dashboard_enseignant.php
// ============================================================

require "../auth.verif.encadrant.php";
require "../Authentification/conexion_db.php";

$idEncadrant = (int) $_SESSION['id_encadrant'];

// --- Get all tasks created by this encadrant ---
// We join with groupe to show the group code,
// and include the student names from the group
$sql = "SELECT 
            t.Id_Tache,
            t.titre_tache,
            t.description_tache,
            t.date_limite_tache,
            t.statut_tache,
            t.fichier_soumis,
            t.date_creation_tache,
            t.date_soumission_tache,
            g.Id_Groupe
        FROM tache t
        JOIN groupe g ON t.Id_Groupe = g.Id_Groupe
        WHERE t.Id_Encad = :id_encadrant
        ORDER BY t.date_creation_tache DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([":id_encadrant" => $idEncadrant]);
$taches = $stmt->fetchAll();

// --- For each task, get the students in that group ---
// We'll use a simple second query approach (easy to understand)
$tachesAvecEtudiants = [];
foreach ($taches as $tache) {
    $sqlEtd = "SELECT nom_Etudiant, prenom_Etudiant 
               FROM etudiant 
               WHERE Id_Groupe = :id_groupe";
    $stmtEtd = $conn->prepare($sqlEtd);
    $stmtEtd->execute([":id_groupe" => $tache['Id_Groupe']]);
    $etudiants = $stmtEtd->fetchAll();

    // Add students list to the task array
    $tache['etudiants'] = $etudiants;
    $tachesAvecEtudiants[] = $tache;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Tâches — PFS</title>
    <link rel="stylesheet" href="../dashboard_enseignant/dashboard_ensaignant.css">
    <link rel="stylesheet" href="taches.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="page_enseignant">

    <!-- ===================== SIDEBAR (same as dashboard_enseignant.php) ===================== -->
    <aside class="sidebar_enseignant">

        <div class="sidebar_top">
            <div class="logo_block">
                <i class="fa-solid fa-graduation-cap"></i>
                <h2 class="logo_title">Enseignant</h2>
            </div>

            <nav class="nav_menu_enseignant">
                <a href="/PFS/dashboard_enseignant/dashboard_enseignant.php" class="nav_link">
                    <i class="fa-solid fa-table-columns"></i>
                    <span>Tableau de bord</span>
                </a>
                <a href="/PFS/taches/liste_taches_encadrant.php" class="nav_link active_link">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Tâches</span>
                </a>
            </nav>
        </div>

        <div class="sidebar_bottom">
            <a href="/PFS/logout.php" class="logout_btn">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Déconnexion</span>
            </a>
        </div>

    </aside>

    <!-- ===================== MAIN CONTENT ===================== -->
    <main class="main_content_enseignant">

        <section class="intro_section_enseignant">
            <p class="intro_text_enseignant">LISTE DE VOS TÂCHES ET SOUMISSIONS DES ÉTUDIANTS.</p>
            <h1 class="page_title_enseignant">Mes Tâches</h1>
        </section>

        <!-- Success message if redirected after creating a task -->
        <?php if (isset($_GET['succes'])): ?>
        <div class="alert_succes">
            ✓ Tâche créée avec succès !
        </div>
        <?php endif; ?>

        <!-- Button to add a new task -->
        <div style="margin-bottom: 20px;">
            <a href="/PFS/taches/creer_tache_form.php" class="btn_ajouter_tache">
                <i class="fa-solid fa-plus"></i> Ajouter une tâche
            </a>
        </div>

        <section class="table_section_enseignant">
            <div class="table_card_enseignant">

                <div class="table_wrapper">
                    <table class="groupes_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Titre de la tâche</th>
                                <th>Groupe assigné</th>
                                <th>Étudiants</th>
                                <th>Date limite</th>
                                <th>Statut</th>
                                <th>Soumission PDF</th>
                                <th>Accepter</th>
                                <th>Refuser</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php if (empty($tachesAvecEtudiants)): ?>
                            <tr>
                                <td colspan="9" style="text-align:center; color:#999;">
                                    Aucune tâche pour l'instant.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($tachesAvecEtudiants as $t): ?>
                            <tr>
                                <!-- Task ID -->
                                <td><?php echo $t['Id_Tache']; ?></td>

                                <!-- Task title with description tooltip -->
                                <td title="<?php echo htmlspecialchars($t['description_tache']); ?>">
                                    <?php echo htmlspecialchars($t['titre_tache']); ?>
                                </td>

                                <!-- Group code -->
                                <td>
                                    <span class="code_badge">
                                 <?php echo htmlspecialchars($t['Id_Groupe']); ?>
                                    </span>
                                </td>

                                <!-- List of students in the group -->
                                <td>
                                    <?php foreach ($t['etudiants'] as $etd): ?>
                                        <div>
                                            <?php echo htmlspecialchars($etd['prenom_Etudiant'] . ' ' . $etd['nom_Etudiant']); ?>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php if (empty($t['etudiants'])): ?>
                                        <span class="pas_soumis">Aucun étudiant</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Deadline -->
                                <td><?php echo htmlspecialchars($t['date_limite_tache']); ?></td>

                                <!-- Status badge (same style as dashboard_enseignant.php) -->
                                <td>
                                    <?php if ($t['statut_tache'] === 'accepte'): ?>
                                        <span class="badge_statut badge_accepte">Accepté</span>
                                    <?php elseif ($t['statut_tache'] === 'refuse'): ?>
                                        <span class="badge_statut badge_refuse">Refusé</span>
                                    <?php elseif ($t['statut_tache'] === 'soumis'): ?>
                                        <span class="badge_statut badge_attente">Soumis</span>
                                    <?php else: ?>
                                        <span class="badge_statut badge_attente">En attente</span>
                                    <?php endif; ?>
                                </td>

                                <!-- PDF submission link -->
                                <td>
                                    <?php if (!empty($t['fichier_soumis'])): ?>
                                        <a href="/PFS/uploads/taches/<?php echo htmlspecialchars($t['fichier_soumis']); ?>"
                                           target="_blank" class="pdf_link">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </a>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>

                                <!-- Accept button (only if status is 'soumis') -->
                                <td>
                                    <?php if ($t['statut_tache'] === 'soumis'): ?>
                                        <form action="valider_tache.php" method="POST">
                                            <input type="hidden" name="id_tache" value="<?php echo $t['Id_Tache']; ?>">
                                            <button type="submit" class="btn_action btn_valider">Accepter</button>
                                        </form>
                                    <?php endif; ?>
                                </td>

                                <!-- Refuse button (only if status is 'soumis') -->
                                <td>
                                    <?php if ($t['statut_tache'] === 'soumis'): ?>
                                        <form action="refuser_tache.php" method="POST">
                                            <input type="hidden" name="id_tache" value="<?php echo $t['Id_Tache']; ?>">
                                            <button type="submit" class="btn_action btn_refuser">Refuser</button>
                                        </form>
                                    <?php endif; ?>
                                </td>

                            </tr>
                        <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>

            </div>
        </section>

    </main>
</div>

</body>
</html>
