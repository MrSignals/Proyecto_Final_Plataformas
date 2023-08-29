<?php
include 'funciones.php';

$error = false;
$config = include 'config.php';

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

  if (isset($_POST['apellido'])) {
    $consultaSQL = "SELECT * FROM personas WHERE Apellido LIKE '%" . $_POST['apellido'] . "%'";
  } else {
    $consultaSQL = "SELECT * FROM personas";
  }

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $persona= $sentencia->fetchAll();

} catch(PDOException $error) {
  $error= $error->getMessage();
}
$titulo = isset($_POST['apellido']) ? 'Lista de personas (' . $_POST['apellido'] . ')' : 'Lista de personas';
?>




<?php include "templates/header.php";?>

<?php
if ($error) {
  ?>
  <div class="container mt-2">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-danger" role="alert">
          <?= $error ?>
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
      <a href="crear.php"  class="btn btn-primary mt-4">Agendar personas</a>
      <hr>
      <form method="POST" class="form-inline">
        <div class="form-group mr-3">
            <input type="text" id="apellido" name="apellido" placeholder="Buscar por apellido" class="form-control">
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Ver resultados</button>
      </form>
    </div>
  </div>
</div>


<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h2 class="mt-3">Lista de Personas</h2>
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Email</th>
            <th>Edad</th>
            <th>Peso</th>
            <th>Altura</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($persona && $sentencia->rowCount() > 0) {
            foreach ($persona as $fila) {
              ?>
              <tr>
                <td><?php echo escapar($fila["idPersona"]); ?></td>
                <td><?php echo escapar($fila["Nombre"]); ?></td>
                <td><?php echo escapar($fila["Apellido"]); ?></td>
                <td><?php echo escapar($fila["Email"]); ?></td>
                <td><?php echo escapar($fila["Edad"]); ?></td>
                <td><?php echo escapar($fila["Peso"]); ?></td>
                <td><?php echo escapar($fila["Altura"]); ?></td>
                <td>
                    <a href="<?= 'borrar.php?id=' . escapar($fila["idPersona"]) ?>">🗑️Borrar</a>
                    <a href="<?= 'editar.php?id=' . escapar($fila["idPersona"]) ?>">✏️Editar</a>
                </td>
              </tr>
              <?php
            }
          }
          ?>
        <tbody>
      </table>
    </div>
  </div>
</div>
<?php include "templates/footer.php";?>