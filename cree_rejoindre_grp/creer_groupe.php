<?php
require "../auth.verif.php";


require "../Authentification/conexion_db.php";
require "../logger.php";

//on recupere l id etudiant de la session
$id_etudiant = $_SESSION['id_etudiant'];
//on cherche id gorupe correcpond a l id etudiant
$sql = "SELECT Id_Groupe FROM etudiant WHERE Id_Etudiant = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([":id" => $id_etudiant]);
$etudiant = $stmt->fetch();

if ($etudiant && $etudiant['Id_Groupe'] !== null) { //si on trouve l id groupe non null c.a.d que l etudiant a deja un groupe
    echo "Vous avez déjà un groupe.";
    exit;
}

//sinon on echecute le code c++ qui va generer un code
$exe = __DIR__ . "/../cpp/app.exe"; // adapte le nom si nécessaire
// Execute C++ and capture output
$output = shell_exec('"' . $exe . '"'); //declanche la generation du code

// 5. Clean output
$id_groupe = trim($output);  // on nettoie l id groupe q u on recoit par c++


if (!is_numeric($id_groupe)) { //on verifie que c++ rnevoie bien un numeric
    echo "Erreur lors de la création du groupe. Sortie C++ : " ;
    exit;
}

// 7. Link student to group
$sql2 = "UPDATE etudiant SET Id_Groupe = :id_groupe WHERE Id_Etudiant = :id_etudiant"; //on mettre  a jour l id groupe genere a l etudiant car c etait null par default
$stmt2 = $conn->prepare($sql2);
$stmt2->execute([
    ":id_groupe"   => $id_groupe,
    ":id_etudiant" => $id_etudiant
]);


$sql3 = "SELECT code_groupe FROM groupe WHERE Id_Groupe = :id_groupe"; //on recupere le code du groupe 
$stmt3 = $conn->prepare($sql3);
$stmt3->execute([":id_groupe" => $id_groupe]);
$groupe = $stmt3->fetch();

// 9. Save in session
$_SESSION['code_groupe'] = $groupe['code_groupe']; //on rappell de l id groupe et code du groupe
$_SESSION['id_groupe']   = $id_groupe;

// 10. Redirect
logAction("Groupe cree ID=" . $id_groupe, $id_etudiant);
header("Location: /PFS/cree_rejoindre_grp/afficher_groupe.php");
exit;
?>