<?php

Class SessionHelper {

    function startSession() {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    function isLogged() {
        $this->startSession();
        if (!isset($_SESSION['usuario'])) {
            header("Locatio: ".BASE_URL. "login");
            die();
        }
    }
    function isAdmin() {
        $this->isLogged();
        if (!$_SESSION['permiso']) {
            header("Locatio: ".BASE_URL);
            die();
        }
    }

}