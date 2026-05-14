<?php
// ============================================================
// ajouter_tache.php
// Called when the encadrant submits the "Ajouter une tâche" form.
//
// This works exactly like creer_encadrant.php works for C++:
//   1. Read the form data
//   2. Call the Java program (instead of the C++ .exe)
//   3. Read the output
//   4. Redirect or show result
// ============================================================

require "../auth.verif.encadrant.php"; // must be logged in as encadrant
require "../Authentification/conexion_db.php";

if (
    isset($_POST['titre_tache']) &&
    isset($_POST['description_tache']) &&
    isset($_POST['date_limite']) &&
    isset($_POST['id_groupe'])   // which group this task is for
) {

    // --- Read form data ---
    $titre       = trim($_POST['titre_tache']);
    $description = trim($_POST['description_tache']);
    $dateLimite  = trim($_POST['date_limite']);
    $idGroupe    = (int) $_POST['id_groupe'];
    $idEncadrant = (int) $_SESSION['id_encadrant']; // from session

    // --- Basic validation ---
    if (empty($titre) || empty($description) || empty($dateLimite) || $idGroupe <= 0) {
        echo "Veuillez remplir tous les champs.";
        exit();
    }

    // --- Call the Java program (same pattern as calling encadrant.exe in C++) ---
    // We use 'java' command instead of a .exe file
    $javaDir = __DIR__ . "/../java";
    $cp      = $javaDir . PATH_SEPARATOR . $javaDir . "/mysql-connector.jar";

    // Build the command — escapeshellarg protects against injection
    $commande = "java -cp " . escapeshellarg($cp) . " MainTache "
              . escapeshellarg($titre) . " "
              . escapeshellarg($description) . " "
              . escapeshellarg($dateLimite) . " "
              . $idGroupe . " "
              . $idEncadrant;

    // Execute and capture output
    $output = trim(shell_exec($commande));

    // --- Check result ---
    if (empty($output) || $output === "ERREUR" || !is_numeric($output)) {
        echo "Erreur lors de la création de la tâche. (Java output: " . htmlspecialchars($output) . ")";
        exit();
    }

    // --- Redirect to task list on success --
    header("Location: /PFS/gestion-projets-etudiants/taches/liste_taches_encadrant.php?succes=1");
    exit();

} else {
    // Form was not submitted properly — go back to form
    header("Location: /PFS/enseignant/ajouter_tache_enseignant.html");
    exit();
}
?>
