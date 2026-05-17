<?php
require "../auth.verif.encadrant.php";
/* =============================================
   CONNEXION BASE DE DONNÉES
============================================= */
require "../Authentification/conexion_db.php";

// Récupérer tous les formulaires soumis avec leur groupe
$sql = "SELECT f.Id_Formulaire, f.titre, f.rapport, f.statut, f.Date_creationF,
               g.Id_Groupe, g.code_groupe
        FROM formulaire f
        JOIN groupe g ON f.Id_Groupe = g.Id_Groupe
        ORDER BY f.Date_creationF DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$formulaires = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Enseignant — PFS</title>
    <link rel="stylesheet" href="dashboard_ensaignant.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="page_enseignant">

    <!-- ===================== SIDEBAR ===================== -->
    <aside class="sidebar_enseignant">

        <div class="sidebar_top">
            <div class="logo_block">
                <i class="fa-solid fa-graduation-cap"></i>
                <h2 class="logo_title">Enseignant</h2>
            </div>

            <nav class="nav_menu_enseignant">
                <a href="dashboard_enseignant.php" class="nav_link active_link">
                    <i class="fa-solid fa-table-columns"></i>
                    <span>Tableau de bord</span>
                </a>
                <a href="groupes_enseignant.html" class="nav_link">
                    <i class="fa-solid fa-users"></i>
                    <span>Groupes</span>
                </a>
                <a href="etudiants_enseignant.html" class="nav_link">
                    <i class="fa-solid fa-user-graduate"></i>
                    <span>Etudiants</span>
                </a>
                <a href="taches_enseignant.php" class="nav_link">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Taches</span>
                </a>
                <a href="soutenance_enseignant.html" class="nav_link">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Soutenance</span>
                </a>
                <a href="reglages_enseignant.html" class="nav_link">
                    <i class="fa-solid fa-gear"></i>
                    <span>Réglages</span>
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

    <!-- ===================== CONTENU PRINCIPAL ===================== -->
    <main class="main_content_enseignant">

        <section class="intro_section_enseignant">
            <p class="intro_text_enseignant">
                BIENVENUE DANS L'ESPACE ENSEIGNANT. VOICI LA LISTE DES GROUPES ET LEURS PROJETS SOUMIS.
            </p>
            <h1 class="page_title_enseignant">Tableau de bord Enseignant</h1>
        </section>

        <section class="table_section_enseignant">
            <div class="table_card_enseignant">

                <div class="table_actions_bar">
                    <button type="button" class="action_top_btn">
                        <i class="fa-solid fa-filter"></i>
                        <span>Filters</span>
                    </button>
                    <button type="button" class="action_top_btn">
                        <i class="fa-solid fa-file-export"></i>
                        <span>Export</span>
                    </button>
                </div>

                <div class="table_wrapper">
                    <table class="groupes_table">
                        <thead>
                            <tr>
                                <th>Numero Groupe</th>
                                <th>Code</th>
                                <th>Sujet</th>
                                <th>Statut</th>
                                <th>Rapport PDF</th>
                                <th>Valider</th>
                                <th>Refuser</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($formulaires as $f): ?>
                            <tr>
                                <td><?php echo $f['Id_Groupe']; ?></td>
                                <td><span class="code_badge"><?php echo htmlspecialchars($f['code_groupe']); ?></span></td>
                                <td><?php echo htmlspecialchars($f['titre']); ?></td>
                                <td>
                                    <?php if ($f['statut'] === 'accepte'): ?>
                                        <span class="badge_statut badge_accepte">Accepté</span>
                                    <?php elseif ($f['statut'] === 'refuse'): ?>
                                        <span class="badge_statut badge_refuse">Refusé</span>
                                    <?php else: ?>
                                        <span class="badge_statut badge_attente">En attente</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($f['rapport'])): ?>
                                        <a href="/PFS/uploads/<?php echo htmlspecialchars($f['rapport']); ?>" target="_blank" class="pdf_link">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </a>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($f['statut'] === 'en attente'): ?>
                                        <form action="valider_projet.php" method="POST">
                                            <input type="hidden" name="id_formulaire" value="<?php echo $f['Id_Formulaire']; ?>">
                                            <button type="submit" class="btn_action btn_valider">Accepter</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($f['statut'] === 'en attente'): ?>
                                        <form action="refuser_projet.php" method="POST">
                                            <input type="hidden" name="id_formulaire" value="<?php echo $f['Id_Formulaire']; ?>">
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