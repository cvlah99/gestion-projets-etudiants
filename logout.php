
<?php
// Démarrer la session (obligatoire pour la détruire)
session_start();

// Supprimer toutes les variables de session
session_unset();

// Détruire complètement la session
session_destroy();

// Redirection vers la page de connexion
header("Location: /PFS/connection_etudiant/connection_etudiant.html");
exit;
?>