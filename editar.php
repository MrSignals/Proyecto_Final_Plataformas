<?php
include 'funciones.php';

$config = include 'config.php';

$resultado = [
  'error' => false,
  'mensaje' => ''
];

if (!isset($_GET['idPersona'])) {
  $resultado['error'] = false;
  $resultado['mensaje'] = 'El paciente no existe';
}

if (isset($_POST['submit'])) {
  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $persona = [
      "idPersona"        => $_GET['id'],
      "Nombre"    => $_POST['nombre'],
      "Apellido"  => $_POST['apellido'],
      "Email"     => $_POST['email'],
      "Edad"      => $_POST['edad'],
      "Peso" => $_POST['peso'],
      "Altura" => $_POST['altura']
    ];
    
    $consultaSQL = "UPDATE personas SET
        Nombre = :Nombre,
        Apellido = :Apellido,
        Email = :Email,
        Edad = :Edad,
        Peso= :Peso,
        Altura = :Altura,
        updated_at = NOW()
        WHERE idPersona = :idPersona";
    
    $consulta = $conexion->prepare($consultaSQL);
    $consulta->execute($persona);

  } catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
  }
}

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    
  $idPersona = $_GET['id'];
  $consultaSQL = "SELECT * FROM personas WHERE idPersona =" . $idPersona;

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $persona = $sentencia->fetch(PDO::FETCH_ASSOC);

  if (!$persona) {
    $resultado['error'] = true;
    $resultado['mensaje'] = 'No se ha encontrado el alumno';
  }

} catch(PDOException $error) {
  $resultado['error'] = true;
  $resultado['mensaje'] = $error->getMessage();
}
?>

<?php require "templates/header.php"; ?>

<?php
if ($resultado['error']) {
  ?>
  <div class="container mt-2">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-danger" role="alert">
          <?= $resultado['mensaje'] ?>
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php
if (isset($_POST['submit']) && !$resultado['error']) {
  ?>
  <div class="container mt-2">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-success" role="alert">
          El paciente ha sido actualizado correctamente
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php
if (isset($persona) && $persona) {
  ?>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2 class="mt-4">Editando al paciente <?= escapar($persona['Nombre']) . ' ' . escapar($persona['Apellido'])  ?></h2>
        <hr>
        <form method="post">
          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="<?= escapar($persona['Nombre']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="apellido">Apellido</label>
            <input type="text" name="apellido" id="apellido" value="<?= escapar($persona['Apellido']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= escapar($persona['Email']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="edad">Edad</label>
            <input type="text" name="edad" id="edad" value="<?= escapar($persona['Edad']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="peso">Peso</label>
            <input type="text" name="peso" id="peso" value="<?= escapar($persona['Peso']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="altura">Altura</label>
            <input type="text" name="altura" id="altura" value="<?= escapar($persona['Altura']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <input type="submit" name="submit" class="btn btn-primary" value="Actualizar">
            <a class="btn btn-primary" href="index.php">Regresar al inicio</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php require "templates/footer.php"; ?>