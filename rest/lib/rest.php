<?php
    namespace REST;

    function setHeaders() {
        header("Content-Type: application/json");
    }

    function sendData($data) {
        http_response_code(200);
        try {
            $dataJSON = json_encode($data);
            echo $dataJSON;
        } catch (Exception $e) {
            sendError(
                500,
                "Could not format server response, encoder said: " . $e->getMessage()
            );
        }
    }

    function sendError($code, $msg) {
        http_response_code($code);
        echo json_encode([
            "err" => $msg
        ]);
    }
?>