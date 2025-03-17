<?php

return [
    'host' => 'localhost',
    'dbname' => 'monitoreoTrafico', 
    'user' => 'root', // Reemplaza con tu usuario de la base de datos
    'password' => '', // Reemplaza con tu contraseña de la base de datos
    'charset' => 'utf8',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];

?>