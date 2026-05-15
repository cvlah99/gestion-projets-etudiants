<?php
session_start();

if (empty($_SESSION['id_admin'])) {
    header("Location: /PFS/admin/login_admin.html");
    exit;
}