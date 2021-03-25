<?php


Class ClienteModel {

    private $db;

    function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=banco;charset=utf8', 'root', '');
    }


    function addCliente($dni, $premium, $nombre, $telefono, $direccion) {
        $query = $this->db->prepare("INSERT INTO clientes(dni, premium, nombre, telefono, direccion) VALUE(?, ?, ?, ?, ?)");
        $query->execute(array($dni, $premium, $nombre, $telefono, $direccion));
        return $query->lastInsertedId();
    }

    function getClienteByDni($dni) {
        $query = $this->db->prepare("SELECT * FROM clientes WHERE dni=?");
        $query->execute([$dni]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    function getClienteById($id) {
        $query = $this->db->prepare("SELECT * FROM clientes WHERE id=?");
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    function editCliente($dni, $premium, $nombre, $telefono, $direccion, $id) {
        $query = $this->db->prepare("UPDATE clientes SET dni=?, premium=?, nombre=?, telefono=?, direccion=? WHERE id=?");
        $query->execute(array($dni, $premium, $nombre, $telefono, $direccion, $id));
    }
}