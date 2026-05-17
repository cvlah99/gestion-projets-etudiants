<?php
require "../auth.verif.encadrant.php";
require "../Authentification/conexion_db.php";

$erreur = "";
$succes = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre       = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $date_limite = trim($_POST['date_limite'] ?? '');
    $id_groupe   = intval($_POST['id_groupe'] ?? 0);
    $id_encad    = $_SESSION['id_encadrant'];

    if (empty($titre) || empty($description) || empty($date_limite) || $id_groupe === 0) {
        $erreur = "Veuillez remplir tous les champs.";
    } else {
        $java_dir = realpath(__DIR__ . "/../java");
        $cmd = "java -cp \"$java_dir;$java_dir/mysql-connector.jar\" -Dfile.encoding=UTF-8 MainTache "
             . escapeshellarg($titre) . " "
             . escapeshellarg($description) . " "
             . escapeshellarg($date_limite) . " "
             . intval($id_groupe) . " "
             . intval($id_encad);

        $output = trim(shell_exec($cmd));

        if (is_numeric($output) && intval($output) > 0) {
            header("Location: taches_enseignant.php?succes=1");
            exit;
        } else {
            $erreur = "Erreur lors de la création de la tâche. (code: $output)";
        }
    }
}

// Récupérer les groupes pour le select
$groupes = $conn->query("SELECT Id_Groupe, code_groupe FROM groupe ORDER BY Id_Groupe")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une tâche — PFS</title>
    <link rel="stylesheet" href="dashboard_ensaignant.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .form_card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 32px 36px;
            max-width: 620px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.06);
        }
        .form_card h2 {
            font-size: 17px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 24px;
        }
        .form_group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 18px;
        }
        .form_group label {
            font-size: 12px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .form_group input,
        .form_group textarea,
        .form_group select {
            padding: 10px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            color: #111827;
            background: #f9fafb;
            outline: none;
            font-family: 'raleway', sans-serif;
            transition: border-color 0.2s;
        }
        .form_group input:focus,
        .form_group textarea:focus,
        .form_group select:focus {
            border-color: #2563eb;
            background: #ffffff;
        }
        .form_group textarea {
            resize: vertical;
            min-height: 100px;
        }
        .btn_submit {
            background: #2563eb;
            color: #ffffff;
            padding: 11px 28px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }
        .btn_submit:hover { background: #1d4ed8; }
        .btn_annuler {
            background: #f1f5f9;
            color: #6b7280;
            padding: 11px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-left: 10px;
            transition: background 0.2s;
        }
        .btn_annuler:hover { background: #e5e7eb; }
        .alert_erreur {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .form_actions {
            display: flex;
            align-items: center;
            margin-top: 8px;
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
            <h1 class="page_title_enseignant">Créer une nouvelle tâche</h1>
        </section>

        <div class="form_card">
            <h2><i class="fa-solid fa-plus" style="color:#2563eb;margin-right:8px;"></i>Nouvelle tâche</h2>

            <?php if ($erreur): ?>
                <div class="alert_erreur">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?php echo htmlspecialchars($erreur); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="creer_tache.php">

                <div class="form_group">
                    <label>Titre de la tâche</label>
                    <input type="text" name="titre" placeholder="Ex: Rapport d'avancement" required
                           value="<?php echo htmlspecialchars($_POST['titre'] ?? ''); ?>">
                </div>

                <div class="form_group">
                    <label>Description</label>
                    <textarea name="description" placeholder="Décrivez la tâche en détail..." required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                </div>

                <div class="form_group">
                    <label>Date limite</label>
                    <input type="date" name="date_limite" required
                           value="<?php echo htmlspecialchars($_POST['date_limite'] ?? ''); ?>">
                </div>

                <div class="form_group">
                    <label>Groupe concerné</label>
                    <select name="id_groupe" required>
                        <option value="">-- Sélectionner un groupe --</option>
                        <?php foreach ($groupes as $g): ?>
                            <option value="<?php echo $g['Id_Groupe']; ?>"
                                <?php echo (isset($_POST['id_groupe']) && $_POST['id_groupe'] == $g['Id_Groupe']) ? 'selected' : ''; ?>>
                                Groupe <?php echo $g['Id_Groupe']; ?> — <?php echo htmlspecialchars($g['code_groupe']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form_actions">
                    <button type="submit" class="btn_submit">
                        <i class="fa-solid fa-paper-plane"></i>
                        Créer la tâche via Java
                    </button>
                    <a href="taches_enseignant.php" class="btn_annuler">
                        <i class="fa-solid fa-xmark"></i>
                        Annuler
                    </a>
                </div>

            </form>
        </div>
    </main>
</div>
</body>
</html>