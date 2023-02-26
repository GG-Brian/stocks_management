<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autenticación a la Brianesca</title>
    <link rel="stylesheet" href="CSS/logged.php">
    <style><?php include "CSS/table.php" ?></style>
    <link rel="stylesheet" type="text/css" href="CSS/table.css"/>
</head>
<body class="body-custom">
    <?php
        if ($_SERVER["REQUEST_URI"] == "/stocks_management/login.php?close"){
            session_unset();
            $_SERVER["PHP_AUTH_USER"] = "";
        }
        function solicitudCredenciales(){
            header('WWW-Authenticate: Basic Realm="Contenido restringido"');
            header('HTTP/1.0 401 Unauthorized');
            echo'¡Usuario no reconocido!';
            exit;
        }
        function login($name = "none", $key = "none"){
            $host = "localhost";
            $database = "proyecto";
            $username = "brian";
            $password = "Totalitario";
            $dataOrigin = "mysql:host=$host;dbname=$database"; // Called DSN for short
            
            $databaseObject = new PDO($dataOrigin, $username, $password); // Closes with NULL
            $databaseObject->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $userFilter = $databaseObject->query("SELECT * FROM usuarios WHERE usuario = '".$name."' AND clave = '".$key."';");
            if ($userFilter->rowCount() == 1){
                if(session_status() !== PHP_SESSION_ACTIVE) session_start();
                $userData = $userFilter->fetch();
                $_SESSION["user"] = $userData["usuario"];
                $_SESSION["fullname"] = $userData["nombrecompleto"];
                $_SESSION["email"] = $userData["correo"];
                $_SESSION["key"] = $userData["clave"];
                $_SESSION["fondo"] = $userData["colorfondo"];
                $_SESSION["letra"] = $userData["tipoletra"];
                return "";
            }
            return $name.":".$key;
        }

        while (true){
            if ( ! isset($_COOKIE["badAccess"])){
                setCookie("badAccess", 0, time()+2629746000); // https://www.inchcalculator.com/convert/month-to-millisecond/ Un mes
                setCookie("loginError", "no", time()+2629746000);
                setCookie("lastSuccessLogin", "nunca", time()+2629746000);
            }
            $login = login($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
            if ( ! isset($_SERVER['PHP_AUTH_USER']) || $login != ""){
                if ( $login != ""){
                    if ( $_COOKIE["loginError"] == "no"){
                        setcookie("loginError", $login, strtotime( '+30 days' ));
                    } else {
                        setcookie("loginError", $_COOKIE["loginError"].";".$login, strtotime( '+30 days' ));
                    }
                }
                solicitudCredenciales();
            } else {
                setcookie("lastSuccessLogin", date("l-d-m-y h:i:sa") , time()+2629746000);
                echo("<h2 class='table'>Entre hoy y hace un mes, se detectaron;<br>".$_COOKIE["badAccess"]." accessos inválidos,<br>".$_COOKIE["loginError"]." credenciales de acceso incorrectos.<br><br>El último inicio de sesión existoso ▶ ".$_COOKIE["lastSuccessLogin"]."</h2>");
                echo("<h2 class='table'>Buenos días, ".$_SESSION["fullname"].", si quiere cambiar sus datos de perfil -> <a href='perfil.php'>¡Clickea aquí!</a><- O bien cerrar sesión -><a href='login.php?close'>Aquí</a><-</h2>");
                break;
            }
        }
    ?>
</body>
</html>