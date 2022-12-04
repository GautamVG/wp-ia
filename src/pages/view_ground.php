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
    if (isset($_GET["g"])) {
        try {
            $db = DB\connect();
            $query = "SELECT `ground`.*, `zone`.*, `user`.`name` as `manager_name` FROM `ground`, `zone`, `user` WHERE `ground`.`id` = :groundId AND `zone`.`ground_id` = `ground`.`id` AND `user`.`svvid` = `ground`.`manager_svvid` AND `zone`.`is_primary` = true;";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":groundId", $_GET["g"]);
            $stmt->execute();
            $ground = $stmt->fetchAll()[0];

            $query = "SELECT * FROM `zone` WHERE `zone`.`ground_id` = :groundId AND `zone`.`is_primary` = false";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":groundId", $_GET["g"]);
            $stmt->execute();
            $zones = $stmt->fetchAll();
        } catch (Exception $err) {
            Redirect\toErrorPage($err->getMessage());
        }
    } else {
        Redirect\toErrorPage(Exception("Page not found"));
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once(APP_ROOT. "templates/head_base.php"); ?>
    <link rel="stylesheet" href="/public/styles/view_ground.css">
    <title>View Ground | ZSchedule</title>
</head>
<body>
    <?php include_once(APP_ROOT. "templates/navbar.php"); ?>
    <div class="mobile-container">
        <h1> <?php echo $ground['name'] ?> </h1>
        <img 
            src="<?php echo $ground['photo'] ?>"
        />

        <div class="segment">
            <h3>Ground Manager</h3>
            <p> <?php echo $ground['manager_name'] ?> </p>
        </div>

        <?php
            if ($ground['amenities'] != null && $ground['amenities'] != "") {
                ?>
                    <div class="segment">
                        <h3>Amenities</h3>
                        <p> <?php echo $ground['amenities'] ?> </p>
                    </div>
                <?php 
            }
        ?>

        <?php
            if (count($zones) > 0) {
                ?> 
                    <div id="ground-zones">
                        <h2>Ground Zones</h2>
                        <?php 
                            foreach ($zones as $zone) {
                                ?>
                                    <div class="segment">
                                        <h3> <?php echo $zone['name'] ?> </h3>
                                        <?php 
                                            if ($zone['amenities'] != null && $zone['amenities'] != "") {
                                                ?> 
                                                    <p> <?php echo $zone['amenities'] ?> </p>
                                                <?php
                                            }
                                        ?>
                                    </div>
                                <?php
                            }
                        ?>
                    </div>
                <?php
            }
        ?>

        <?php 
            if ($ground['close_time'] != null || $ground['open_time'] != null) {
                ?>
                    <form action="/scripts/open_ground.php" method="POST" id="controls">
                        <p>This ground is closed from 
                            <?php echo $ground['close_time'] ?>
                        to 
                            <?php echo $ground['open_time'] ?>
                        </p>
                    </form>
                <?php
            } 
        ?>
    </div>
</body>
</html>