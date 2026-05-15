<?php
require "../auth.verif.php";
require "../Authentification/conexion_db.php";
require "../logger.php";

if (!isset($_POST['code_groupe'])) { //on verie qu il vient du formulaire
    header("Location: rejoindre_groupe.html");
    exit;
}
if (empty(trim($_POST['code_groupe']))) { 
    header("Location: rejoindre_groupe.html");
    exit;
}

$id_etudiant = $_SESSION['id_etudiant'];
$code = strtoupper(trim($_POST['code_groupe'])); // on transforme le code du groupe en majuscul 

// check if the student already has a group
$sql = "SELECT Id_Groupe FROM etudiant WHERE Id_Etudiant = :id"; //on cherche id groupe de l etudiant
$stmt = $conn->prepare($sql);
$stmt->execute([":id" => $id_etudiant]);
$etudiant = $stmt->fetch();

if ($etudiant['Id_Groupe'] != null) { //si id groupe n est pas null donc l etudiant a deja un groupe
    echo "Vous avez déjà un groupe.";
    exit;
}


$sql2 = "SELECT Id_Groupe FROM groupe WHERE code_groupe = :code"; //on cherche l id groupe qui ont ce code
$stmt2 = $conn->prepare($sql2);
$stmt2->execute([":code" => $code]);
$groupe = $stmt2->fetch();

// if no group found
if (!$groupe) {
    echo "Code invalide. Aucun groupe trouvé."; 
    exit;
}

$id_groupe = $groupe['Id_Groupe'];


$_SESSION['id_groupe'] = $id_groupe;


// on modie l id du groupe de l etudiant qui vient de rejoidre le groupe
$sql3 = "UPDATE etudiant SET Id_Groupe = :id_groupe WHERE Id_Etudiant = :id_etudiant";
$stmt3 = $conn->prepare($sql3);
$stmt3->execute([":id_groupe" => $id_groupe, ":id_etudiant" => $id_etudiant]);

// on augmante le nembre de membre du groupe
$sql4 = "UPDATE groupe SET membre_grp = membre_grp + 1 WHERE Id_Groupe = :id_groupe";
$stmt4 = $conn->prepare($sql4);
$stmt4->execute([":id_groupe" => $id_groupe]);


logAction("Groupe rejoint ID=" . $id_groupe, $id_etudiant);
header("Location: /PFS/dashboard_etudiant/dashboard.php");
exit;

?>
