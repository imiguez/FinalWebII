<?php


Class ResumenModel {

    private $db;

    function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=banco;charset=utf8', 'root', '');
    }

    function crearResumen($id, $monto) {
        $query = $this->db->prepare("INSERT INTO resumenes(id_cliente, monto) VALUE(?, ?)");
        $query->execute(array($id, $monto));
    }

    function updateResumen($id, $monto) {
        $query = $this->db->prepare("UPDATE resumenes SET monto=? WHERE id=?");
        $query->execute($monto, $id);
    }

    function getResumenByCliente($id) {
        $query = $this->db->prepare("SELECT * FROM resumenes WHERE id_cliente=?");
        $query->execute(array($id));
        return $query->fetch(PDO::FETCH_OBJ);
    }
}