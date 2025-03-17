<?php

namespace Models;

use database\Database;

class Usuario{

    private $db;
    public function __construct()
    {
        $this->db=Database::getInstance()->getConnection();

    }

    public function obtenerUsuaio(string $username): ?array{

        $stm=$this->db->prepare("SELECT * FROM usuarios WHERE username = :username");
        $stm->bindParam(':username', $username);
        $stm->execute();
        $usuario=$stm->fetch(\PDO::FETCH_ASSOC);

        return $usuario ? $usuario:null;
    }

    public function registrarUsuario(string $nombre, string $apellido, string $username,string $password, string $rol): bool {
        try {
            $stm = $this->db->prepare("INSERT INTO usuarios (apellido, fecha_registro, nombre , password,  rol, username) VALUES (:apellido, NOW(), :nombre, :password, :rol, :username)");
            $stm->bindParam(':apellido', $apellido);
            $stm->bindParam(':nombre', $nombre);
            $stm->bindParam(':password', $password);
            $stm->bindParam(':rol', $rol);
            $stm->bindParam(':username', $username);
            
            return $stm->execute();
        } catch (\PDOException $e) {
            echo "Error en el metodo RegistrarUsuario en model: ".$e->getMessage();
            return false;
        }
    }


}