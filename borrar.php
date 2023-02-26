<link rel="stylesheet" href="CSS/logged.php">
<link rel="stylesheet" href="CSS/table.php">


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrar Stock del Listado de Stocks Disponible</title>
    <link rel="stylesheet" href="CSS/table.css">
</head>
<body class="fondo">
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
                echo("<h1>Gestor de Productos Disponibles en Stock</h1>
                      <h2>Borrando producto para el stock</h2>");
    
                if (count($_POST) > 0 && trim($_POST["id"]) != ""){
                    $removedProducto = $databaseObject->query("DELETE FROM productos WHERE id='".$productoId."'");
                    if ($removedProducto){
                        echo("<div id='action-confirmed' class='table-custom space-around'>
                                <h2 class='information'>Registro eliminado con éxito</h2>
                                <br><br>
                                <a href='listado.php'><button type='button' class='create'>Volver al Listado</button></a>
                              </div>");
                    }
                } else {
                    $selectProducto = $databaseObject->query("SELECT * FROM productos WHERE id='".$productoId."';");
                    $producto = $selectProducto->fetch();
    
                    echo("<div class='table-custom space-around'>
                          <h3 class='details'>¿Quieres eliminar el siguiente registro? Esta acción no se puede deshacer</h3>
                          <form action='borrar.php?id=".$productoId."' method='post'>
                            <br>
                            <input name='id' value='".$productoId."' hidden>
                            <h2 name='nombre' class='information'>".$producto["nombre"]."</h2>
                            <h3 name='nombre_corto' class='information'>".$producto["nombre_corto"]."</h3>
                            <span name='descripcion' class='information'>".$producto["descripcion"]."</span>
                            <br><br>
                            <p name='pvp' class='information'>".$producto["pvp"]."</p>
                            <p name='familia' class='information'>".$producto["familia"]."</p>
                            <br><br>
                            <a href='update.php?id=".$productoId."'><button type='button' class='update'>Actualizar registro</button></a>
                            <a href='listado.php'><button type='button' class='create'>Volver al listado</button></a>
                            <button type='submit' class='remove'>Eliminar registro</button>
                        </form>
                        </div>");
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