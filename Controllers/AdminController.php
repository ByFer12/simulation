<?php

namespace Controllers;

use Controllers\AuthController;
class  AdminController extends BaseController{

    public function __construct()
    {
        if(!AuthController::estaAutenticado() || AuthController::obtenerRolUsuario() !== 'administrador'){
            header("Location: /simulacionTrafico/login");
            exit();
        }
    }
}
?>