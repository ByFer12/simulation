<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');
error_reporting(E_ALL);
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

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

    // Iniciar transacción
    $db->beginTransaction();

    // Insertar registro de simulación
    $stmtSim = $db->prepare("INSERT INTO simulation (user_id) VALUES (:user_id)");
    $stmtSim->execute(['user_id' => $_SESSION['usuario_id']]);
    $simulationId = $db->lastInsertId();

    // Insertar datos de vehículos
    if (isset($data['vehicles']) && is_array($data['vehicles'])) {
        $stmtVehicle = $db->prepare("INSERT INTO vehicles (simulation_id, x, y, direction, type, speed) VALUES (:simulation_id, :x, :y, :direction, :type, :speed)");
        foreach ($data['vehicles'] as $vehicle) {
            $stmtVehicle->execute([
                'simulation_id' => $simulationId,
                'x'             => $vehicle['x'],
                'y'             => $vehicle['y'],
                'direction'     => $vehicle['direction'],
                'type'          => $vehicle['type'],
                'speed'         => $vehicle['speed']
            ]);
        }
    } else {
        throw new Exception("No se encontraron datos de vehículos.");
    }

    // Insertar datos de semáforos
    if (isset($data['semaphores']) && is_array($data['semaphores'])) {
        $stmtSemaphore = $db->prepare("INSERT INTO semaphores (simulation_id, json_id, red, green, yellow) VALUES (:simulation_id, :json_id, :red, :green, :yellow)");
        foreach ($data['semaphores'] as $semaphore) {
            $stmtSemaphore->execute([
                'simulation_id' => $simulationId,
                'json_id'       => $semaphore['id'],
                'red'           => $semaphore['timings']['red'],
                'green'         => $semaphore['timings']['green'],
                'yellow'        => $semaphore['timings']['yellow']
            ]);
        }
    } else {
        throw new Exception("No se encontraron datos de semáforos.");
    }

    // Confirmar transacción
    $db->commit();
    echo json_encode(['success' => true, 'simulation_id' => $simulationId]);

} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    http_response_code(500);
    echo json_encode(['error' => 'Error al guardar la simulación: ' . $e->getMessage()]);
}
?>
