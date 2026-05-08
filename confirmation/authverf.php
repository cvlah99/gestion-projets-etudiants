<?php
session_start();
if (!isset($_SESSION['id_enseignant'])) {
    header("Location: connection_enseignant.html");
    exit;
}
?>