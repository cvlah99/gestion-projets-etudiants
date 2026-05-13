<?php
require(__DIR__ . '/../Authentification/conexion_db.php');
    if(isset($_POST["sujet"])&&
        isset($_POST["description_sujet"])){
            $nom_suject = trim($_POST["sujet"]);
            $description_suject = $_POST["description_sujet"];

            if(!empty($nom_suject) && !empty($description_suject)){
                $query = "INSERT INTO projet (sujet, DescriptionP) VALUES (:nom_suject , :description_suject) ";
                $stmt = $conn->prepare($query);
                $stmt->execute(array(":nom_suject"=>$nom_suject, ":description_suject"=>$description_suject));
                echo "Project sent ";
            }
        }

?>