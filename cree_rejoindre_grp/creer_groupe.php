<?php
// 1. Start session and check student login
session_start();
if (!isset($_SESSION['id_etudiant'])) {
    header("Location: ../connection_etudiant/connection_etudiant.html");
    exit;
}

// 2. Database connection
require "../Authentification/conexion_db.php";

// 3. Check if student already has a group
$id_etudiant = $_SESSION['id_etudiant'];

$sql = "SELECT Id_Groupe FROM etudiant WHERE Id_Etudiant = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([":id" => $id_etudiant]);
$etudiant = $stmt->fetch();

if ($etudiant && $etudiant['Id_Groupe'] !== null) {
    echo "Vous avez déjà un groupe.";
    exit;
}

// 4. Call the C++ executable (dynamic path)
$exe = __DIR__ . "/../cpp/app.exe"; // adapte le nom si nécessaire

if (!file_exists($exe)) {
    echo "Erreur : programme C++ introuvable.";
    exit;
}

// Execute C++ and capture output
$output = shell_exec('"' . $exe . '"');

// 5. Clean output
$id_groupe = trim($output);

// 6. Validate returned ID
if (!is_numeric($id_groupe)) {
    echo "Erreur lors de la création du groupe. Sortie C++ : " . htmlspecialchars($output);
    exit;
}

// 7. Link student to group
$sql2 = "UPDATE etudiant SET Id_Groupe = :id_groupe WHERE Id_Etudiant = :id_etudiant";
$stmt2 = $conn->prepare($sql2);
$stmt2->execute([
    ":id_groupe"   => $id_groupe,
    ":id_etudiant" => $id_etudiant
]);

// 8. Get group code
$sql3 = "SELECT code_groupe FROM groupe WHERE Id_Groupe = :id_groupe";
$stmt3 = $conn->prepare($sql3);
$stmt3->execute([":id_groupe" => $id_groupe]);
$groupe = $stmt3->fetch();

// 9. Save in session
$_SESSION['code_groupe'] = $groupe['code_groupe'];
$_SESSION['id_groupe']   = $id_groupe;

// 10. Redirect

header("Location: /PFS/cree_rejoindre_grp/afficher_groupe.php");
exit;
?>