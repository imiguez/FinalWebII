<?php

require_once 'api/ApiView.php';


Class ApiController {

    protected $model, $view;
    private $data;

    function __construct() {
        $this->view = new ApiView();
        $this->data = file_get_contents("php://input");
    }

    function getData() {
        return json_decode($this->data);
    }

}