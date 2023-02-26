<link rel="stylesheet" href="CSS/logged.php">
<link rel="stylesheet" href="CSS/table.php">

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Registro de Stock disponible</title>
    <link rel="stylesheet" href="CSS/table.css">
</head>
<body class="fondo">
    <?php include_once "login.php";
        $host = "localhost";
        $database = "proyecto";
        $username = "brian";
        $password = "Totalitario";
        $dataOrigin = "mysql:host=$host;dbname=$database"; // Called DSN for short

        try{
            $databaseObject = new PDO($dataOrigin, $username, $password); // Closes with NULL
            $databaseObject->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            $selectFamilias = $databaseObject->query("SELECT nombre FROM familias");
            
            $queryLastProductoId = $databaseObject->query("SELECT max(id) as id FROM productos");
            $queryLastProducto = $queryLastProductoId->fetch();
            $newProductoId = $queryLastProducto["id"] + 1;
            
            echo("<h1>Gestor de Productos Disponibles en Stock</h1>
                  <h2>Creando producto para el stock</h2>");
    
            if (count($_POST) > 0 && trim($_POST["id"]) != ""){
                $insertedProducto = $databaseObject->query("INSERT INTO productos
                    VALUES ('".$newProductoId."',
                            '".$_POST["nombre"]."',
                            '".$_POST["nombre_corto"]."',
                            '".$_POST["descripcion"]."',
                            '".$_POST["pvp"]."',
                            '".$_POST["familia"]."')");
                if ($insertedProducto){
                    echo("<div id='action-confirmed' class='table-custom space-around'>
                            <a href='update.php?id=".$_POST["id"]."'><button type='button' class='update'><h2>Acción completada con éxito</h2></button></a>
                            <br><br>
                            <a href='detalle.php?id=".$newProductoId."'><button type='button' class='details'>Visualizar registro</button></a>
                            <a href='listado.php'><button type='button' class='create'>Volver al Listado</button></a>
                            <a href='borrar.php?id='".$newProductoId."''><button type='button' class='remove'>Eliminar registro</button></a>
                          </div>");
                }
            } else if ($selectFamilias){
                echo("<div id='action-to-confirm' class='table space-around'>
                          <form action='crear.php' method='post'>
                            <input name='id' type='text' value='".$newProductoId."' hidden>
                            <br>
                            <input name='nombre' type='text' placeholder='Nombre oficial de producto'>
                            <input name='nombre_corto' type='text' placeholder='Nombre oficial de producto'>
                            <br><br>
                            <textarea name='descripcion' rows='4' cols='50' placeholder='Descripción precisa'></textarea>
                            <br><br>
                            <input name='pvp' type='float' placeholder='Precio de Venta al Público'>
                            <br><br>
                            <select name='familia'>");
                            while ($familia = $selectFamilias->fetch()){
                                echo("<option>".$familia["nombre"]."</option>");
                            }
                            echo("</select>
                            <br><br><br>
                            <button type='submit' class='create'>Agregar producto</button>
                            <br><br>
                          </form>
                      </div>");
            } else {
                echo("Ha ocurrido algún problema accediendo a la base de datos, sentímos las molestias causadas.");
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