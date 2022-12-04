<?php include_once(dirname(__DIR__) . "/bootstrap.php") ?>

<?php 
    include_once(APP_ROOT . "lib/db.php");
    include_once(APP_ROOT . "lib/redirect.php");
?>

<?php 
    session_start();
    if (!(isset($_SESSION) && isset($_SESSION['userData']) && $_SESSION['userData']['user_type_label'] != "student")) Redirect\toLoginPage();
?>

<?php 
    if (isset($_POST["ground-id"])) {
        try {
            $db = DB\connect();
            $query = "UPDATE `ground` SET `close_time` = NULL, `open_time` = NULL WHERE `id` = :groundId;";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":groundId", $_POST['ground-id']);
            $stmt->execute();
            Redirect\toGroundsPage();
        } catch (Exception $err) {
            Redirect\toErrorPage($err->getMessage());
        }
    }
?>