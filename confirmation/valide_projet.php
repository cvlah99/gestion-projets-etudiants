<?php
session_start();
require("../Authentification/conexion_db.php");
require("authverf.php");

if (!isset($_POST['id_groupe'])) {
    header("Location: dashboard_enseignant.php");
    exit;
}

$id_groupe = (int) $_POST['id_groupe'];

$sql = "UPDATE groupe SET statut = 'valide' WHERE Id_Groupe = :id_groupe";
$stmt = $conn->prepare($sql);
$stmt->execute([':id_groupe' => $id_groupe]);

header("Location: dashboard_enseignant.php");
exit;
?>
