<?php


Class OperacionModel {

    private $db;

    function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=banco;charset=utf8', 'root', '');
    }

    function crearOperacion($monto, $fecha, $tipo_operacion, $id_cuenta) {
        $query = $this->db->prepare("INSERT INTO operaciones(monto, fecha, tipo_operacion, id_cuenta) VALUE(?, ?, ?, ?)");
        $query->execute(array($monto, $fecha, $tipo_operacion, $id_cuenta));
    }

}

