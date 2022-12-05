<?php include_once(dirname(__DIR__) . "/bootstrap.php") ?>

<?php 
    include_once(APP_ROOT . "lib/db.php");
    include_once(APP_ROOT . "lib/redirect.php");
?>

<?php 
    session_start();
    if (!(isset($_SESSION) && isset($_SESSION['userData']) && $_SESSION['userData']['user_type_label'] == "student")) Redirect\toLoginPage();
?>

<?php 
    if (isset($_GET["booking-id"])) {
        try {
            $db = DB\connect();
            $query = "DELETE FROM `booking` WHERE `id` = :bookingId";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":bookingId", $_GET["booking-id"]);
            $stmt->execute();

            Redirect\toBookingsPage();
        } catch (Exception $err) {
            Redirect\toErrorPage($err->getMessage());
        }
    } else {
        Redirect\toErrorPage("Incorrect request parameters");
    }
?>