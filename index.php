<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
 
session_start();

spl_autoload_register(function ($className) {
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $baseDir = __DIR__;
    $filePath = $baseDir . DIRECTORY_SEPARATOR . $className . '.php';

   //echo "Intentando cargar: $filePath<br>";

    if (file_exists($filePath)) {
        require_once $filePath;
      // ; echo "¡Archivo encontrado y cargado!<br>";
        return true;
    } else {
       // echo "¡Archivo no encontrado!<br>";
    }
    return false;
});

 
$url = $_GET['url'] ?? '';
$basePath = '/Proyectos/simulacionTrafico/';

try {
    // Instancia del controlador de autenticación
    $authController = new \Controllers\AuthController();
    
    if ($url === 'login' || $url === '') {
        $authController->login();
    } elseif ($url === 'logout') {
        $authController->logout();
    }elseif ($url === 'register') {
        $authController->register();
    }elseif ($url === 'admin/dashboard/gestion_usuarios') {
        include __DIR__ . '/views/admin/gestion_usuarios.php';
    } 
    elseif (strpos($url, 'admin/dashboard') === 0) {
        // Asegúrate de que la ruta de clase coincida exactamente con tu namespace y estructura
        $adminDashboardController = new Controllers\Admin\DashboardController();
        $adminDashboardController->index();
    } elseif (strpos($url, 'monitor/dashboard') === 0) {
        $adminDashboardController = new \Controllers\Admin\DashboardController();
        $monitorDashboardController->index();
    } elseif (strpos($url, 'supervisor/dashboard') === 0) {
        $supervisorDashboardController = new \Controllers\Supervisor\DashboardController();
        $supervisorDashboardController->index();
    } else {
        $authController->mostrarFormLogin();
    }
} catch (Exception $e) {
    // Log y mostrar el error
    error_log("Error: " . $e->getMessage());
    echo "Error de aplicación: " . $e->getMessage() . "<br>";
    echo "En el archivo: " . $e->getFile() . "<br>";
    echo "En la línea: " . $e->getLine() . "<br>";
}
?>