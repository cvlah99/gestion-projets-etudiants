<?php
// 1. Check the student is logged in
session_start();
if (!isset($_SESSION['id_etudiant'])) {
    header("Location: ../connection_etudiant/connection_etudiant.html");
    exit;
}

// 2. Connect to the database
require "../Authentification/conexion_db.php";

// 3. Check if the student already has a group
$id_etudiant = $_SESSION['id_etudiant'];

$sql = "SELECT Id_Groupe FROM etudiant WHERE Id_Etudiant = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([":id" => $id_etudiant]);
$etudiant = $stmt->fetch();

if ($etudiant['Id_Groupe'] != null) {
    echo "Vous avez déjà un groupe.";
    exit;
}

// 4. Call the C++ program
// C++ will generate a code, insert it into the groupe table,
// and print the new Id_Groupe so PHP can read it
$exe    = "C:\\xampp\\htdocs\\PFS\\CPP\\app.exe";
$output = shell_exec($exe);

// 5. Clean the output — we only want the number
$id_groupe = trim($output);

// 6. Check C++ returned a valid number
if (!is_numeric($id_groupe)) {
    echo "Erreur lors de la création du groupe. Veuillez réessayer.";
    exit;
}

// 7. Link the student to the new group in the etudiant table
$sql2 = "UPDATE etudiant SET Id_Groupe = :id_groupe WHERE Id_Etudiant = :id_etudiant";
$stmt2 = $conn->prepare($sql2);
$stmt2->execute([
    ":id_groupe"   => $id_groupe,
    ":id_etudiant" => $id_etudiant
]);

// 8. Get the group code to show it to the student
$sql3 = "SELECT code_groupe FROM groupe WHERE Id_Groupe = :id_groupe";
$stmt3 = $conn->prepare($sql3);
$stmt3->execute([":id_groupe" => $id_groupe]);
$groupe = $stmt3->fetch();

// 9. Save in session so afficher_groupe.php can display it
$_SESSION['code_groupe'] = $groupe['code_groupe'];
$_SESSION['id_groupe']   = $id_groupe;

// 10. Redirect to the page that shows the code
header("Location: afficher_groupe.php");
exit;
?>
