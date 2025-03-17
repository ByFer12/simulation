<?php
namespace Controllers;
class SupervisorController extends BaseController{

    public function __construct()
    {
        if(!AuthController::estaAutenticado() || AuthController::obtenerRolUsuario() !== 'supervisor'){
            header("Location: /simulacionTrafico/login");
            exit();
        }
    }
}

?>