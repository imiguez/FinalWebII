<?php


Class ApiView {

    function response($data, $status) {
        header("Content-Type: application/json");
        header("HTTP/1.1".$status." ".$this->requestStatus($status));
        echo json_encode($data);
    }

    private function requestStatus($code) {
        $stat = [
            200 => "Ok",
            404 => "Not found",
            500 => "Server error"
        ];
        return (isset($stat[$code])) ? $stat[$code] : $stat[500];
    }

}