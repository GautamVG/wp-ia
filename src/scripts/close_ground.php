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
    if (isset($_POST["ground-id"]) &&
        isset($_POST['close-time']) &&
        isset($_POST['open-time'])
    ) {
        try {
            $db = DB\connect();
            $query = "UPDATE `ground` SET `close_time` = :closeTime, `open_time` = :openTime WHERE `id` = :groundId;";
            $stmt = $db->prepare($query);
            print_r($_POST);
            $closeTime = date_create_from_format("H:i", $_POST['close-time'])->format("H:i:s");
            $openTime = date_create_from_format("H:i", $_POST['open-time'])->format("H:i:s");
            print_r($closeTime);
            print_r($openTime);


            $stmt->bindParam(":closeTime", $closeTime);
            $stmt->bindParam(":openTime", $openTime);
            $stmt->bindParam(":groundId", $_POST['ground-id']);
            $stmt->execute();
            Redirect\toGroundsPage();
        } catch (Exception $err) {
            // Redirect\toErrorPage($err->getMessage());
        }
    } else {
        Redirect\toErrorPage("Incorrect request parameters");
    }
?>