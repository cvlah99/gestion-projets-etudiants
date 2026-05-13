<?php

session_start();
 
require "../Authentification/conexion_db.php";
 
if (isset($_POST["login"]) && isset($_POST["mot_de_passe"])) {
 
    $login       = trim($_POST["login"]);
    $mot_de_passe = trim($_POST["mot_de_passe"]);
 
    if (empty($login) || empty($mot_de_passe)) {
        echo "Veuillez remplir tous les champs.";
        exit();
    }
    $sql = "SELECT Id_admin, nom_admin, mdp_admin FROM admin WHERE nom_admin = :login";
    $stmt = $conn->prepare($sql);
    $stmt->execute([":login" => $login]);
    $admin = $stmt->fetch(); // admin  stock id  nom  et le  mot de passe  fetch  retourne  tableau $admin  remplie  avec  id , nom , passwod or sinon false
 
    if ($admin && $mot_de_passe === $admin['mdp_admin']) { // admin  si  elle est false on va pas enter
 

        $_SESSION['id_admin']  = $admin['Id_admin'];
        $_SESSION['nom_admin'] = $admin['nom_admin'];

        header("Location: dashboard_admin.php");
        exit();
 
    } else {
        echo "Login ou mot de passe incorrect.";
        exit();
    }
 
} else {
    header("Location: login_admin.html");
    exit();
}
?>