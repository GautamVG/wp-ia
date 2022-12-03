<?php include_once(dirname(__DIR__) . "/bootstrap.php") ?>

<?php 
    include_once(APP_ROOT . "lib/db.php");
    include_once(APP_ROOT . "lib/redirect.php");
?>

<?php 
    // Check if session is valid, otherwise redirect
    session_start();
    if (!(isset($_SESSION) && isset($_SESSION['svvid']))) Redirect\toLoginPage();
?>

<?php 
    // Fetch details required for booking from database
    $grounds = [];
    $db = DB\connect();
    try {
        $results = $db->query("SELECT * FROM `ground`;");
        if ($results) $grounds = $results->fetchAll();
    } catch (Exception $err) {
        Redirect\toErrorPage($err->getMessage());
    }
?>

<?php 
    // Check if this is a request to create a booking
    if (isset($_GET['book'])) {
        // Make a booking
        // And redirect to home page
        if (
            isset($_GET['groundID']) && 
            isset($_GET['startTime']) &&
            isset($_GET['endTime'])
        ) {
            $groundID = $_GET['groundID'];
            $startTime = date_create_from_format("H:i", $_GET['startTime'])->format("H:i:s");
            $endTime = date_create_from_format("H:i", $_GET['endTime'])->format("H:i:s");
            $date = date("Y:m:d");
            $svvid = $_SESSION['svvid'];

            makeBooking($groundID, $date, $startTime, $endTime, $svvid);
            Redirect\toHomePage();
        } else {
            $errMsg = "Please fill all details";
        }
    }

    function makeBooking($groundID, $date, $startTime, $endTime, $svvid) {
        $db = DB\connect();
        try {
            $query = "INSERT INTO `booking` (`date`, `start_time`, `end_time`, `ground`, `user_svvid`) VALUES ('$date', '$startTime', '$endTime', '$groundID', '$svvid');";
            $db->exec($query);
        } catch (Exception $err) {
            Redirect\toErrorPage($err->getMessage());
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/phosphor-icons"></script>
    <title>Book a slot | ZSchedule</title>
    <style>
        <?php 
            include_once(APP_ROOT . "styles/base.css");
            include_once(APP_ROOT . "styles/book.css");
        ?>
    </style>
</head>
<body>
    <h1>New Booking</h1>
    <form method="GET">
        <label class="form-label">
            Select a ground
        </label>
        <?php 
            if (count($grounds) == 0) {
                ?> 
                    <p>No grounds available right now, please try again later</p>
                <?php
            } else {
                foreach ($grounds as $ground) {
                    ?>
                        <div class="radio-btn">
                            <input
                                type="radio"
                                id=<?php echo $ground['id'] ?>
                                name="groundID"
                                value=<?php echo $ground['id'] ?>
                            />
                            <label 
                                for=<?php echo $ground['id'] ?>
                            >
                                <?php echo $ground['name'] ?>
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
        <input type="submit" value="Book" name="book" />
    </form>
    <script>
        <?php 
            if (isset($_GET['startTime']) && $_GET['startTime'] != "")  {
                ?> 
                    document.forms[0]['startTime'].value = '<?php echo $_GET['startTime'] ?>'
                <?php
            }
            if (isset($_GET['endTime']) && $_GET['endTime'] != "")  {
                ?> 
                    document.forms[0]['endTime'].value = '<?php echo $_GET['endTime'] ?>'
                <?php
            }
        ?>
    </script>
</body>
</html>