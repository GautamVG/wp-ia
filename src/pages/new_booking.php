<?php include_once(dirname(__DIR__) . "/bootstrap.php") ?>

<?php 
    include_once(APP_ROOT . "lib/db.php");
    include_once(APP_ROOT . "lib/redirect.php");
?>

<?php 
    // Check if session is valid, otherwise redirect
    session_start();
    if (!(isset($_SESSION) && isset($_SESSION['userData']))) Redirect\toLoginPage();
?>

<?php 
    // Check if this is a request to create a booking
    if (isset($_POST['submit'])) {
        // Make a booking
        // And redirect to home page
        if (
            isset($_POST['zoneId']) && 
            isset($_POST['startTime']) &&
            isset($_POST['endTime'])
        ) {
            $zoneId = $_POST['zoneId'];
            $startTime = date_create_from_format("H:i", $_POST['startTime'])->format("H:i:s");
            $endTime = date_create_from_format("H:i", $_POST['endTime'])->format("H:i:s");
            $date = date("Y:m:d");
            $svvid = $_SESSION['userData']['svvid'];

            makeBooking($zoneId, $date, $startTime, $endTime, $svvid);
            Redirect\toBookingsPage();
        } else {
            $errMsg = "Please fill all details";
        }
    }

    function makeBooking($zoneId, $date, $startTime, $endTime, $svvid) {
        $db = DB\connect();
        try {
            $query = "INSERT INTO `booking` (`date`, `start_time`, `end_time`, `zone_id`, `user_svvid`) VALUES (:date, :startTime, :endTime, :zoneId, :svvid);";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":date", $date);
            $stmt->bindParam(":startTime", $startTime);
            $stmt->bindParam(":endTime", $endTime);
            $stmt->bindParam(":zoneId", $zoneId);
            $stmt->bindParam(":svvid", $svvid);
            $stmt->execute();
        } catch (Exception $err) {
            Redirect\toErrorPage($err->getMessage());
        }
    }
?>

<?php 
    // Fetch details required for booking from database
    $zones = [];
    $db = DB\connect();
    try {
        $query = "SELECT `zone`.*, `ground`.`name` as `ground_name`, `ground`.`close_time`, `ground`.`open_time` FROM `zone`, `ground` WHERE (`zone`.`is_primary` = false OR `zone`.`is_multi_zonal` = false) AND `ground`.`id` = `zone`.`ground_id`";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $zones = $stmt->fetchAll();
    } catch (Exception $err) {
        Redirect\toErrorPage($err->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once(APP_ROOT. "templates/head_base.php"); ?>
    <link rel="stylesheet" href="/public/styles/new_booking.css">
    <title>Book a slot | ZSchedule</title>
</head>
<body>
    <?php include_once(APP_ROOT. "templates/navbar.php"); ?>
    <div class="mobile-container">
        <h1>New Booking</h1>
        <form method="POST">
            <label class="form-label">
                Select a ground
            </label>
            <?php 
                if (count($zones) == 0) {
                    ?> 
                        <p>No grounds available right now, please try again later</p>
                    <?php
                } else {
                    foreach ($zones as $zone) {
                        ?>
                            <div class="radio-btn">
                                <input
                                    type="radio"
                                    id=<?php echo $zone['id'] ?>
                                    name="zoneId"
                                    value=<?php echo $zone['id'] ?>
                                />
                                <label 
                                    for=<?php echo $zone['id'] ?>
                                >
                                    <?php 
                                        if ($zone['is_primary'])
                                            echo (
                                                $zone['ground_name'] . 
                                                ($zone['close_time'] == null || $zone['open_time'] == null ?
                                                    " " :
                                                    " (Closed from " . $zone['close_time'] . " to " . $zone['open_time'] . ")")
                                            );
                                        else 
                                            echo (
                                                $zone['ground_name'] . " => " . $zone['name'] .
                                                ($zone['close_time'] == null || $zone['open_time'] == null ?
                                                    " " :
                                                    " (Closed from " . $zone['close_time'] . " to " . $zone['open_time'] . ")")
                                            );
                                    ?>
                                </label>
                            </div>
                        <?php
                    }
                }
            ?>
            <label class="form-label">Pick a start time</label>
            <input 
                type="time" 
                name="startTime" 
            />
            <label class="form-label">Pick an end time</label>
            <input 
                type="time" 
                name="endTime" 
            />
            <input type="submit" name="submit" />
        </form>
    </div>
</body>
</html>