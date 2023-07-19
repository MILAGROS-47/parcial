<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Procesar registro
  $descripcion = $_POST['descripcion'];
  $monto = $_POST['monto'];
  $tipoIngreso = $_POST['tipoIngreso'];

  // Realizar validaciones (puedes agregar más validaciones según tus necesidades)
  if (empty($descripcion) || empty($monto) || empty($tipoIngreso)) {
    http_response_code(400);
    echo json_encode(array('error' => 'Todos los campos son requeridos.'));
    exit();
  }

  // Guardar transacción en la base de datos
  $servername = "localhost:3306";
  $username = "root";
  $password = "";
  $dbname = "transaccion";

  // Crear una conexión a la base de datos
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Verificar si hay errores en la conexión
  if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(array('error' => 'Error en la conexión a la base de datos.'));
    exit();
  }

  // Preparar la consulta SQL para insertar la transacción
  $stmt = $conn->prepare("INSERT INTO transaccion (FechaHora, descripcion, monto, tipoIngreso) VALUES (NOW(), ?, ?, ?)");
  $stmt->bind_param("sss", $descripcion, $monto, $tipoIngreso);

  // Ejecutar la consulta SQL
  if ($stmt->execute()) {
    // Éxito al guardar la transacción en la base de datos
    echo json_encode(array('success' => true));
  } else {
    // Error al guardar la transacción en la base de datos
    http_response_code(500);
    echo json_encode(array('error' => 'Error al guardar la transacción en la base de datos.'));
  }

  // Cerrar la conexión y liberar recursos
  $stmt->close();
  $conn->close();
}
?>
