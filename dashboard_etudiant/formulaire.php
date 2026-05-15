<?php

require "../auth.verif.php";

if (empty($_SESSION['id_groupe'])) { //verifie que l etudiant a un gorupe
    header("Location: /PFS/cree_rejoindre_grp/cree_rejoindre.html");
    exit();
}

require "../Authentification/conexion_db.php";
require "../logger.php";


if (isset($_POST["titre"]) && isset($_FILES["rapport"])) {  //verifie que l etudiant vient du formulaire

    $titre     = trim($_POST["titre"]);
    $id_groupe = $_SESSION['id_groupe'];

    if (empty($titre)) {
        echo "Veuillez remplir le titre.";
        exit();
    }

    
    $fichier     = $_FILES["rapport"]; // $FILE c'est un tableau  que php le remplit automatiquement il remplit trois information ,  nom du fichier , ou il  va place le fichier  ,  est les erreur 
    $nom_fichier = $fichier["name"]; //pour ne pas repeter 
    $fichier_tmp = $fichier["tmp_name"]; //on le met dans un fichier temporaire
    $erreur      = $fichier["error"]; //php met automatiquement un code d erreur 0 s il y a aucune erreur
    $extension   = strtolower(pathinfo($nom_fichier, PATHINFO_EXTENSION)); //pathinfo($nom_fichier, PATHINFO_EXTENSION) prend seulement l extention pdf
    //strlower le met en minuscule pour eviter .PDF

    if ($erreur !== 0) { //s il y a une erreur
        echo "Erreur lors de l'upload du fichier.";
        exit();
    }

    if ($extension !== "pdf") { //si c est pas un pdf
        echo "Le fichier doit être un PDF.";
        exit();
    }

    $nom_unique = "rapport_groupe_" . $_SESSION['id_groupe'] . ".pdf"; // on change le nom pour savoir le rapport de quel groupe
    $dossier_upload = "../uploads/"; // chemin ou il va stocker le fichier
    $chemin_final   = $dossier_upload . $nom_unique; // exemple  ../upload/nom_de fichier

    if (!move_uploaded_file($fichier_tmp, $chemin_final)) { //deplacer le pdf du fichier temporaire vers dossier upload
        echo "Impossible de sauvegarder le fichier.";
        exit();
    }

    $sql_check = "SELECT Id_Formulaire, statut FROM formulaire WHERE Id_Groupe = :id_groupe";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->execute([":id_groupe" => $id_groupe]);
$ancien = $stmt_check->fetch();

if ($ancien && $ancien['statut'] === 'refuse') {
    $sql = "UPDATE formulaire 
            SET titre = :titre, rapport = :rapport, 
                Date_creationF = NOW(), statut = 'en attente'
            WHERE Id_Groupe = :id_groupe";
} else if (!$ancien) {
    $sql = "INSERT INTO formulaire (titre, rapport, Id_Groupe, Date_creationF, Date_limiteF, statut)
            VALUES (:titre, :rapport, :id_groupe, NOW(), NULL, 'en attente')";
} else {
    echo "Vous avez déjà un formulaire en cours.";
    exit();
}

$stmt = $conn->prepare($sql);
$stmt->execute([
    ":titre"     => $titre,
    ":rapport"   => $nom_unique,
    ":id_groupe" => $id_groupe
]);
logAction("Formulaire soumis groupe=" . $id_groupe, $_SESSION['id_etudiant']);

    header("Location: /PFS/dashboard_etudiant/dashboard.php");
    exit();

} else {
    header("Location: /PFS/dashboard_etudiant/formulaire.html");
    exit();
}
//laspiration
//666 777
?>
