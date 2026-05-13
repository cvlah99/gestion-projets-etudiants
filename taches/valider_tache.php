<?php
// ============================================================
// valider_tache.php
// Called when the encadrant clicks "Accepter" on a task.
//
// Mirrors the logic of valider_projet.php,
// but calls Java instead of updating PHP directly.
// ============================================================

require "../auth.verif.encadrant.php";

if (isset($_POST['id_tache']) && is_numeric($_POST['id_tache'])) {

    $idTache = (int) $_POST['id_tache'];

    // Call the Java program to update the status
    $javaDir  = __DIR__ . "/../java";
    $cp       = $javaDir . PATH_SEPARATOR . $javaDir . "/mysql-connector.jar";
    $commande = "java -cp " . escapeshellarg($cp) . " MainValiderTache " . $idTache;

    $output = trim(shell_exec($commande));

    // If something went wrong, we still redirect (same as valider_projet.php behavior)
    // You could add error handling here if needed
}

// Redirect back to task list
header("Location: liste_taches_encadrant.php");
exit;
?>
