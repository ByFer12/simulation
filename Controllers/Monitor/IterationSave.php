<?php
// Configuración de errores
ini_set('display_errors', 1); // Mostrar errores
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');
error_reporting(E_ALL);

// Iniciar sesión y configurar cabeceras
session_start();
header('Content-Type: application/json');

// Incluir clase de base de datos
use Database\Database;
require_once __DIR__ . '/../../database/Database.php';

// Verificar que la sesión esté iniciada y que el rol sea "monitor"
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'monitor') {
    http_response_code(403);
    echo json_encode(['error' => 'Acceso denegado. Se requiere rol monitor.']);
    exit;
}

// Recibir y decodificar el JSON
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos JSON inválidos.']);
    exit;
}

try {
    // Obtener la conexión a la base de datos
    $db = Database::getInstance()->getConnection();
    $db->beginTransaction();

    // Insertar nueva iteración
    $stmt = $db->prepare("INSERT INTO iteracion (simulacion_id, comentario) VALUES (:simulacion_id, :comentario)");
    $stmt->bindValue(':simulacion_id', $data['simulacion_id']);
    $stmt->bindValue(':comentario', $data['comentario']);
    $stmt->execute();
    $iteracion_id = $db->lastInsertId();

    // Insertar los semáforos de esta iteración
    $stmt = $db->prepare("INSERT INTO iteracion_semaforo (iteracion_id, semaforo_id, tiempo_rojo, tiempo_amarillo, tiempo_verde) 
                        VALUES (:iteracion_id, :semaforo_id, :rojo, :amarillo, :verde)");

    foreach ($data['semaforos'] as $semaforo) {
        $stmt->bindValue(':iteracion_id', $iteracion_id);
        $stmt->bindValue(':semaforo_id', $semaforo['id']);
        $stmt->bindValue(':rojo', $semaforo['red']);
        $stmt->bindValue(':amarillo', $semaforo['yellow']);
        $stmt->bindValue(':verde', $semaforo['green']);
        $stmt->execute();
    }

    $db->commit();
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    http_response_code(500); // Código de error interno del servidor
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
    exit;
}
?>