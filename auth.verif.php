<?php
session_start();

if (!isset($_SESSION['id_etudiant'])) {
    header("Location: /PFS/connection_etudiant/connection_etudiant.html");
    exit;
}

?>