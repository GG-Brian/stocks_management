<link rel="stylesheet" href="CSS/logged.php">
<link rel="stylesheet" href="CSS/table.php">

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Stocks Disponibles</title>
    <link rel="stylesheet" href="CSS/table.css">
</head>
<body class="fondo">
    <?php include_once "login.php"; ?>
    <div style="background-color: red">
        <a href="custompage.php"><button type='button' href="custompage.php">Actualizar estilo de tabla</button></a>
    </div>

    <?php
        $host = "localhost";
        $database = "proyecto";
        $username = "brian";
        $password = "Totalitario";
        $dataOrigin = "mysql:host=$host;dbname=$database"; // Called DSN for short
        
        try {
            $databaseObject = new PDO($dataOrigin, $username, $password); // Closes with NULL
            $databaseObject->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $isFiltered = false;

            $filterId="%";
            $filterNombre="%";
            $rowsPerPage=5;

            if (count($_POST) > 0){
                $isFiltered = true;
                if ($_POST["sinFiltro"] == "true"){
                    $productoId = "";
                    $productoNombre = "";
                    $rowsPerPage = 5;
                } else {
                    $productoId = $_POST["id"];
                    $productoNombre = $_POST["nombre"];
                    $rowsPerPage = $_POST["rowsPerPage"];
                }
                $charactersProductoId = str_split($productoId);
                $charactersProductoNombre = str_split($productoNombre);
                foreach ($charactersProductoId as $index => $character){      $filterId = $filterId.$character."%";}
                foreach ($charactersProductoNombre as $index => $character){  $filterNombre = $filterNombre.$character."%";}
            } else if (isset($_GET["rowsPerPage"])){
                $rowsPerPage = $_GET["rowsPerPage"];
            }

            $selectProductos = $databaseObject->query("SELECT id,nombre FROM productos WHERE id LIKE '".$filterId."' AND nombre LIKE '".$filterNombre."'");
            $totalConsultRows = $selectProductos->rowCount();
            $totalPages = ceil($totalConsultRows / $rowsPerPage);
            
            $actualPage = 1;
            if (isset($_GET["actualPage"])) { $actualPage = $_GET["actualPage"];}
            $currentPageResults = ($actualPage - 1) * $rowsPerPage;
            
            $selectProductosFilteredLimitedOrdered = "SELECT id,nombre FROM productos
                                                      WHERE id LIKE '".$filterId."' AND nombre LIKE '".$filterNombre."'";

            if (isset($_GET["orderCode"]) && $_GET["orderCode"] == "true"){
                if (isset($_GET["orderName"])){
                    if ($_GET["orderName"] == "1"){      $selectProductosFilteredLimitedOrdered = $selectProductosFilteredLimitedOrdered." ORDER BY id DESC, nombre ASC "; }
                    else if ($_GET["orderName"] == "2"){ $selectProductosFilteredLimitedOrdered = $selectProductosFilteredLimitedOrdered." ORDER BY id DESC, nombre DESC ";}
                    else {                               $selectProductosFilteredLimitedOrdered = $selectProductosFilteredLimitedOrdered." ORDER BY id DESC ";}
                } else {                                 $selectProductosFilteredLimitedOrdered = $selectProductosFilteredLimitedOrdered." ORDER BY id DESC ";}}
            else if (isset($_GET["orderName"])){
                if ($_GET["orderName"] == "1"){          $selectProductosFilteredLimitedOrdered = $selectProductosFilteredLimitedOrdered." ORDER BY nombre ASC "; }
                else if ($_GET["orderName"] == "2"){     $selectProductosFilteredLimitedOrdered = $selectProductosFilteredLimitedOrdered." ORDER BY nombre DESC ";}
                else {                                   $selectProductosFilteredLimitedOrdered = $selectProductosFilteredLimitedOrdered." ";}}
            else {                                       $selectProductosFilteredLimitedOrdered = $selectProductosFilteredLimitedOrdered." ";}

            $selectProductos = $databaseObject->query($selectProductosFilteredLimitedOrdered."LIMIT ".$currentPageResults.",".$rowsPerPage);
            if ($selectProductos){
                echo("<h1>Gestor de Productos Disponibles en Stock</h1>
                      <div class='table-custom space-around'>
                        <div>
                            <form action='listado.php' method='post'>
                                <select name='rowsPerPage'>");
                if (isset($_POST["rowsPerPage"])){
                    echo("                           <option value='".$rowsPerPage."' selected='selected'>Mostrando ".$rowsPerPage." registros</option>");
                    if ($rowsPerPage != 5){    echo("<option value='5'>5 productos</option>"); } 
                    if ($rowsPerPage != 10) {  echo("<option value='10'>10 productos</option>"); }
                    if ($rowsPerPage != 20){   echo("<option value='20'>20 productos</option>"); }
                    if ($rowsPerPage != 50) {  echo("<option value='50'>50 productos</option>"); }
                    if ($rowsPerPage != 100) { echo("<option value='100'>100 productos</option>"); }
                    if ($rowsPerPage != 500) { echo("<option value='500'>500 productos</option>"); }
                } else {
                    echo("          <option value='5' selected='selected'>5 productos</option>
                                    <option value='10'>10 productos</option>
                                    <option value='20'>20 productos</option>
                                    <option value='50'>50 productos</option>
                                    <option value='100'>100 productos</option>
                                    <option value='200'>200 productos</option>
                                    <option value='500'>500 productos</option>");
                }
                echo("          </select>
                                <input class='input-filter' type='text' name='id' placeholder='Código de elemento' ");
                if ($isFiltered && $_POST["sinFiltro"] == "false"){                                          echo("value='".$productoId."'/>");}
                else {                                                                                       echo("/>");}
                echo("          <input class='input-filter' type='text' name='nombre' placeholder='Nombre de elemento' ");
                if ($isFiltered && $_POST["sinFiltro"] == "false"){                                          echo("value='".$productoNombre."'/>");} 
                else {                                                                                       echo("/>");}
                echo("          <br>
                                <button type='submit'>Filtrar</button>
                                <select name='sinFiltro'>
                                    <option value='false' selected='selected'>Guardar Filtro</option>
                                    <option value='true'>Borrar filtro</option>
                                </select>
                            </form>
                            <a href='crear.php'><button type='button' class='create'>Crear registro</button></a>
                        </div>
                        <div>");
                if (isset($_GET["orderCode"]) && $_GET["orderCode"] == "true"){
                    echo"           <a href='listado.php?actualPage=".$actualPage."&rowsPerPage=".$rowsPerPage."&orderCode=false&orderName=";if(isset($_GET["orderName"])){echo $_GET["orderName"];}echo"'>
                                        <button type='button' class='paging'>Código Orden Natural</button>
                                    </a>";}
                else { echo"        <a href='listado.php?actualPage=".$actualPage."&rowsPerPage=".$rowsPerPage."&orderCode=true&orderName=";if(isset($_GET["orderName"])){echo $_GET["orderName"];}echo"'>
                                        <button type='button' class='paging'>Código Orden Inverso</button>
                                    </a>";}
                if (isset($_GET["orderName"]) && ($_GET["orderName"] == "1")){
                    echo"           <a href='listado.php?actualPage=".$actualPage."&rowsPerPage=".$rowsPerPage."&orderCode=";if(isset($_GET["orderCode"])){echo $_GET["orderCode"];}echo"&orderName=2'>
                                        <button type='button' class='paging'>Orden Inverso</button>
                                    </a>
                                    <a href='listado.php?actualPage=".$actualPage."&rowsPerPage=".$rowsPerPage."&orderCode=";if(isset($_GET["orderCode"])){echo $_GET["orderCode"];}echo"'>
                                        <button type='button' class='paging'>Eliminar Orden</button>
                                    </a>";}
                else if (isset($_GET["orderName"]) && ($_GET["orderName"] == "2")){
                    echo"           <a href='listado.php?actualPage=".$actualPage."&rowsPerPage=".$rowsPerPage."&orderCode=";if(isset($_GET["orderCode"])){echo $_GET["orderCode"];}echo"&orderName=1'>
                                        <button type='button' class='paging'>Orden Natural</button>
                                    </a>
                                    <a href='listado.php?actualPage=".$actualPage."&rowsPerPage=".$rowsPerPage."&orderCode=";if(isset($_GET["orderCode"])){echo $_GET["orderCode"];}echo"'>
                                        <button type='button' class='paging'>Eliminar Orden</button>
                                    </a>";}
                else {echo"         <a href='listado.php?actualPage=".$actualPage."&rowsPerPage=".$rowsPerPage."&orderCode=";if(isset($_GET["orderCode"])){echo $_GET["orderCode"];}echo"&orderName=1'>
                                        <button type='button' class='paging'>Orden Natural</button>
                                    </a>
                                    <a href='listado.php?actualPage=".$actualPage."&rowsPerPage=".$rowsPerPage."&orderCode=";if(isset($_GET["orderCode"])){echo $_GET["orderCode"];}echo"&orderName=2'>
                                        <button type='button' class='paging'>Orden Inverso</button>
                                    </a>";}
                                    
                echo("              <table>");
                $isPair = false;
                while ($producto = $selectProductos->fetch()){
                    if ($isPair){ echo("<tr class='row-pair-custom'>");   $isPair = false; }
                    else {        echo("<tr class='row-unpair-custom'>"); $isPair = true; }
                    echo("          <td><a href='update.php?id=".$producto["id"]."'><button type='button' class='update'>Actualizar</button></a></td>
                                    <td><a href='detalle.php?id=".$producto["id"]."'><button type='button' class='details'>".$producto["id"]."</button></a></td>
                                    <td><a href='muevestock.php?id=".$producto["id"]."'><button type='button' class='move'>Mover stock</button></a></td>
                                    <td>".$producto["nombre"]."</td>
                                    <td><a href='borrar.php?id=".$producto["id"]."'><button type='button' class='remove'>Borrar</button></a></td>
                                </tr>");
                }
                echo("      </table>");
                for ($page=1; $page<=$totalPages; $page++){
                    echo "  <a href='listado.php?actualPage=".$page."&rowsPerPage=".$rowsPerPage."&orderCode=";if(isset($_GET["orderCode"])){echo $_GET["orderCode"];}echo"&orderName=";if(isset($_GET["orderName"])){echo $_GET["orderName"];}echo"'><button type='button' class='paging'>".$page."</button></a>";
                }
                echo("      <br>
                            <a ");if($actualPage-1==0){       echo"style='visibility: hidden;'";} echo" href='listado.php?actualPage=".($actualPage-1)."&rowsPerPage=".$rowsPerPage."&orderCode=";if(isset($_GET["orderCode"])){echo $_GET["orderCode"];}echo"&orderName=";if(isset($_GET["orderName"])){echo $_GET["orderName"];}echo"'>
                                <button type='button' class='page-move'>Anterior</button>
                            </a>
                            <button type='button' class='page-indicator'>".$actualPage."</button>
                            <a ";if($actualPage==$totalPages){echo"style='visibility: hidden;'";} echo" href='listado.php?actualPage=".($actualPage+1)."&rowsPerPage=".$rowsPerPage."&orderCode=";if(isset($_GET["orderCode"])){echo $_GET["orderCode"];}echo"&orderName=";if(isset($_GET["orderName"])){echo $_GET["orderName"];}echo"'>
                                <button type='button' class='page-move'>Siguiente</button>
                            </a>
                        </div>";
            } else {
                echo("Ha ocurrido algún problema cargando los datos, sentímos las molestias causadas.");
                $badAccessNumber = $_COOKIE["badAccess"];
                setCookie("badAccess", $badAccessNumber+1, time()+2629746000);
            }
            $databaseObject = null;
        } catch(PDOException $errorEvent){
            $badAccessNumber = $_COOKIE["badAccess"];
            setCookie("badAccess", $badAccessNumber+1, time()+2629746000);
            die("<h3 class='update'>Ha surgido un error en la conexión, esta es su descripción: </h3>" . $errorEvent->getMessage(). "<br><br><br>". $selectProductosFilteredLimitedOrdered);
        }
    ?>
</body>
</html>