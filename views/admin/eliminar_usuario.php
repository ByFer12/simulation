<?php
//session_start();

use database\Database;
// Verificar que el usuario esté autenticado y que tenga permiso (ej. rol administrador)
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: /Proyectos/simulacionTrafico/?url=login");
    exit();
}

require_once __DIR__ . '/../../database/Database.php';

// Obtener la conexión a la base de datos
$db = Database::getInstance()->getConnection();

// Verificar que se haya pasado el parámetro id
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    
    // Preparar la consulta para eliminar el usuario
    $sql = "DELETE FROM usuarios WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        // Redirigir a la vista de gestión de usuarios
        header("Location: /Proyectos/simulacionTrafico/views/admin/gestion_usuarios.php");

        exit();
    } else {
        echo "Error al eliminar el usuario.";
    }
} else {
    echo "No se especificó el usuario a eliminar.";
}
?>
