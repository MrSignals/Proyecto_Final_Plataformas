<?php include "templates/header.php"; ?>
<?php
include 'funciones.php';
if(isset($_POST['submit'])){
    $resultado = [
        'error' => false,
        'mensaje' => 'La persona ' . escapar($_POST['nombre']) . ' ha sido agendada con éxito'
    ];

    $config = include 'config.php';

    try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    
        $persona = array(
          "Nombre"   => $_POST['nombre'],
          "Apellido" => $_POST['apellido'],
          "Email"    => $_POST['email'],
          "Edad"     => $_POST['edad'],
          "Peso" => $_POST['peso'],
          "Altura" => $_POST['altura'],
        );
    
        $consultaSQL = "INSERT INTO personas (Nombre, Apellido, Email, Edad, Peso, Altura) values (:" . implode(", :", array_keys($persona)) . ")";
    
        $sentencia = $conexion->prepare($consultaSQL);
        $sentencia->execute($persona);
    
      } catch(PDOException $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = $error->getMessage();
      }
}
?>
<?php include 'templates/header.php';?>
<?php
if (isset($resultado)) {
  ?>
  <div class="container mt-3">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-<?= $resultado['error'] ? 'danger' : 'success' ?>" role="alert">
          <?= $resultado['mensaje'] ?>
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <a href="crear.php" class="btn btn-primary mt-4">Agendar Persona</a>
            <hr>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control">
                </div>
                <div class="form-group">
                    <label for="apellido">Apellido</label>
                    <input type="text" name="apellido" id="apellido" class="form-control">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control">
                </div>
                <div class="form-group">
                    <label for="edad">Edad</label>
                    <input type="text" name="edad" id="edad" class="form-control">
                </div>
                <div class="form-group">
                    <label for="peso">Peso</label>
                    <input type="text" name="peso" id="peso" class="form-control">
                </div>
                <div class="form-group">
                    <label for="altura">Altura</label>
                    <input type="text" name="altura" id="altura" class="form-control">
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" class="btn btn-primary" value="Enviar">
                    <a class="btn btn-primary" href="index.php">Regresar al inicio</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include "templates/footer.php"; ?>