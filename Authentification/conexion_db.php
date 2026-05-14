<?php
$conn = new PDO(
    "mysql:host=localhost;port=3306;dbname=gestion_de_project","root","Salah@sql12");
?>
<!--fichier de la conexion de la base de donne on le fait dans un fichier independant pour : .ne pas repetez le code 
.pour simplifiez la maintenance    on l appelle avec require