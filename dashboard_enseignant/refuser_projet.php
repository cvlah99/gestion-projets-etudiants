<?php
require "../auth.verif.encadrant.php";
require "../Authentification/conexion_db.php";
require "../logger.php";

if (isset($_POST['id_formulaire']) && is_numeric($_POST['id_formulaire'])) {
    $id_formulaire = (int) $_POST['id_formulaire'];

    $sql = "UPDATE formulaire SET statut = 'refuse' WHERE Id_Formulaire = :id_formulaire";
    $stmt = $conn->prepare($sql);
    $stmt->execute([":id_formulaire" => $id_formulaire]);
}
logAction("Projet refuse formulaire ID=" . $id_formulaire, $_SESSION['id_encadrant']);
header("Location: dashboard_enseignant.php");
exit;
?>