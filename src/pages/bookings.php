<?php include_once(dirname(__DIR__) . "/bootstrap.php") ?>

<?php 
    include_once(APP_ROOT . "lib/db.php");
    include_once(APP_ROOT . "lib/redirect.php");
?>

<?php 
    session_start();
    if (!(isset($_SESSION) && isset($_SESSION['userData']))) Redirect\toLoginPage();
?>

<?php 
    $db = DB\connect();
    try {
        date_default_timezone_set("Indian/Maldives");
        $date = date("Y/m/d");
        $time = $_SESSION['userData']['user_type_label'] == "student" ? date("H:i:s") : "00:00:00";
        if (isset($_POST['filter'])) $date = $_POST['date'];
        $query = "SELECT `booking`.`id`, `start_time`, `end_time`, `user`.`name` as `booker_name`, `user`.`svvid` as `booker_svvid`, `ground`.`name` as `ground_name`, `zone`.`name` as `zone_name`, `zone`.`is_primary` as `is_zone_primary` FROM `booking`, `user`, `zone`, `ground` WHERE `date` = :date AND `start_time` > :time AND `booking`.`user_svvid` = `user`.`svvid` AND `booking`.`zone_id` = `zone`.`id` AND `zone`.`ground_id` = `ground`.`id` ORDER BY `start_time`";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":date", $date);
        $stmt->bindParam(":time", $time);
        $stmt->execute();
        $slots = $stmt->fetchAll();
    } catch (Exception $err) {
        Redirect\toErrorPage($err->getMessage());
    }

    function formatTimeInstant($timeStr) {
        return date("H:i", strtotime($timeStr));
    }

    function formatTimePeriod($timeStr) {
        return strtoupper(date("a", strtotime($timeStr)));
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once(APP_ROOT. "templates/head_base.php"); ?>
    <link rel="stylesheet" href="/public/styles/bookings.css">
    <title>Home | ZSchedule</title>
</head>
<body>
    <?php include_once(APP_ROOT. "templates/navbar.php"); ?>
    <main class="mobile-container">
        <h1>Today's schedule</h1>
        <h3> <?php echo $date ?> </h3>
        <div class="slots">
            <?php 
                if (count($slots) == 0) {
                    ?>
                        <h2>No slots booked</h2>
                    <?php
                } else {
                    foreach ($slots as $slot) {
                        ?> 
                            <div class="slot">
                                <div class="top">
                                    <div class="time-details">
                                        <i class="ph-clock"></i>
                                        <div class="start time">
                                            <h3 class="instant">
                                                <?php echo formatTimeInstant($slot['start_time']) ?>
                                            </h3>
                                            <h4 class="period">
                                                <?php echo formatTimePeriod($slot['start_time']) ?>
                                            </h4>
                                        </div>
                                        <div class="end time">
                                            <h3 class="instant">
                                                <?php echo formatTimeInstant($slot['end_time']) ?>
                                            </h3>
                                            <h4 class="period">
                                                <?php echo formatTimePeriod($slot['end_time']) ?>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="booking-details">
                                        <p>Booked by</p>
                                        <h3>
                                            <?php echo $slot['booker_name'] ?>
                                        </h3>
                                    </div>
                                </div>
                                <div class="bottom">
                                    <div class="ground-name">
                                        <i class="ph-map-pin"></i>
                                        <p>
                                            <?php 
                                                if ($slot['is_zone_primary'])
                                                    echo $slot['ground_name'];
                                                else
                                                    echo($slot['ground_name'] . " (" . $slot['zone_name'] . ")");
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <?php 
                                    if ($slot['booker_svvid'] == $_SESSION['userData']['svvid']) {
                                        ?>
                                            <a 
                                                class="delete-btn"
                                                href=<?php 
                                                    echo("/scripts/delete_booking.php?booking-id=" . $slot["id"]);
                                                ?>
                                            >
                                                <i class="ph-trash"></i>
                                            </a>
                                        <?php
                                    }
                                ?>
                            </div>
                        <?php
                    }
                }
            ?>
        </div>
        <?php 
            if ($_SESSION['userData']['user_type_label'] == "student") {
                ?>
                    <a href="./new_booking.php" class="book-btn">Book a slot</a>
                <?php
            } else {
                ?>
                    <form method="POST">
                        <input type="date" name="date">
                        <input type="submit" name="filter" value="View">
                    </form>
                <?php
            }
        ?>
    </main>
</body>
</html>