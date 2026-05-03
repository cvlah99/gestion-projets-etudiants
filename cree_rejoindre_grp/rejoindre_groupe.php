<?php
session_start();
require "../Authentification/conexion_db.php";

// check if the student is logged in
if (!isset($_SESSION['id_etudiant'])) {
    header("Location: ../connection_etudiant/connection_etudiant.html");
    exit;
}

// check that the form was submitted
if (!isset($_POST['code_groupe'])) {
    header("Location: rejoindre_groupe.html");
    exit;
}

$id_etudiant = $_SESSION['id_etudiant'];
$code = strtoupper(trim($_POST['code_groupe'])); // stroupper converti tous les lettre en majuscule 

// check if the student already has a group
$sql = "SELECT Id_Groupe FROM etudiant WHERE Id_Etudiant = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([":id" => $id_etudiant]);
$etudiant = $stmt->fetch();

if ($etudiant['Id_Groupe'] != null) {
    echo "Vous avez déjà un groupe.";
    exit;
}

// search for the group with this code
$sql2 = "SELECT Id_Groupe FROM groupe WHERE code_groupe = :code";
$stmt2 = $conn->prepare($sql2);
$stmt2->execute([":code" => $code]);
$groupe = $stmt2->fetch();

// if no group found
if (!$groupe) {
    echo "Code invalide. Aucun groupe trouvé.";
    exit;
}

$id_groupe = $groupe['Id_Groupe'];

// link the student to this group
$sql3 = "UPDATE etudiant SET Id_Groupe = :id_groupe WHERE Id_Etudiant = :id_etudiant";
$stmt3 = $conn->prepare($sql3);
$stmt3->execute([":id_groupe" => $id_groupe, ":id_etudiant" => $id_etudiant]);

// increment the member count
$sql4 = "UPDATE groupe SET membre_grp = membre_grp + 1 WHERE Id_Groupe = :id_groupe";
$stmt4 = $conn->prepare($sql4);
$stmt4->execute([":id_groupe" => $id_groupe]);

// send the student to the dashboard
header("Location: dashboard.php");
exit;
?>
