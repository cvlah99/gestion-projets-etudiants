<?php
require "../auth.verif.php";
require "../Authentification/conexion_db.php";

if (empty($_SESSION['id_groupe'])) {
    header("Location: /PFS/cree_rejoindre_grp/cree_rejoindre.html");
    exit;
}

$id_groupe = $_SESSION['id_groupe'];
$erreur = "";
$succes = "";

// Traitement soumission fichier PDF via Java
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_tache'])) {
    $id_tache = intval($_POST['id_tache']);

    if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION));

        if ($ext !== 'pdf') {
            $erreur = "Seuls les fichiers PDF sont acceptés.";
        } else {
            $nom_fichier = "tache_" . $id_tache . "_" . time() . ".pdf";
            $dest = __DIR__ . "/../uploads/" . $nom_fichier;

            if (move_uploaded_file($_FILES['fichier']['tmp_name'], $dest)) {
                $java_dir = realpath(__DIR__ . "/../java");
                $cmd = "java -cp \"$java_dir;$java_dir/mysql-connector.jar\" MainSoumission "
                     . intval($id_tache) . " "
                     . escapeshellarg($nom_fichier);
                $output = trim(shell_exec($cmd));

                if ($output === "1") {
                    $succes = "Fichier soumis avec succès !";
                } else {
                    $erreur = "Erreur lors de la soumission via Java.";
                }
            } else {
                $erreur = "Erreur lors de l'upload du fichier.";
            }
        }
    } else {
        $erreur = "Veuillez sélectionner un fichier PDF.";
    }
}

// Récupérer les tâches du groupe
$sql = "SELECT Id_Tache, titre, DescriptionT, Date_limiteT, statut, fichier
        FROM tache WHERE Id_Groupe = :id_groupe ORDER BY Id_Tache DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([':id_groupe' => $id_groupe]);
$taches = $stmt->fetchAll();

// Code groupe
$stmt2 = $conn->prepare("SELECT code_groupe FROM groupe WHERE Id_Groupe = :id");
$stmt2->execute([':id' => $id_groupe]);
$groupe = $stmt2->fetch();
$code_groupe = $groupe ? $groupe['code_groupe'] : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Tâches — PFS</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .taches_container {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .tache_card {
            background: #ffffff;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px 24px;
        }
        .tache_card:hover {
            border-color: #3b82f6;
        }
        .tache_header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }
        .tache_titre {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }
        .tache_desc {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 12px;
            line-height: 1.6;
        }
        .tache_meta {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }
        .tache_meta span {
            font-size: 12px;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .badge_statut {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }
        .badge_accepte { background: #dcfce7; color: #16a34a; }
        .badge_refuse  { background: #fee2e2; color: #dc2626; }
        .badge_attente { background: #fef3c7; color: #d97706; }
        .badge_soumis  { background: #dbeafe; color: #2563eb; }

        .soumettre_form {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            background: #f1f5f9;
            border-radius: 8px;
            padding: 12px 16px;
            margin-top: 8px;
        }
        .soumettre_form label {
            font-size: 12px;
            font-weight: 700;
            color: #374151;
        }
        .soumettre_form input[type="file"] {
            font-size: 13px;
        }
        .btn_soumettre {
            background: #2563eb;
            color: #ffffff;
            padding: 8px 18px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
        }
        .btn_soumettre:hover { background: #1d4ed8; }
        .pdf_link {
            color: #dc2626;
            font-size: 18px;
            text-decoration: none;
        }
        .alert_succes {
            background: #dcfce7;
            color: #16a34a;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 13px;
            margin-bottom: 16px;
        }
        .alert_erreur {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 13px;
            margin-bottom: 16px;
        }
        .empty_msg {
            text-align: center;
            color: #9ca3af;
            font-size: 14px;
            padding: 40px 0;
        }
    </style>
</head>
<body>

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
        <a class="nav-link active" href="/PFS/dashboard_etudiant/tache.php">Tâches</a>
        <a class="nav-link" href="/PFS/pfs/soutenance.php">Soutenance</a>
    </nav>

    <div class="sidebar-bottom">
        <form action="/PFS/logout.php" method="post">
            <button class="logout-btn" type="submit">Déconnexion</button>
        </form>
    </div>
</div>

<div class="main">

    <div class="top-bar">
        <div>
            <div class="group-code">
                CODE DU GROUPE :
                <span class="code-badge"><?php echo htmlspecialchars($code_groupe); ?></span>
            </div>
            <h1>Mes Tâches</h1>
        </div>
    </div>

    <?php if ($succes): ?>
        <div class="alert_succes">✓ <?php echo htmlspecialchars($succes); ?></div>
    <?php endif; ?>
    <?php if ($erreur): ?>
        <div class="alert_erreur">✗ <?php echo htmlspecialchars($erreur); ?></div>
    <?php endif; ?>

    <div class="taches_container">

        <?php if (empty($taches)): ?>
            <div class="empty_msg">
                <p>Aucune tâche assignée à votre groupe pour l'instant.</p>
            </div>
        <?php endif; ?>

        <?php foreach ($taches as $t): ?>
            <div class="tache_card">
                <div class="tache_header">
                    <span class="tache_titre"><?php echo htmlspecialchars($t['titre']); ?></span>
                    <?php if ($t['statut'] === 'accepte'): ?>
                        <span class="badge_statut badge_accepte">✓ Accepté</span>
                    <?php elseif ($t['statut'] === 'refuse'): ?>
                        <span class="badge_statut badge_refuse">✗ Refusé</span>
                    <?php elseif ($t['statut'] === 'soumis'): ?>
                        <span class="badge_statut badge_soumis">⏳ Soumis</span>
                    <?php else: ?>
                        <span class="badge_statut badge_attente">En attente</span>
                    <?php endif; ?>
                </div>

                <p class="tache_desc"><?php echo htmlspecialchars($t['DescriptionT']); ?></p>

                <div class="tache_meta">
                    <span>📅 Date limite : <strong><?php echo htmlspecialchars($t['Date_limiteT']); ?></strong></span>
                    <?php if (!empty($t['fichier'])): ?>
                        <span>
                            📎 Fichier soumis :
                            <a href="/PFS/uploads/<?php echo htmlspecialchars($t['fichier']); ?>"
                               target="_blank" class="pdf_link">
                                <i class="fa-solid fa-file-pdf"></i> Voir PDF
                            </a>
                        </span>
                    <?php endif; ?>
                </div>

                <?php if ($t['statut'] === 'en attente' || $t['statut'] === 'refuse'): ?>
                    <form method="POST" enctype="multipart/form-data" class="soumettre_form">
                        <input type="hidden" name="id_tache" value="<?php echo $t['Id_Tache']; ?>">
                        <label>Soumettre un fichier PDF :</label>
                        <input type="file" name="fichier" accept=".pdf" required>
                        <button type="submit" class="btn_soumettre">
                            <i class="fa-solid fa-upload"></i>
                            Soumettre
                        </button>
                    </form>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>

    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</body>
</html>