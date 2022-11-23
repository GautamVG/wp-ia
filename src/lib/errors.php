<?php 
    namespace Error;
    
    class API extends \Exception {
        function __construct($message, $statusCode) {
            parent::__construct($message, $statusCode);
        }
    }

    class Server extends API {
        function __construct($message, $statusCode = 500) {
            parent::__construct($message, $statusCode);
        }
    }

    class Client extends API {
        function __construct($message, $statusCode = 400) {
            parent::__construct($message, $statusCode);
        }
    }
?>