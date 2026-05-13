<?php
require "conexion_db.php"; //conexion a la base de donne
if (
    isset($_POST["nom"]) && // verifier que  l utulisateur vient du formulaire et pas par l url directement
    isset($_POST["prenom"]) &&
    isset($_POST["email"]) &&
    isset($_POST["groupe_classe"]) &&
    isset($_POST["mot_de_passe"])
) {

    $nom = trim($_POST["nom"]); //recuperation des informations saisie dans le formulaire .trim pour eleminer les espace au debut et a la fin
    $prenom = trim($_POST["prenom"]); //strtoupper pour transferez le texte en majuscule
    $email = trim($_POST["email"]);
    $groupe_classe = strtoupper(trim($_POST["groupe_classe"])) ;
    $mot_de_passe = $_POST["mot_de_passe"];
    $provenance = isset($_POST["provenance"]) ? trim($_POST["provenance"]) : "";
    //provenance n est pas obligatoir dans le formulaire donc on fait si elle est saisie en la recupere sinon on met une chaine vide

    if (
        !empty($nom) && //verie que les champs ne sont pas laissez vides
        !empty($prenom) &&
        !empty($email) &&
        !empty($groupe_classe) &&
        !empty($mot_de_passe)
    ) {

        if (!preg_match("/[A-Za-zÀ-ÿ\s]{3,}/", $nom)) { //double verification tous les verifications au niveau front doit etre traite dans le back
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

        //preparer la requete select :nom .... sont des parametres nomee qu ils vas etres emplaces par des vrais valeur
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
    header("Location: ../connection_etudiant/connection_etudiant.html");
    exit;
    
    
    



    } else {
        echo "Veuillez remplir tous les champs obligatoires.";
        exit;
    }

} else {
    header("Location: Authentification.html");
    exit;
}
?>
