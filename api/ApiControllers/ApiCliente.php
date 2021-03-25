<?php

require_once 'api/ApiControllers/ApiController.php';
require_once 'app/Models/ClienteModel.php';


Class ApiCliente extends ApiController {

    function __construct() {
        parent::__construct();
        $this->model = new ClienteModel();
    }

    function getCliente($params = null) {
        $id = $params[':ID'];
        $cliente = $this->model->getClienteById($id);
        if ($cliente) 
            $this->view->response($cliente, 200);
        else
            $this->view->response("El cliente no existe", 404);
    }

    function editCliente($params = null) {
        $id = $params[':ID'];
        $datos = $this->getData();
        $this->model->editCliente($datos->dni, $datos->premium, $datos->nombre, $datos->telefono, $datos->direccion, $id);
    }
}