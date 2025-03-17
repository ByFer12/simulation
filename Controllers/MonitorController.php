<?php
namespace Controllers;

class MonitorController extends BaseController{

    public function __construct()
    {
        if(!AuthController::estaAutenticado() || AuthController::obtenerRolUsuario() !== 'monitor'){
            header("Location: /simulacionTrafico/login");
            exit();
        }
    }

 
}
?>