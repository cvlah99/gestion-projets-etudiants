<?php
session_start();
require("../Authentification/conexion_db.php");
require("authverf.php");
$sql = "
    SELECT g.Id_Groupe, g.sujet, g.statut, g.fichier
    FROM groupe g
    ORDER BY g.Id_Groupe ASC
";
$stmt = $conn->prepare($sql);
$stmt->execute();
$groupes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Enseignant</title>
    <link rel="stylesheet" href="dashboard_enseignant.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="page_enseignant">

    <aside class="sidebar_enseignant" id="sidebar_enseignant">
        <div class="sidebar_top">
            <div class="logo_block">
                <i class="fa-solid fa-graduation-cap"></i>
                <h2 class="logo_title">Enseignant</h2>
            </div>
            <nav class="nav_menu_enseignant">
                <a href="dashboard_enseignant.php" class="nav_link active_link" id="nav_dashboard_ens">
                    <i class="fa-solid fa-table-columns"></i>
                    <span>Tableau de bord</span>
                </a>
                <a href="groupes_enseignant.php" class="nav_link" id="nav_groupes_ens">
                    <i class="fa-solid fa-users"></i>
                    <span>Groupes</span>
                </a>
                <a href="etudiants_enseignant.php" class="nav_link" id="nav_etudiants_ens">
                    <i class="fa-solid fa-user-graduate"></i>
                    <span>Etudiants</span>
                </a>
                <a href="ajouter_tache_enseignant.php" class="nav_link" id="nav_taches_ens">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Taches</span>
                </a>
                <a href="soutenance_enseignant.php" class="nav_link" id="nav_soutenance_ens">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Soutenance</span>
                </a>
                <a href="reglages_enseignant.php" class="nav_link" id="nav_reglages_ens">
                    <i class="fa-solid fa-gear"></i>
                    <span>Réglages</span>
                </a>
            </nav>
        </div>
        <div class="sidebar_bottom">
            <a href="logout.php" class="logout_btn" id="logout_ens">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Déconnexion</span>
            </a>
        </div>
    </aside>

    <main class="main_content_enseignant" id="main_content_enseignant">

        <section class="intro_section_enseignant">
            <p class="intro_text_enseignant">
                BIENVENUE DANS L'ESPACE ENSEIGNANT. VOICI LA LISTE DES GROUPES ET LEURS PROJETS SOUMIS.
            </p>
            <h1 class="page_title_enseignant">Tableau de bord Enseignant</h1>
        </section>

        <section class="table_section_enseignant">
            <div class="table_card_enseignant">

                <div class="table_actions_bar">
                    <button type="button" class="action_top_btn filter_btn" id="filter_btn_ens">
                        <i class="fa-solid fa-filter"></i>
                        <span>Filters</span>
                    </button>
                    <button type="button" class="action_top_btn export_btn" id="export_btn_ens">
                        <i class="fa-solid fa-file-export"></i>
                        <span>Export</span>
                    </button>
                </div>

                <div class="table_wrapper">
                    <table class="groupes_table" id="groupes_table">
                        <thead>
                            <tr>
                                <th>Numero Groupe</th>
                                <th>Sujet</th>
                                <th>Valider</th>
                                <th>Refuser</th>
                                <th>Détail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($groupes as $g): ?>
                            <tr>
                                <td><?= htmlspecialchars($g['Id_Groupe']) ?></td>
                                <td><?= htmlspecialchars($g['sujet']) ?></td>

                                <td>
                                    <?php if ($g['statut'] === 'valide'): ?>
                                        <span class="btn_action btn_valider">Accepté</span>
                                    <?php else: ?>
                                        <form action="valider_projet.php" method="POST"
                                              onsubmit="return confirmerAction(event, 'valider', <?= (int)$g['Id_Groupe'] ?>)">
                                            <input type="hidden" name="id_groupe" value="<?= (int)$g['Id_Groupe'] ?>">
                                            <button type="submit" class="btn_action btn_valider"
                                                    name="btn_valider_<?= (int)$g['Id_Groupe'] ?>">
                                                Accepter
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if ($g['statut'] === 'refuse'): ?>
                                        <span class="btn_action btn_refuser">Refusé</span>
                                    <?php else: ?>
                                        <form action="refuser_projet.php" method="POST"
                                              onsubmit="return confirmerAction(event, 'refuser', <?= (int)$g['Id_Groupe'] ?>)">
                                            <input type="hidden" name="id_groupe" value="<?= (int)$g['Id_Groupe'] ?>">
                                            <button type="submit" class="btn_action btn_refuser"
                                                    name="btn_refuser_<?= (int)$g['Id_Groupe'] ?>">
                                                Refuser
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <a href="voir_fichier.php?id_groupe=<?= (int)$g['Id_Groupe'] ?>"
                                       class="detail_link"
                                       name="detail_groupe_<?= (int)$g['Id_Groupe'] ?>"
                                       target="_blank">
                                        <i class="fa-solid fa-file-lines"></i>
                                    </a>
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

<script>
function confirmerAction(e, type, idGroupe) {
    e.preventDefault();
    var msg = type === 'valider'
        ? 'Confirmer l\'acceptation du groupe ' + idGroupe + ' ?'
        : 'Confirmer le refus du groupe ' + idGroupe + ' ?';
    if (confirm(msg)) {
        e.target.submit();
    }
    return false;
}
</script>

</body>
</html>
