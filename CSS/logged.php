<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();

    $fondo = "";
    $letra = "";

    if (isset($_SESSION["fondo"])){ $fondo = ".fondo { background-color: ".$_SESSION["fondo"]." ;}"; }
    if (isset($_SESSION["letra"])){ $letra = ".letra { font-family: ".$_SESSION["letra"]."      ;}"; }

    echo ($fondo);
    echo ($letra);
?>