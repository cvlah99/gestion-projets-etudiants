<?php
function logAction($action, $id_utilisateur = null) { //on cree une fonctioin avec $action ce qui c est passe et qui a fait

    $date = date("Y-m-d H:i:s"); //on recupere la date actuelle
    $ip   = $_SERVER['REMOTE_ADDR'];
    $user = $id_utilisateur ? "ID=" . $id_utilisateur : "non connecte";

    $ligne = "[$date] [$ip] [$user] $action\n";

    $fichier = __DIR__ . "/logs/journal.txt";

    $f = fopen($fichier, "a") or die("Erreur ouverture journal");
    fputs($f, $ligne);
    fclose($f);
}