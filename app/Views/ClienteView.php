<?php


// require_once '' require de la libreri smarty

Class ClienteView {

    private $smarty;

    function __construct() {
        $this->smarty = new Smarty();
    }

    function showResumenDelCliente($cliente, $cuentas, $operaciones) {
        $this->smarty->assign("cliente", $cliente);
        $this->smarty->assign("cuentas", $cuentas);
        $this->smarty->assign("operaciones", $operaciones);
        $this->smarty->display("resumenCliente.tpl");
    }

}