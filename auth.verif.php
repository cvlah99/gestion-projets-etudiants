<?php
session_start();

if (!isset($_SESSION['id_etudiant'])) { //si la session de l utlisateur est vide donc l utulisateur n est pas connecte 
    header("Location: /PFS/connection_etudiant/connection_etudiant.html");
    exit;
}