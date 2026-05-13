<?php
session_start();

require "../Authentification/conexion_db.php";

if (isset($_POST["cle_accee"])) {

    $cle_accee = trim($_POST["cle_accee"]);

    if (empty($cle_accee)) {
        header("Location: login_encadrant.html");
        exit;
    }

    $sql = "SELECT Id_Encad, nom_Encad, prenom_Encad, cle_accee
            FROM encadrant
            WHERE cle_accee = :cle_accee
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->execute([":cle_accee" => $cle_accee]);
    $encadrant = $stmt->fetch();

    if ($encadrant) {
        $_SESSION['id_encadrant']  = $encadrant['Id_Encad'];
        $_SESSION['nom_encadrant'] = $encadrant['nom_Encad'];
        $_SESSION['prenom_encadrant'] = $encadrant['prenom_Encad'];

        header("Location: /PFS/dashboard_enseignant/dashboard_enseignant.php");
        exit;
    } else {
        echo "Clé d'accès incorrecte.";
        exit;
    }

} else {
    header("Location: login_encadrant.html");
    exit;
}
?>