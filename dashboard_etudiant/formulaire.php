<?php

require "../auth.verif.php";

if (empty($_SESSION['id_groupe'])) {
    header("Location: /PFS/cree_rejoindre_grp/cree_rejoindre.html");
    exit();
}

require "../Authentification/conexion_db.php";


if (isset($_POST["titre"]) && isset($_FILES["rapport"])) {

    $titre     = trim($_POST["titre"]);
    $id_groupe = $_SESSION['id_groupe'];

    if (empty($titre)) {
        echo "Veuillez remplir le titre.";
        exit();
    }

    
    $fichier     = $_FILES["rapport"]; // $FILE c'est un tableau  que php le remplit automatiquement il remplit trois information ,  nom du fichier , ou il  va place le fichier  ,  est les erreur 
    $nom_fichier = $fichier["name"]; 
    $fichier_tmp = $fichier["tmp_name"];
    $erreur      = $fichier["error"];
    $extension   = strtolower(pathinfo($nom_fichier, PATHINFO_EXTENSION)); // ca c'est  pour extraire l'extention de fichier .pdf

    if ($erreur !== 0) {
        echo "Erreur lors de l'upload du fichier.";
        exit();
    }

    if ($extension !== "pdf") {
        echo "Le fichier doit être un PDF.";
        exit();
    }

    $nom_unique = "rapport_groupe_" . $_SESSION['id_groupe'] . ".pdf"; // change le  nom de fichier du nom de groupe  a travert id_groupe danc on aura rapport_groupe_1.pdf
    $dossier_upload = "../uploads/"; // chemin ou il va stocker le fichier
    $chemin_final   = $dossier_upload . $nom_unique; // exemple  ../upload/nom_de fichier 

    if (!move_uploaded_file($fichier_tmp, $chemin_final)) {
        echo "Impossible de sauvegarder le fichier.";
        exit();
    }

    $sql = "INSERT INTO formulaire (titre, rapport, Id_Groupe, Date_creationF, Date_limiteF, statut)
            VALUES (:titre, :rapport, :id_groupe, NOW(), NULL, 'en attente')";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ":titre"     => $titre,
        ":rapport"   => $nom_unique,
        ":id_groupe" => $id_groupe
    ]);

    header("Location: /PFS/dashboard_etudiant/dashboard.php");
    exit();

} else {
    header("Location: /PFS/dashboard_etudiant/formulaire.html");
    exit();
}
?>
