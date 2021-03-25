<?php

require_once 'app/Models/ClienteModel.php';
require_once 'app/Models/CuentaModel.php';
require_once 'app/Models/OperacionModel.php';
// require_once 'app/Models/ResumenModel.php';
require_once 'app/Views/ClienteView.php';
require_once 'app/SessionHelper.php';


Class ClienteController {

    private $modelCliente, $modelCuenta, $modelOp, $modelR, $view, $helper;

    function __construct() {
        $this->modelCliente = new ClienteModel();
        $this->modelCuenta = new CuentaModel();
        $this->modelOp = new OperacionModel();
        // $this->modelR = new ResumenModel();
        $this->view = new ClienteView();
        $this->helper = new SessionHelper();
    }
    
    function addCliente() {
        $this->helper->isAdmin();
        $nombre = $_POST['nombre'];
        $dni = $_POST['dni'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $premium = $_POST['premium'];
        if (!empty($dni) $$ isset($nombre) && isset($telefono) && isset($direccion) && isset($premium)) {
            if (!$this->getClienteByDni($dni)) {
                $id_cliente = $this->modelCliente->addCliente($dni, $premium, $nombre, $telefono, $direccion);
                $fecha = new Date();
                $id_cuenta = $this->modelCuenta->addCuenta($fecha, $_POST['nro_cuenta'], $id_cliente, $_POST['tipo_cuenta']);
                $_SESSION['usuario'] = $_POST['nombre'];
                $_SESSION['permiso'] = $_POST['permiso'];
                $_SESSION['id'] = $id_cliente;
                if ($premium)
                    $this->modelOp->crearOperacion(10000, $fecha, 2, $id_cuenta);
            } else
                $this->view->showAddCliente("Ya se han registrado con ese dni.");
        } else
            $this->view->showAddCliente("Algun campo no fue completo.");
    }

    function resumenDelCliente($params = null) {
        $id_cliente = $params[':ID'];
        $cliente = $this->getClienteById($id_cliente);
        if (!$cliente) {
            $this->view->showClientes("No existe el cliente solicitado.");
            die();
        } else if (!$this->getCuentasById($id_cliente)) {
            $this->view->showClientes("El cliente no tiene cuentas.");
            die();
        }
        $cuentas = $this->getCuentasById($id_cliente);
        $operaciones = [];
        $montoActual = 0;
        foreach ($cuentas as $cuenta) {
            $opCuenta = $this->getOperacionesByCuenta($cuenta->id);
            foreach ($opCuenta-> as $op) {
                array_push($operaciones, $op);
                if ($op->tipo_operacion == 1) {
                    $montoActual -= $op->monto;
                } else {
                    $montoActual += $op->monto;
                }
            }
        }
        return [$cliente, $cuentas, $operaciones];
    }

    function getResumenDelCliente($params = null) {
        $id_cliente = $params[':ID'];
        $cliente = $this->modelCliente->getClienteById($id_cliente);
        if (!$cliente) {
            $this->view->showClientes("No existe el cliente solicitado.");
            die();
        } else if (!$this->modelCuenta->getCuentasById($id_cliente)) {
            $this->view->showClientes("El cliente no tiene cuentas.");
            die();
        }
        $cuentas = $this->modelCuenta->getCuentasById($id_cliente);
        $operaciones = [];
        foreach ($cuentas as $cuenta) {
            $opCuenta = $this->modelOp->getOperacionesByCuenta($cuenta->id);
            foreach ($opCuenta as $op) {
                array_push($operaciones, $op);
            }
        }
        $this->view->showResumenDelCliente($cliente, $cuentas, $operaciones);
    }

    function transferenciaRapida() {
        $this->helper->isLogged();
        $dni = $_POST['dni'];
        $monto = $_POST['monto'];
        if (!empty($dni) && $this->modelCliente->getClienteByDni($dni)) {
            $cuentas = $this->modelCuenta->getCuentasById($_SESSION['id']);
            foreach ($cuentas as $cuenta) {
                $opCuenta = $this->modelOp->getOperacionesByCuenta($cuenta->id);
                $montoDeCuenta = 0;
                foreach ($opCuenta as $op) {
                    if ($op->tipo_cuenta == 1)
                        $montoDeCuenta -= $op->monto;
                    else
                        $montoDeCuenta += $op->monto;
                }
                if ($montoDeCuenta > $monto) {
                    $fecha = new Date();
                    $this->modelOp->crearOperacion($monto, $fecha, 1, $_SESSION['id']);
                    $id_destinatario = $this->modelCliente->getClienteByDni($dni)->id;
                    $this->modelOp->crearOperacion($monto, $fecha, 2, $id);
                    $this->view->showTransferencia("Se ha concretado la transferencia.");
                    die();
                }
            }
            $this->view->showTransferencia("Error en la transferencia, intente denuevo.");
        }
    }
}