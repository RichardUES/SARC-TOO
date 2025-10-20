<?php 

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

?>

<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <title>Panel Administrativo</title>

  <link rel="shortcut icon" href="<?= VIRTUAL_PATH ?>/assets/brand/favicon-logo-light.svg" type="image/x-icon">

  <link rel="stylesheet" href="../styles/app.css">

</head>

<body>