<?php

namespace App\Models;

use App\Models\DB;

class Usuarios
{
    private $bd;

    public function __construct()
    {
        $this->bd = DB::getInstance();
    }

    public function listarUsuarios()
    {
        $sql = "SELECT * FROM usuarios";
        $resultado = $this->bd->query($sql);

        $usuarios = [];
        while ($fila = $this->bd->LeeRegistro($resultado)) {
            $usuarios[] = $fila;
        }
        return $usuarios;
    }
    
    public function registraUsuario(array $datos)
    {
        $sql = "INSERT INTO usuarios (
            usuario,
            password,
            rol
        ) VALUES (
            '{$this->bd->escape($datos['usuario'])}',
            '{$this->bd->escape($datos['password'])}',
            '{$this->bd->escape($datos['rol'])}'
        )";

        $this->bd->query($sql);
    }

    public function obtenerUsuarioPorId($id)
    {
        $sql = "SELECT * FROM usuarios WHERE id = '{$this->bd->escape($id)}'";
        $resultado = $this->bd->query($sql);
        return $this->bd->LeeRegistro($resultado);
    }

    public function actualizarUsuario($id, array $datos)
    {
        $sql = "UPDATE usuarios SET
            usuario = '{$this->bd->escape($datos['usuario'])}',
            password = '{$this->bd->escape($datos['password'])}',
            rol = '{$this->bd->escape($datos['rol'])}'
            WHERE id = '{$this->bd->escape($id)}'";

        $this->bd->query($sql);
    }

    public function eliminarUsuario($id)
    {
        $sql = "DELETE FROM usuarios WHERE id = '{$this->bd->escape($id)}'";
        $this->bd->query($sql);
    }
}