<?php
session_start();
require("../Authentification/conexion_db.php");
require("authverf.php");
if (!isset($_GET['id_groupe'])) {
    header("Location: dashboard_enseignant.php");
    exit;
}

$id_groupe = (int) $_GET['id_groupe'];

$sql = "SELECT fichier FROM groupe WHERE Id_Groupe = :id_groupe LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute([':id_groupe' => $id_groupe]);
$row = $stmt->fetch();

if (!$row || empty($row['fichier'])) {
    echo "Aucun fichier soumis pour ce groupe.";
    exit;
}

$chemin = $row['fichier']; // e.g. "uploads/groupe_1_rapport.pdf"

if (!file_exists($chemin)) {
    echo "Fichier introuvable sur le serveur.";
    exit;
}

$ext      = strtolower(pathinfo($chemin, PATHINFO_EXTENSION));
$filename = basename($chemin);

$mime_types = [
    'pdf'  => 'application/pdf',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'doc'  => 'application/msword',
];

$mime = $mime_types[$ext] ?? 'application/octet-stream';

// Stream the file directly to the browser (inline for PDF, download for Word)
header('Content-Type: ' . $mime);
header('Content-Disposition: ' . ($ext === 'pdf' ? 'inline' : 'attachment') . '; filename="' . $filename . '"');
header('Content-Length: ' . filesize($chemin));
readfile($chemin);
exit;
?>
