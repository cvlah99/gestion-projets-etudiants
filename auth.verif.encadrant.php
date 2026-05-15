<?php
session_start();

if (empty($_SESSION['id_encadrant'])) {
    header("Location: /PFS/admin/login_encadrant.html");
    exit;
}