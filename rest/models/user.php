<?php 
    // Require Globals
    require_once(dirname(__DIR__, 1) . "/bootstrap.php");
    // Require Models
    require_once(PROJECT_ROOT . "models/database.php");

    class UserModel extends DatabaseModel {
        protected function getTableName() {
            return "user";
        }

        protected function getPrimaryKey() {
            return "svvid";
        }

        protected function getColumnNames() {
            return ["name", "svvid", "pwd", "type"];
        }
    }
?>
