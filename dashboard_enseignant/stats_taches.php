<?php
require "../auth.verif.encadrant.php";
require "../Authentification/conexion_db.php";

// Récupérer tous les groupes
$groupes = $conn->query("SELECT Id_Groupe, code_groupe FROM groupe ORDER BY Id_Groupe")->fetchAll();

$stats = [];
$java_dir = realpath(__DIR__ . "/../java");

foreach ($groupes as $g) {
    $cmd = "java -cp \"$java_dir;$java_dir/mysql-connector.jar\" MainStats " . intval($g['Id_Groupe']);
    $output = trim(shell_exec($cmd));
    $parts = explode(",", $output);

    if (count($parts) === 5) {
        $stats[$g['Id_Groupe']] = [
            'code'       => $g['code_groupe'],
            'total'      => intval($parts[0]),
            'en_attente' => intval($parts[1]),
            'soumis'     => intval($parts[2]),
            'accepte'    => intval($parts[3]),
            'refuse'     => intval($parts[4]),
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques Tâches — PFS</title>
    <link rel="stylesheet" href="dashboard_ensaignant.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .stats_grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .stats_card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 22px 24px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.05);
        }
        .stats_card_header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }
        .stats_card_title {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
        }
        .stats_rows {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .stat_row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 13px;
        }
        .stat_row_total    { background: #f1f5f9; color: #374151; }
        .stat_row_attente  { background: #fef3c7; color: #d97706; }
        .stat_row_soumis   { background: #dbeafe; color: #2563eb; }
        .stat_row_accepte  { background: #dcfce7; color: #16a34a; }
        .stat_row_refuse   { background: #fee2e2; color: #dc2626; }

        .stat_label { font-weight: 600; }
        .stat_value {
            font-size: 18px;
            font-weight: 800;
        }

        .progress_bar_wrap {
            margin-top: 14px;
            height: 8px;
            background: #e5e7eb;
            border-radius: 20px;
            overflow: hidden;
            display: flex;
        }
        .progress_seg { height: 100%; transition: width 0.3s; }
        .seg_accepte { background: #16a34a; }
        .seg_soumis  { background: #2563eb; }
        .seg_refuse  { background: #dc2626; }
        .seg_attente { background: #d97706; }

        .empty_stats {
            text-align: center;
            color: #9ca3af;
            padding: 40px;
            font-size: 14px;
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
                <a href="taches_enseignant.php" class="nav_link">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Tâches</span>
                </a>
                <a href="stats_taches.php" class="nav_link active_link">
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
            <p class="intro_text_enseignant">STATISTIQUES — JAVA MAINSTATS</p>
            <h1 class="page_title_enseignant">Statistiques des tâches par groupe</h1>
        </section>

        <?php if (empty($stats)): ?>
            <div class="empty_stats">Aucun groupe trouvé.</div>
        <?php else: ?>
            <div class="stats_grid">
                <?php foreach ($stats as $id_grp => $s):
                    $total = max($s['total'], 1);
                    $pct_accepte = round(($s['accepte'] / $total) * 100);
                    $pct_soumis  = round(($s['soumis']  / $total) * 100);
                    $pct_refuse  = round(($s['refuse']  / $total) * 100);
                    $pct_attente = 100 - $pct_accepte - $pct_soumis - $pct_refuse;
                    $pct_attente = max($pct_attente, 0);
                ?>
                    <div class="stats_card">
                        <div class="stats_card_header">
                            <span class="stats_card_title">
                                <i class="fa-solid fa-users" style="color:#2563eb;margin-right:6px;"></i>
                                Groupe <?php echo $id_grp; ?>
                            </span>
                            <span class="code_badge"><?php echo htmlspecialchars($s['code']); ?></span>
                        </div>

                        <div class="stats_rows">
                            <div class="stat_row stat_row_total">
                                <span class="stat_label">Total tâches</span>
                                <span class="stat_value"><?php echo $s['total']; ?></span>
                            </div>
                            <div class="stat_row stat_row_attente">
                                <span class="stat_label">En attente</span>
                                <span class="stat_value"><?php echo $s['en_attente']; ?></span>
                            </div>
                            <div class="stat_row stat_row_soumis">
                                <span class="stat_label">Soumises</span>
                                <span class="stat_value"><?php echo $s['soumis']; ?></span>
                            </div>
                            <div class="stat_row stat_row_accepte">
                                <span class="stat_label">Acceptées</span>
                                <span class="stat_value"><?php echo $s['accepte']; ?></span>
                            </div>
                            <div class="stat_row stat_row_refuse">
                                <span class="stat_label">Refusées</span>
                                <span class="stat_value"><?php echo $s['refuse']; ?></span>
                            </div>
                        </div>

                        <!-- Barre de progression -->
                        <div class="progress_bar_wrap">
                            <div class="progress_seg seg_accepte" style="width:<?php echo $pct_accepte; ?>%"></div>
                            <div class="progress_seg seg_soumis"  style="width:<?php echo $pct_soumis; ?>%"></div>
                            <div class="progress_seg seg_refuse"  style="width:<?php echo $pct_refuse; ?>%"></div>
                            <div class="progress_seg seg_attente" style="width:<?php echo $pct_attente; ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>
</div>
</body>
</html>