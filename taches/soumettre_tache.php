<?php
// ============================================================
// soumettre_tache.php
// Called when a student uploads a file for their task.
//
// Works just like formulaire.php (PDF upload for project),
// but calls Java to record the submission.
// ============================================================

require "../auth.verif.php";

if (empty($_SESSION['id_groupe'])) {
    header("Location: /PFS/cree_rejoindre_grp/cree_rejoindre.html");
    exit;
}

if (isset($_POST['id_tache']) && isset($_FILES['fichier_tache'])) {

    $idTache  = (int) $_POST['id_tache'];
    $idGroupe = (int) $_SESSION['id_groupe'];

    // --- Handle the uploaded file (same logic as formulaire.php) ---
    $fichier     = $_FILES['fichier_tache'];
    $nomFichier  = $fichier['name'];
    $fichierTmp  = $fichier['tmp_name'];
    $erreur      = $fichier['error'];
    $extension   = strtolower(pathinfo($nomFichier, PATHINFO_EXTENSION));

    // Check for upload errors
    if ($erreur !== 0) {
        echo "Erreur lors de l'upload du fichier.";
        exit();
    }

    // Only allow PDF files
    if ($extension !== 'pdf') {
        echo "Le fichier doit être un PDF.";
        exit();
    }

    // Create a unique file name — same pattern as formulaire.php
    $nomUnique = "tache_" . $idTache . "_groupe_" . $idGroupe . ".pdf";
    $dossierUpload = "../uploads/taches/";

    // Create the folder if it doesn't exist yet
    if (!is_dir($dossierUpload)) {
        mkdir($dossierUpload, 0755, true);
    }

    $cheminFinal = $dossierUpload . $nomUnique;

    // Move the uploaded file to the destination folder
    if (!move_uploaded_file($fichierTmp, $cheminFinal)) {
        echo "Impossible de sauvegarder le fichier.";
        exit();
    }

    // --- Call the Java program to record the submission in the database ---
    $javaDir  = __DIR__ . "/../java";
    $cp       = $javaDir . PATH_SEPARATOR . $javaDir . "/mysql-connector.jar";

    $commande = "java -cp " . escapeshellarg($cp) . " MainSoumission "
              . $idTache . " "
              . $idGroupe . " "
              . escapeshellarg($nomUnique);

    $output = trim(shell_exec($commande));

    if ($output === "OK") {
        // Redirect to task list on success
        header("Location: /PFS/taches/tache_etudiant.php?soumis=1");
        exit();
    } else {
        echo "Erreur lors de l'enregistrement de la soumission. (Java: " . htmlspecialchars($output) . ")";
        exit();
    }

} else {
    header("Location: /PFS/taches/tache_etudiant.php");
    exit();
}
?>
