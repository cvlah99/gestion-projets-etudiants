<?php
// ============================================================
// refuser_tache.php
// Called when the encadrant clicks "Refuser" on a task.
//
// Mirrors refuser_projet.php logic, calls Java instead.
// ============================================================

require "../auth.verif.encadrant.php";

if (isset($_POST['id_tache']) && is_numeric($_POST['id_tache'])) {

    $idTache = (int) $_POST['id_tache'];

    // Call the Java program to update the status to 'refuse'
    $javaDir  = __DIR__ . "/../java";
    $cp       = $javaDir . PATH_SEPARATOR . $javaDir . "/mysql-connector.jar";
    $commande = "java -cp " . escapeshellarg($cp) . " MainRefuserTache " . $idTache;

    $output = trim(shell_exec($commande));
}

// Redirect back to task list
header("Location: liste_taches_encadrant.php");
exit;
?>
