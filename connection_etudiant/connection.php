<?php
session_start(); //pour se rappeller de l etudiant qui se conecte
require "../Authentification/conexion_db.php"; //conexion bd
require "../logger.php"; //journalisation


if (isset($_POST["email_etd"], $_POST["password_etd"])) {

    $email_etd = trim($_POST["email_etd"]);
    $password_etd = trim($_POST["password_etd"]);

    if (!empty($email_etd) &&
        !empty($password_etd) &&
        filter_var($email_etd, FILTER_VALIDATE_EMAIL)) {

        //on prepare la requette on dmeande de donner les informations d l etudiant dont l email correspond a l email saisie par l utulisateur
        //on veut macimum un resultat car l email est unique
        //
        $sql = "
        
        SELECT Id_Etudiant, nom_Etudiant, prenom_Etudiant, email, mot_de_passe, Id_Groupe
        FROM etudiant

        WHERE email = :email
        AND email != 'system@loc'al'
        LIMIT 1
        

            
        ";

        $stmt = $conn->prepare($sql); //preparer la requette 
        $stmt->execute([
            ":email" => $email_etd //remplace le parametre qu on a donne par le vrai email saisie
        ]);

        $user = $stmt->fetch(); //on prend une seule ligne de resultat et on le fait dans un tableau si email non trouve $user est false
        if ($user) { //si lemail est trouve
            
            if ($password_etd === $user['mot_de_passe']) { 
                unset($_SESSION['id_groupe']); //important pour un utulisateur qui est connecte et veut acceder au s  dashboard sans groupe
                $_SESSION['id_etudiant']=$user['Id_Etudiant']; //on recupere lesinformation pour se rappelle de l utulisateur connecte
                $_SESSION['nom'] = $user['nom_Etudiant'];
                $_SESSION['prenom'] = $user['prenom_Etudiant'];
                $_SESSION['email'] = $user['email'];
                
                //se rappeller de  l etudiant qui a deja un groupe
                if (!empty($user['Id_Groupe'])) {
                $_SESSION['id_groupe'] = $user['Id_Groupe'];
}

                if (!empty($_SESSION['id_groupe'])) {
                //si l utulisateur a deja un groupe il vas directement au dashboard
                logAction("Connexion reussie email=" . $email_etd, $user['Id_Etudiant']);
                header("Location: /PFS/dashboard_etudiant/dashboard.php");
                exit;
                } else {
                    //sinon il choisie qu cree ou rejoidre un ggroupe
                    header("Location: /PFS/cree_rejoindre_grp/cree_rejoindre.php");
                    exit;
}

                

            } else {
                echo "Mot de passe incorrect";
                logAction("Echec connexion mot de passe incorrect email=" . $email_etd, null);
            }

        } else {
            echo "Email non trouvé";
            logAction("Echec connexion email introuvable=" . $email_etd, null);
        }

    } else {
        header("Location: connection_etudiant.html");
        exit;
    }

} else {
    header("Location: connection_etudiant.html");
    exit;
}
?>