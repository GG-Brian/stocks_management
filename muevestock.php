<link rel="stylesheet" href="CSS/logged.php">
<link rel="stylesheet" href="CSS/table.php">

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mover Stock entre tiendas</title>
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
    
            $urlId = explode("=", $_SERVER["REQUEST_URI"]);
            if (count($_GET) > 1 && isset($_GET["error"])){
                $errorConsult = $databaseObject->query("SELECT felicidad FROM vida");
            } else if (isset($urlId[1])){
                if (preg_match("/\d*/", $urlId[1])){
                    $queryProducto = $databaseObject->query("SELECT id,nombre FROM productos WHERE id='".$urlId[1]."';");
                    $queryProductoTienda = $databaseObject->query("SELECT tienda,unidades FROM stocks WHERE producto='".$urlId[1]."';");
    
                    echo("<h1>Gestor de Producto Disponible en Stock -> ".$urlId[1]."</h1>
                          <h2>Movilizando stock de producto entre tiendas</h2>
                          <div class='table-custom space-around'>
                            <table>
                                <tr class='information table-header space-around'>
                                    <th>Tienda</th>
                                    <th>En posesión</th>
                                    <th>Enviar a</th>
                                    <th>Unidades</th>
                                    <th></th>
                                </tr>");
                    $isPair = false;
    
                    echo("
                    <form action='muevestock.php' method='get'>
                    <input hidden type='input' name='id' value=".$urlId[1].">
                    <button type='submit' name='error' value='true'>Causar error de excepción PDO</button>
                    </form>
                    ");
                    
                    while ($stocksField = $queryProductoTienda->fetch()){
                        $queryTienda = $databaseObject->query("SELECT id,nombre FROM tiendas WHERE id='".$stocksField["tienda"]."';");
                        $queryTiendas = $databaseObject->query("SELECT id,nombre FROM tiendas;");
                        $tiendaFields = $queryTienda->fetch();
                        $tiendasFields = $queryTiendas->fetchAll();
                        
                        if ($isPair){ echo("<tr class='row-pair-custom'>");   $isPair = false; }
                        else {        echo("<tr class='row-unpair-custom'>"); $isPair = true; }
    
                        echo("<form action='muevestock.php' method='post'>
                                <input name='producto' value='".$urlId[1]."' hidden>
                                <input name='tienda' value='".$tiendaFields["id"]."' hidden>
                                <td class='space-around'>".$tiendaFields["nombre"]."</td>
                                <td class='space-around'>".$stocksField["unidades"]." unidades</td> 
                                <td class='space-around'>
                                    <select name='destino'>");
                        foreach ($tiendasFields as $tiendaField => $tiendaValueArray){
                            if ($tiendaFields["nombre"] != $tiendaValueArray["nombre"]){
                                echo("  <option value='".$tiendaValueArray["id"]."'>".$tiendaValueArray["nombre"]."</option>");
                            }
                        }
                        echo("      </select>
                                </td>
                                <td class='space-around'>
                                    <select name='unidades'>");
                        if ($stocksField["unidades"] < 2){
                            echo("      <option value='1'>Enviar única unidad</option>");}
                        else {
                            echo("      <option value='1'>Enviar 1 unidad</option>");
                            for ($i = 2; $i < $stocksField["unidades"]; $i++){
                                echo("  <option value='".$i."'>Enviar ".$i." unidades</option>");
                            }   echo("  <option value='".$stocksField["unidades"]."'>Enviar Todo</option>");}
                        echo("      </select>
                                </td>
                                <td>
                                    <button type='submit' class='move'>Realizar envio</button>
                                </td>
                            </form>
                        </tr>");
                    }
                } else {
                    echo("Parece haber algún tipo de problema con respecto al id al que se accede, disculpe las molestias");
                    $badAccessNumber = $_COOKIE["badAccess"];
                    setCookie("badAccess", $badAccessNumber+1, time()+2629746000);
                }
            
            } else if (count($_POST) > 0){
                $isEverythingCorrect = true;
                $producto = $_POST["producto"];
                $tienda = $_POST["tienda"];
                $destino = $_POST["destino"];
                $unidades = $_POST["unidades"];
    
                $whereProductoDestino = "WHERE producto='".$producto."' AND tienda='".$destino."'";
                $whereProductoTienda = "WHERE producto='".$producto."' AND tienda='".$tienda."'";
                            
                $databaseObject->beginTransaction();
                $isRowExistent = $databaseObject->query("SELECT unidades FROM stocks $whereProductoDestino AND unidades > 0");
                if ($destinyRow = $isRowExistent->fetch()){
                    $isTreatedRowDeletable = $databaseObject->query("SELECT unidades FROM stocks $whereProductoTienda AND unidades > 0");
                    $treatedRow = $isTreatedRowDeletable->fetch();
                    if ($treatedRow["unidades"] == $unidades){
                        if (!$stockRemove = $databaseObject->query("DELETE FROM stocks $whereProductoTienda AND unidades > 0")){
                            $transactionConfirmed = false;
                        }
                    }
                    if (!$stockUnidadesPlus = $databaseObject->exec("UPDATE stocks SET unidades=unidades+".$unidades." ".$whereProductoDestino)){
                        $transactionConfirmed = false;
                    }
                } else {
                    if (!$stockInsert = $databaseObject->exec("INSERT INTO stocks VALUES ('".$producto."','".$destino."','".$unidades."')")){
                        $transactionConfirmed = false;
                    }
                }
                if (!$stockUnidadesMinus = $databaseObject->exec("UPDATE stocks SET unidades=unidades-".$unidades." ".$whereProductoTienda)){
                    $transactionConfirmed = false;
                }
                            
                echo("<h1>Gestor de Producto Disponible en Stock -> ".$producto."</h1>
                      <h2>Movilizando stock de producto entre tiendas</h2>
                      <div id='action-confirmed' class='table space-around'>");
                if ($isEverythingCorrect){
                    $databaseObject->commit();
                    echo("<button type='button' class='update'><h2>Acción completada con éxito</h2></button>
                        <br><br>
                        <a href='detalle.php?id=".$_POST["producto"]."'><button type='button' class='details'>Visualizar producto</button></a>
                        <a href='listado.php'><button type='button' class='create'>Volver al Listado</button></a>
                        <a href='muevestock.php?id=".$_POST["producto"]."'><button type='button' class='move'>Seguir movilizando</button></a>
                    </div>");
                } else {
                    $badAccessNumber = $_COOKIE["badAccess"];
                    setCookie("badAccess", $badAccessNumber+1, time()+2629746000);
                    $databaseObject->rollback();
                }
    
            } else {
                echo("Esta página es inaccesible sin un código de referencia, por favor, accede a partir de un producto del <a href='listado.php'>Listado</a>");
                $badAccessNumber = $_COOKIE["badAccess"];
                setCookie("badAccess", $badAccessNumber+1, time()+2629746000);
            }
            $databaseObject = null;
        } catch(PDOException $errorEvent){
            setCookie("badAccess", $_COOKIE["badAccess"]+1, time()+2629746000);
            die("<h3 class='update'>Ha surgido un error en la conexión, esta es su descripción: </h3>" . $errorEvent->getMessage()."<br><br><br>
            <a href='detalle.php?id=".$_GET["id"]."'><button type='button' class='details'>Visualizar registro</button></a>
            <a href='listado.php'><button type='button' class='create'>Volver al Listado</button></a>
            <a href='borrar.php?id=".$_GET["id"]."'><button type='button' class='remove'>Eliminar registro</button></a");
        }
    ?>
</body>
</html>