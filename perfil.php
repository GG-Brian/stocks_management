<link rel="stylesheet" href="CSS/logged.php">

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil-ator de la Brianesca</title>
  <link rel="stylesheet" href="CSS/table.css">
  <link rel="stylesheet" href="CSS/table.php">
</head>
<body class="fondo">
  <h1>Configuración de Perfil</h1>
  <h2>Defina a su gusto los datos de su perfil web</h2>
  <?php include_once "login.php";

    function generateForm(){
      echo("<form action='perfil.php' method='POST'><br><br>
      <div class='table-custom space-around'>
        <table style='font-size: 30px; text-align: center;'>
          <tr class='row-pair-custom'>
            <th style='visibility: hidden;'><button>Eliminar Orden</button></th>
            <th>Usuario</th>
            <th>  Nombre Completo  </th>
            <th>Correo</th>
            <th style='visibility: hidden;'><button>Eliminar Orden</button></th>
          </tr>
          <tr class='row-pair-custom'>
            <td style='visibility: hidden;'><button>Eliminar Orden</button></td>
            <td><input name='user' value='".$_SESSION['user']."'></td>
            <td><input name='fullname' value='".$_SESSION["fullname"]."'></td>
            <td><input name='email' value='".$_SESSION['email']."'></td>
            <td style='visibility: hidden;'><button>Eliminar Orden</button></td>
          </tr>
          <tr class='row-unpair-custom'>
            <th style='visibility: hidden;'><button>Eliminar Orden</button></th>
            <th>Clave</th>
            <th>Fondo</th>
            <th>Letra</th>
            <th style='visibility: hidden;'><button>Eliminar Orden</button></th>
          </tr>
          <tr class='row-unpair-custom'>
            <td style='visibility: hidden;'><button>Eliminar Orden</button></td>
            <td><input type='password' name='key' value='".$_SESSION["key"]."'></td>
            <td><input type='color' name='fondo' value='".$_SESSION["fondo"]."'></td>
            <td>
              <select name='letra'>
                <option value='Arial' ");          if($_SESSION["letra"] == "Arial"){           echo("selected"); }echo(">Arial</option>
                <option value='Comic Sans' ");     if($_SESSION["letra"] == "Comic Sans"){      echo("selected"); }echo(">Comic Sans</option>
                <option value='Times New Roman' ");if($_SESSION["letra"] == "Times New Roman"){ echo("selected"); }echo(">Times New Roman</option>
                <option value='TEXTURA' ");        if($_SESSION["letra"] == "TEXTURA"){         echo("selected"); }echo(">TEXTURA</option>
                <option value='Trajan's Column' ");if($_SESSION["letra"] == "Trajan's Column"){ echo("selected"); }echo(">Trajan's Column</option>
              </select>
            </td>
            <td style='visibility: hidden;'><button>Eliminar Orden</button></td>
          </tr>
        </table>
      </div><br>
      <button type='submit' class='update'>Actualizar perfil</button>
    </form>");
    }
    if ( ! isset($_POST["user"])){
      generateForm();
    } else {
      $isError = false;
      if ($_POST["user"] == ""){
        echo("Usuario<br>");
        $isError = true;
      } 
      if ($_POST["fullname"] == ""){
        echo("Nombre Completo<br>");
        $isError = true;
      } 
      if ($_POST["email"] == ""){
        echo("Correo<br>");
        $isError = true;
      } 
      if ($_POST["key"] == ""){
        echo("Clave<br>");
        $isError = true;
      }
      if ($isError){
        echo("<br>🔼Son campos de texto vacíos detectados, por favor, rellénelos en la parte inferior🔽");
      } else {
        $host = "localhost";
        $database = "proyecto";
        $username = "brian";
        $password = "Totalitario";
        $dataOrigin = "mysql:host=$host;dbname=$database"; // Called DSN for short
      
        $databaseObject = new PDO($dataOrigin, $username, $password); // Closes with NULL
        $databaseObject->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        try {
          $userFilter = $databaseObject->query("UPDATE usuarios SET usuario = '".$_POST["user"]."',
                                                                          clave = '".$_POST["key"]."',
                                                                          nombrecompleto = '".$_POST["fullname"]."',
                                                                          correo = '".$_POST["email"]."',
                                                                          colorfondo = '".$_POST["fondo"]."',
                                                                          tipoletra = '".$_POST["letra"]."'
                                                            WHERE usuario = '".$_SESSION["user"]."'
                                                              AND clave = '".$_SESSION["key"]."'  ;");
          $_SESSION['user']     = $_POST['user'];
          $_SESSION['fullname'] = $_POST['fullname'];
          $_SESSION['email']    = $_POST['email'];
          $_SESSION['key']      = $_POST['key'];
          $_SESSION['fondo']    = $_POST['fondo'];
          $_SESSION['letra']    = $_POST['letra'];
          echo("Su perfil ha sido actualizado exitosamente :D");
        } catch(PDOException $e){
          $badAccessNumber = $_COOKIE["badAccess"];
          setCookie("badAccess", $badAccessNumber+1, time()+2629746000);
          echo("<h1>Ha ocurrido un error relacionado con la base de datos;</h1><br>".$e->getMessage());
        }
      }
      generateForm();
    }
  ?>
</body>
</html>