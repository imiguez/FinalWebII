<?php


Class CuentaModel {

    private $db;

    function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=banco;charset=utf8', 'root', '');
    }

    function addCuenta($fecha, $nro_cuenta, $cliente, $tipo_cuenta) {
        $query = $this->db->prepare("INSERT INTO cuentas(fecha_alta, nro_cuenta, id_cliente, tipo_cuenta) VALUE(?, ?, ?, ?)");
        $query->execute(array($fecha, $nro_cuenta, $cliente, $tipo_cuenta));
        return $query->lastInsertedId();
    }

    function getCuentasById($id) {
        $query = $this->db->prepare("SELECT * FROM cuentas WHERE id_cliente=?");
        $query->execute(array($id));
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
}