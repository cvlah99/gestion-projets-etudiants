<?php
session_start(); //pour memoriser l utulisateur connecte
require "Authentification/conexion_db.php";

if (isset($_POST["email_etd"], $_POST["password_etd"])) {

    $email_etd = trim($_POST["email_etd"]);
    $password_etd = trim($_POST["password_etd"]);

    if (!empty($email_etd) &&
        !empty($password_etd) &&
        filter_var($email_etd, FILTER_VALIDATE_EMAIL)) {

        $sql = "
            SELECT Id_Etudiant, nom_Etudiant, prenom_Etudiant, email, mot_de_passe
            FROM etudiant
            WHERE email = :email
              AND email != 'system@local'
            LIMIT 1
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ":email" => $email_etd
        ]);

        $user = $stmt->fetch();

        if ($user) {

            
            if ($password_etd === $user['mot_de_passe']) {
                $_SESSION['id_etudiant']=$user['Id_Etudiant'];
                $_SESSION['nom'] = $user['nom_Etudiant'];
                $_SESSION['prenom'] = $user['prenom_Etudiant'];
                $_SESSION['email'] = $user['email'];
                header("location: dashboard.php");
                exit;
                

            } else {
                echo "Mot de passe incorrect";
            }

        } else {
            echo "Email non trouvé";
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