<?php 
    // Require Globals
    require_once(dirname(__DIR__, 1) . "/bootstrap.php");

    // Require Config
    require_once(PROJECT_ROOT . "config.php");

    // Libraries
    require_once(PROJECT_ROOT . "lib/errors.php");
    require_once(PROJECT_ROOT . "lib/rest.php");

    // Require Models
    require_once(PROJECT_ROOT . "models/user.php");

    REST\setHeaders();

    try {
        $model = new UserModel();
        // REST\sendData($model->create([
        //     [
        //         "svvid" => "yoru",
        //         "name" => "yoru",
        //         "type" => 3,
        //         "pwd" => "skye",
        //     ]
        // ]));
        REST\sendData($model->update([
            "yoru"
        ], [
            [
                "name" => "God"
            ]
        ]));
        REST\sendData($model->read([]));
    } catch (Error\API $e) {
        REST\sendError($e->getCode(), $e->getMessage());
    }

?>