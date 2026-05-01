<?php
session_start();

if (!isset($_SESSION['id_etudiant'])) {
    header("Location: connection_etudiant.html");
    exit;
}

?>.