<?php

namespace Controllers\Monitor;

use Controllers\MonitorController;

class DashboardController extends MonitorController
{
    public function index()
    {
        include __DIR__ . '/../../views/monitor/dashboard.php';
    }
}
?>