<?php
// ============================================================
// creer_tache_form.php
// Form page for the encadrant to create a new task.
// Lists all groups so encadrant can pick which group to assign.
// ============================================================

require "../auth.verif.encadrant.php";
require "../Authentification/conexion_db.php";

// Load list of all groups to show in dropdown
$sql = "SELECT Id_Groupe FROM groupe ORDER BY Id_Groupe";
$stmt = $conn->prepare($sql);
$stmt->execute();
$groupes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une tâche — PFS</title>
    <link rel="stylesheet" href="../enseignant/ajouter_tache_enseignant.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="page_tache">

    <aside class="sidebar_enseignant">
        <div class="sidebar_top">
            <div class="logo_block">
                <i class="fa-solid fa-graduation-cap"></i>
                <h2>Enseignant</h2>
            </div>

            <nav class="nav_menu">
                <a href="/PFS/dashboard_enseignant/dashboard_enseignant.php" class="nav_link">
                    <i class="fa-solid fa-table-cells-large"></i>
                    <span>Tableau de bord</span>
                </a>
                <a href="/PFS/taches/liste_taches_encadrant.php" class="nav_link active_link">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Tâches</span>
                </a>
            </nav>
        </div>

        <a href="/PFS/logout.php" class="logout_btn">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Déconnexion</span>
        </a>
    </aside>

    <main class="main_tache">

        <section class="header_tache">
            <p class="welcome_text">
                BIENVENUE DANS L'ESPACE ENSEIGNANT.
            </p>
            <h1>Créer une tâche</h1>
            <p class="subtitle">
                Remplissez le formulaire pour assigner une tâche à un groupe d'étudiants.
            </p>
        </section>

        <section class="form_card">

            <!-- This form submits to ajouter_tache.php -->
            <!-- ajouter_tache.php will call the Java program -->
            <form action="ajouter_tache.php" method="POST" class="form_ajouter_tache">

                <!-- Task title -->
                <div class="form_group">
                    <label for="titre_tache">Titre de la tâche</label>
                    <input
                        type="text"
                        id="titre_tache"
                        name="titre_tache"
                        class="input_field"
                        placeholder="Ex: Rapport d'avancement"
                        required
                    >
                </div>

                <!-- Task description -->
                <div class="form_group">
                    <label for="description_tache">Description</label>
                    <textarea
                        id="description_tache"
                        name="description_tache"
                        class="textarea_field"
                        placeholder="Décrivez les consignes et les livrables attendus..."
                        required
                    ></textarea>
                </div>

                <!-- Group selection (dropdown) -->
                <div class="form_group">
                    <label for="id_groupe">Assigner au groupe</label>
                    <select id="id_groupe" name="id_groupe" class="input_field" required>
                        <option value="">-- Choisissez un groupe --</option>
                        <?php foreach ($groupes as $g): ?>
                            <option value="<?php echo $g['Id_Groupe']; ?>">
                                Groupe <?php echo htmlspecialchars($g['Id_Groupe']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Deadline -->
                <div class="form_group">
                    <label for="date_limite">Date limite de soumission</label>
                    <input
                        type="date"
                        id="date_limite"
                        name="date_limite"
                        class="input_field"
                        required
                    >
                </div>

                <!-- Buttons -->
                <div class="form_actions">
                    <a href="liste_taches_encadrant.php" class="btn_cancel">Annuler</a>
                    <button type="submit" class="btn_submit" name="btn_ajouter_tache">
                        <i class="fa-regular fa-floppy-disk"></i>
                        Créer la tâche
                    </button>
                </div>

            </form>

        </section>

    </main>

</div>

</body>
</html>
