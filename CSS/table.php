<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();

    if ( ! isset($_SESSION["body"])){  $_SESSION["body"]  = "#FFEAEE"; } // Default color at table.css https://www.rgbtohex.net/
    if ( ! isset($_SESSION["table"])){ $_SESSION["table"] = "#0B5394"; } // Default color at table.css https://www.rgbtohex.net/
    if ( ! isset($_SESSION["unpair"])){$_SESSION["unpair"]= "#6A9ABF"; } // Default color at table.css https://www.rgbtohex.net/
    if ( ! isset($_SESSION["pair"])){  $_SESSION["pair"]  = "#4F6D95"; } // Default color at table.css https://www.rgbtohex.net/

    $body     = ".body-custom      { background-color: ".$_SESSION["body"]."  ;}";
    $table    = ".table-custom     { background-color: ".$_SESSION["table"]." ;}";
    $rowUnpair= ".row-unpair-custom{ background-color: ".$_SESSION["unpair"].";}";
    $rowPair  = ".row-pair-custom  { background-color: ".$_SESSION["pair"]."  ;}";

    echo($body);
    echo($table);
    echo($rowUnpair);
    echo($rowPair);
?>