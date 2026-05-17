<?php
require "../auth.verif.encadrant.php";
require "../Authentification/conexion_db.php";

// Traitement valider / refuser via Java
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id_tache'])) {
    $id_tache = intval($_POST['id_tache']);
    $java_dir = realpath(__DIR__ . "/../java");

    if ($_POST['action'] === 'valider') {
        $cmd = "java -cp \"$java_dir;$java_dir/mysql-connector.jar\" MainValiderTache " . $id_tache;
    } else {
        $cmd = "java -cp \"$java_dir;$java_dir/mysql-connector.jar\" MainRefuserTache " . $id_tache;
    }
    shell_exec($cmd);
    header("Location: taches_enseignant.php?updated=1");
    exit;
}

// Récupérer toutes les tâches avec leur groupe
$sql = "SELECT t.Id_Tache, t.titre, t.DescriptionT, t.Date_limiteT, t.statut, t.fichier,
               g.Id_Groupe, g.code_groupe
        FROM tache t
        JOIN groupe g ON t.Id_Groupe = g.Id_Groupe
        ORDER BY t.Id_Tache DESC";
$taches = $conn->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tâches — PFS</title>
    <link rel="stylesheet" href="dashboard_ensaignant.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .top_actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        .btn_creer {
            background: #2563eb;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }
        .btn_creer:hover { background: #1d4ed8; }
        .alert_succes {
            background: #dcfce7;
            color: #16a34a;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 13px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .desc_cell {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 13px;
            color: #6b7280;
        }
        .btn_action_form {
            display: inline;
        }
    </style>
</head>
<body>
<div class="page_enseignant">

    <!-- SIDEBAR -->
    <aside class="sidebar_enseignant">
        <div class="sidebar_top">
            <div class="logo_block">
                <i class="fa-solid fa-graduation-cap"></i>
                <h2 class="logo_title">Enseignant</h2>
            </div>
            <nav class="nav_menu_enseignant">
                <a href="dashboard_enseignant.php" class="nav_link">
                    <i class="fa-solid fa-table-columns"></i>
                    <span>Tableau de bord</span>
                </a>
                <a href="taches_enseignant.php" class="nav_link active_link">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Tâches</span>
                </a>
                <a href="stats_taches.php" class="nav_link">
                    <i class="fa-solid fa-chart-bar"></i>
                    <span>Statistiques</span>
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

    <!-- MAIN -->
    <main class="main_content_enseignant">

        <section class="intro_section_enseignant">
            <p class="intro_text_enseignant">GESTION DES TÂCHES — JAVA + JDBC</p>
            <h1 class="page_title_enseignant">Liste des tâches</h1>
        </section>

        <?php if (isset($_GET['succes'])): ?>
            <div class="alert_succes">
                <i class="fa-solid fa-circle-check"></i>
                Tâche créée avec succès via Java !
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['updated'])): ?>
            <div class="alert_succes">
                <i class="fa-solid fa-circle-check"></i>
                Statut de la tâche mis à jour via Java !
            </div>
        <?php endif; ?>

        <section class="table_section_enseignant">
            <div class="table_card_enseignant">

                <div class="table_actions_bar">
                    <a href="stats_taches.php" class="action_top_btn">
                        <i class="fa-solid fa-chart-bar"></i>
                        <span>Statistiques</span>
                    </a>
                    <a href="creer_tache.php" class="btn_creer">
                        <i class="fa-solid fa-plus"></i>
                        Nouvelle tâche
                    </a>
                </div>

                <div class="table_wrapper">
                    <table class="groupes_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Titre</th>
                                <th>Description</th>
                                <th>Groupe</th>
                                <th>Date limite</th>
                                <th>Statut</th>
                                <th>Fichier</th>
                                <th>Valider</th>
                                <th>Refuser</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($taches)): ?>
                            <tr>
                                <td colspan="9" style="text-align:center;color:#6b7280;padding:30px;">
                                    Aucune tâche pour l'instant.
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($taches as $t): ?>
                            <tr>
                                <td><?php echo $t['Id_Tache']; ?></td>
                                <td style="font-weight:600;"><?php echo htmlspecialchars($t['titre']); ?></td>
                                <td class="desc_cell"><?php echo htmlspecialchars($t['DescriptionT']); ?></td>
                                <td>
                                    <span class="code_badge"><?php echo htmlspecialchars($t['code_groupe']); ?></span>
                                </td>
                                <td><?php echo htmlspecialchars($t['Date_limiteT']); ?></td>
                                <td>
                                    <?php if ($t['statut'] === 'accepte'): ?>
                                        <span class="badge_statut badge_accepte">Accepté</span>
                                    <?php elseif ($t['statut'] === 'refuse'): ?>
                                        <span class="badge_statut badge_refuse">Refusé</span>
                                    <?php elseif ($t['statut'] === 'soumis'): ?>
                                        <span class="badge_statut" style="background:#dbeafe;color:#2563eb;">Soumis</span>
                                    <?php else: ?>
                                        <span class="badge_statut badge_attente">En attente</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($t['fichier'])): ?>
                                        <a href="/PFS/uploads/<?php echo htmlspecialchars($t['fichier']); ?>"
                                           target="_blank" class="pdf_link">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </a>
                                    <?php else: ?>
                                        <span style="color:#d1d5db;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($t['statut'] === 'soumis'): ?>
                                        <form method="POST" class="btn_action_form">
                                            <input type="hidden" name="id_tache" value="<?php echo $t['Id_Tache']; ?>">
                                            <input type="hidden" name="action" value="valider">
                                            <button type="submit" class="btn_action btn_valider">Valider</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($t['statut'] === 'soumis'): ?>
                                        <form method="POST" class="btn_action_form">
                                            <input type="hidden" name="id_tache" value="<?php echo $t['Id_Tache']; ?>">
                                            <input type="hidden" name="action" value="refuser">
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