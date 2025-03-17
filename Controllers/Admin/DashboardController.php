<?php

namespace Controllers\Admin;

use Controllers\AdminController;

class DashboardController extends AdminController
{
    public function index()
    {
        include __DIR__ . '/../../views/admin/dashboard.php';
    }
}
?>