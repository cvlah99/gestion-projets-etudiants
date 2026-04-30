<?php
require "conexion_db.php";
if (
    isset($_POST["nom"]) &&
    isset($_POST["prenom"]) &&
    isset($_POST["email"]) &&
    isset($_POST["groupe_classe"]) &&
    isset($_POST["mot_de_passe"])
) {

    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $email = trim($_POST["email"]);
    $groupe_classe = trim($_POST["groupe_classe"]);
    $mot_de_passe = $_POST["mot_de_passe"];
    $provenance = isset($_POST["provenance"]) ? trim($_POST["provenance"]) : "";

    if (
        !empty($nom) &&
        !empty($prenom) &&
        !empty($email) &&
        !empty($groupe_classe) &&
        !empty($mot_de_passe)
    ) {

        if (!preg_match("/[A-Za-zÀ-ÿ\s]{3,}/", $nom)) {
            echo "Nom invalide.";
            exit;
        }

        if (!preg_match("/[A-Za-zÀ-ÿ\s]{3,}/", $prenom)) {
            echo "Prénom invalide.";
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Email invalide.";
            exit;
        }

        if (!preg_match("/[A-Za-z0-9]{1,10}/", $groupe_classe)) {
            echo "Groupe de classe invalide.";
            exit;
        }

        if (!preg_match("/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}/", $mot_de_passe)) {
            echo "Mot de passe non conforme.";
            exit;
        }

        if (!empty($provenance)) {
            if (!preg_match("/[A-Za-zÀ-ÿ\s]{2,}/", $provenance)) {
                echo "Provenance invalide.";
                exit;
            }
        }

        
        $query = "
         INSERT INTO etudiant
        (nom_Etudiant, prenom_Etudiant, Group_cls, email, mot_de_passe, provenance, Id_Groupe)
        VALUES (
        :nom,
        :prenom,
        :groupe_classe,
        :email,
        :mot_de_passe,
        :provenance,
        NULL
    )
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute(array(":nom"=>$nom,":prenom"=>$prenom,":groupe_classe"=>$groupe_classe,":email"=>$email,":mot_de_passe"=>$mot_de_passe,":provenance"=>$provenance));
    echo "Insertion réussie";
    
    
    



    } else {
        echo "Veuillez remplir tous les champs obligatoires.";
        exit;
    }

} else {
    header("Location: Authentification.html");
    exit;
}
?>
