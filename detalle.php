<link rel="stylesheet" href="CSS/logged.php">
<link rel="stylesheet" href="CSS/table.php">

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Stock Disponible</title>
    <link rel="stylesheet" href="CSS/table.css">
</head>
<body class="body-custom">
    <?php include_once "login.php";
        $host = "localhost";
        $database = "proyecto";
        $username = "brian";
        $password = "Totalitario";
        $dataOrigin = "mysql:host=$host;dbname=$database"; // Called DSN for short

        try {
            $databaseObject = new PDO($dataOrigin, $username, $password); // Closes with NULL
            $databaseObject->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            if (isset($_GET["id"])){
                $productoId = $_GET["id"];
                $selectProducto = $databaseObject->query("SELECT * FROM productos WHERE id='".$productoId."';");
                $producto = $selectProducto->fetch();
    
                echo("<h1>Gestor de Producto Disponible en Stock -> ".$productoId."</h1>
                      <h2>Visualizando detalles de un producto</h2>
                      <div class='table-custom space-around'>
                        <form action='listado.php' method='post'>
                            <br>");
                if ($producto){
                    echo("  <h2 class='information'>".$producto["nombre"]."</h2>
                            <h3 class='information'>".$producto["nombre_corto"]."</h3>
                            <span class='information'>".$producto["descripcion"]."</span>
                            <br><br>
                            <p class='information'>".$producto["pvp"]."</p>
                            <p class='information'>".$producto["familia"]."</p>
    
                            <br>
                            <a href='update.php?id=".$productoId."'><button type='button' class='update'>Actualizar</button></a>
                            <button type='submit' class='create'>Volver al Listado</button>
                        </form>
                    </div>");
                } else {
                    echo("Ha ocurrido algún problema cargando los datos, sentímos las molestias causadas.");
                    $badAccessNumber = $_COOKIE["badAccess"];
                    setCookie("badAccess", $badAccessNumber+1, time()+2629746000);
                }
            } else {
                echo("Esta página es inaccesible sin un código de referencia, por favor, accede a partir de un producto del <a href='listado.php'>Listado</a>");
                $badAccessNumber = $_COOKIE["badAccess"];
                setCookie("badAccess", $badAccessNumber+1, time()+2629746000);
            }
            $databaseObject = null;
        } catch(PDOException $errorEvent){
            $badAccessNumber = $_COOKIE["badAccess"];
            setCookie("badAccess", $badAccessNumber+1, time()+2629746000);
            die("<h3 class='update'>Ha surgido un error en la conexión, esta es su descripción: </h3>" . $errorEvent->getMessage()."<br><br><br>
            <a href='detalle.php?id=".$_GET["id"]."'><button type='button' class='details'>Visualizar registro</button></a>
            <a href='listado.php'><button type='button' class='create'>Volver al Listado</button></a>
            <a href='borrar.php?id=".$_GET["id"]."'><button type='button' class='remove'>Eliminar registro</button></a");
        }
        ?>
</body>
</html>