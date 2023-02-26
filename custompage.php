<link rel="stylesheet" href="CSS/logged.php">
<!-- <link rel="stylesheet" href="CSS/table.php"> -->
<link rel="stylesheet" href="CSS/table.css">

<body class="fondo">
  <h1>Personalizador de interfaz web</h1>
  <h2>Actualmente, su interfaz se visualiza como en esta página y tabla</h2>

  <?php include_once "login.php";
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if ( ! empty($_GET)){
      foreach ($_GET as $index => $color){
        if ($_GET[$index] != "#000000"){
          $_SESSION[$index] = $color;
        }
      }
    }
    echo("<form action='custompage.php' action='GET'>");
    // <input type='color' name='body' class='body-custom' style='height: 80px; width: 80px'"); if(isset($_SESSION["body"])){ echo("value='".$_SESSION["body"]."'"); } echo(">
    echo("<br><br>
    <div class='table-custom space-around'>
      <input type='color' name='table' class='table-custom' style='height: 80px; width: 80px'"); if(isset($_SESSION["table"])){ echo("value='".$_SESSION["table"]."'"); } echo(">
      <table style='font-size: 30px'><br><br>
        <tr class='row-unpair-custom'>
          <td>Impar<br><input type='color' name='unpair' class='row-unpair row-unpair-custom' style='height: 80px; width: 80px'"); if(isset($_SESSION["unpair"])){ echo("value='".$_SESSION["unpair"]."'"); } echo("></td>
          <td>dato</td>
          <td>dato</td>
          <td>dato</td>
          <td>dato</td>
        </tr>
        <tr class='row-pair-custom'>
          <td>Par<br><input type='color' name='pair' class='row-pair row-pair-custom' style='height: 80px; width: 80px'"); if(isset($_SESSION["pair"])){ echo("value='".$_SESSION["pair"]."'"); } echo("></td>
          <td>dato</td>
          <td>dato</td>
          <td>dato</td>
          <td>dato</td>
        </tr>
      </table>
    </div>
      
    <h2>Cambie los colores a través de los botones superiores, el color interno de cada botón será el nuevo color de su zona</h2>
      
    <button type='submit'>Aplicar cambios</button>
    <a href='listado.php'><button type='button'>Ir al listado de productos</button></a>
    </form>");
    
  ?>
</body>