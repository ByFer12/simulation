<?php

namespace Controllers\Supervisor;

use Controllers\SupervisorController;

class DashboardController extends SupervisorController
{
    public function index()
    {
        include __DIR__ . '/../../views/supervisor/dashboard.php';
    }
}
?>