<?php
namespace Controllers;

use Models\Usuario;
use database\Database;

class AuthController{

    private $usuarioModel;
    private $db; 
    public function __construct()
    {
        $this->usuarioModel=new Usuario();
        $this->db = Database::getInstance()->getConnection();
        
    }

    public function mostrarFormLogin(){

        include __DIR__ . '/../views/auth/login.php';
        if (isset($data['error'])) {
            echo '<p style="color: red;">' . $data['error'] . '</p>';
        }
    }
    private function mostrarFormRegistro($data = []) {
        include __DIR__ . '/../views/auth/register.php';
        if (isset($data['error'])) {
            echo '<p style="color: red;">' . $data['error'] . '</p>';
        }
    }
    public function login(){
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $nombreUsuario=$_POST['username'];
            $password=$_POST['password'];

            $usuario=$this->usuarioModel->obtenerUsuaio($nombreUsuario);
            
            if($usuario && password_verify($password, $usuario['password'])){
                $userAgent = $_SERVER['HTTP_USER_AGENT'];
                $navegadorOS = $this->detectarNavegadorOS($userAgent);
                $_SESSION['usuario_id']=$usuario['id'];
                $_SESSION['rol']=$usuario['rol'];
                $_SESSION['nombre']=$usuario['nombre'];
                $_SESSION['username']=$usuario['username'];

                $stm=$this->db->prepare("INSERT INTO sesiones(usuario_id,ip, navegador, fecha_inicio) VALUES(:usuario_id, :ip, :navegador, NOW())");
                $stm->bindParam(':usuario_id', $usuario['id']);
                $stm->bindParam(':ip', $_SERVER['REMOTE_ADDR']);
                $stm->bindParam(':navegador', $navegadorOS);

                $stm->execute();
                echo "Usuario autenticado";

                $this->redirigirDashboard($usuario['rol']);
            }
            else{
                echo "Usuario o contraseña incorrectos";
                $this->mostrarFormLogin(['error'=>'Usuario o contraseña incorrectos']);
            }
        }else{
            $this->mostrarFormLogin();
        }
    }

    //funcion que detecta el navegador y el sistema operativo
    function detectarNavegadorOS($userAgent) {
        // Detectar navegador
        if (strpos($userAgent, 'Chrome') !== false && strpos($userAgent, 'Chromium') === false) {
            $navegador = 'Chrome';
        } elseif (strpos($userAgent, 'Brave') !== false) {
            $navegador = 'Brave';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            $navegador = 'Firefox';
        } elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) {
            $navegador = 'Opera';
        } elseif (strpos($userAgent, 'Edg') !== false) {
            $navegador = 'Edge';
        } elseif (strpos($userAgent, 'Safari') !== false && strpos($userAgent, 'Chrome') === false) {
            $navegador = 'Safari';
        } else {
            $navegador = 'Desconocido';
        }
    
        // Detectar sistema operativo
        if (strpos($userAgent, 'Windows NT') !== false) {
            $os = 'Windows';
        } elseif (strpos($userAgent, 'Mac OS X') !== false) {
            $os = 'MacOS';
        } elseif (strpos($userAgent, 'Linux') !== false) {
            if (strpos($userAgent, 'Ubuntu') !== false) {
                $os = 'Linux Ubuntu';
            } else {
                $os = 'Linux';
            }
        } elseif (strpos($userAgent, 'Android') !== false) {
            $os = 'Android';
        } elseif (strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false) {
            $os = 'iOS';
        } else {
            $os = 'Desconocido';
        }
    
        return "$navegador / $os";
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $password = $_POST['password'];
            $apellido = $_POST['apellido'];
            $rol = $_POST['rol'];
            $username=$_POST['username'];
            // Verificar si el usuario ya existe
            $usuarioExistente = $this->usuarioModel->obtenerUsuaio($username);
            
            if ($usuarioExistente) {
               
                $this->mostrarFormRegistro(['error' => 'El nombre de usuario ya está en uso']);
                return;
            }
            
            // Encriptar la contraseña
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            // Registrar el usuario
            $resultado = $this->usuarioModel->registrarUsuario($nombre, $apellido, $username, $passwordHash, $rol);
            
            if ($resultado) {
                
                $this->mostrarFormLogin(['mensaje' => 'Usuario registrado correctamente. Por favor inicia sesión.']);
            } else {
                echo "Error al registrar el usuario ".$resultado." Nada";
                $this->mostrarFormRegistro(['error' => 'Error al registrar el usuario ']);
            }
        } else {
            $this->mostrarFormRegistro();
        }
    }
    private function redirigirDashboard(string $rol){
        $basePath='Proyectos/simulacionTrafico/';
        switch ($rol) {
            case 'administrador':
                echo "Tu rol es administrador ".$rol;
                header("Location: admin/dashboard");
                exit();
            
            case 'monitor':
                header("Location: monitor/dashboard");
                exit();

            case 'supervisor':
                header("Location: supervisor/dashboard");
                exit();        
            
            default:
                header("Location: {$basePath}login");
                exit();
        }
    }


    public function logout(){
        session_start();
        if(isset($_SESSION['usuario_id'])){
            $stmt = $this->db->prepare(
                "UPDATE sesiones 
                 SET fecha_fin = NOW(), 
                     duracion = TIMESTAMPDIFF(SECOND, fecha_inicio, NOW()) / 60
                 WHERE usuario_id = :usuario_id 
                   AND fecha_fin IS NULL 
                 ORDER BY fecha_inicio DESC 
                 LIMIT 1"
            );
            $stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
            $stmt->execute();
        }
        session_destroy();
        header("Location: Proyectos/simulacionTrafico/login");
        exit();
    }
    
    public static function estaAutenticado(): bool
    {
        return isset($_SESSION['usuario_id']);
    }

    public static function obtenerRolUsuario(): ?string
    {
        return $_SESSION['rol'] ?? null;
    }
}

?>