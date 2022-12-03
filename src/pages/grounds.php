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
        $managedGrounds = [];
        $grounds = [];

        if ($_SESSION['userData']['userTypeLabel'] == "admin") {
            // As an Admin
            $query = "SELECT `ground`.*, `ground_to_user`.`user_svvid` as `manager_svvid`, `user`.`name` as `manager_name` FROM `ground`, `ground_to_user`, `user` WHERE `ground`.`id` = `ground_to_user`.`ground_id` AND `ground_to_user`.`user_svvid` = `user`.`svvid`" ;
            $stmt = $db->prepare($query);
            $stmt->execute();
            $managedGrounds = $stmt->fetchAll();
        } else if ($_SESSION['userData']['userTypeLabel'] == "ground_manager") {
            $query = " SELECT `ground`.*, `ground_to_user`.`user_svvid` as `manager_svvid`, `user`.`name` as `manager_name` FROM `ground`, `ground_to_user`, `user` WHERE `ground`.`id` = `ground_to_user`.`ground_id` AND `ground_to_user`.`user_svvid` = `user`.`svvid` AND `user`.`svvid` = :svvid ;";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $managedGrounds = $stmt->fetchAll();

            $query = " SELECT `ground`.*, `ground_to_user`.`user_svvid` as `manager_svvid`, `user`.`name` as `manager_name` FROM `ground`, `ground_to_user`, `user` WHERE `ground`.`id` = `ground_to_user`.`ground_id` AND `ground_to_user`.`user_svvid` = `user`.`svvid` AND `user`.`svvid` != :svvid ;";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $grounds = $stmt->fetchAll();
        } else {
            $query = " SELECT `ground`.*, `ground_to_user`.`user_svvid` as `manager_svvid`, `user`.`name` as `manager_name` FROM `ground`, `ground_to_user`, `user` WHERE `ground`.`id` = `ground_to_user`.`ground_id` AND `ground_to_user`.`user_svvid` = `user`.`svvid` ;";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $grounds = $stmt->fetchAll();
        }
    } catch (Exception $err) {
        Redirect\toErrorPage($err->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once(APP_ROOT. "templates/head_base.php"); ?>
    <link rel="stylesheet" href="/public/styles/grounds.css">
    <title>Grounds | ZSchedule</title>
</head>
<body>
    <?php include_once(APP_ROOT. "templates/navbar.php"); ?>
    <div class="mobile-container">
        <div class="ground-card">
            <div class="ground-photo">
                <a href="/pages/new_ground.php">
                    <i class="ph-plus"></i>
                </a>
            </div>
            <div class="ground-name">New ground</div>
            <div class="ground-manager">New ground</div>
            <a href="/pages/new_ground.php" class="ground-card-action">
                Add
            </a>
        </div>

        <?php 
            foreach ($managedGrounds as $ground) {
                ?> 
                    <div class="card ground-card">
                        <div class="ground-photo">
                            <a href="/pages/view_ground.php">
                                <i class="ph-plus"></i>
                            </a>
                        </div>
                        <div class="ground-name">
                            <?php echo $ground['name'] ?>
                        </div>
                        <div class="ground-manager">
                            <?php echo $ground['manager_name'] ?>
                        </div>
                        <a href="/pages/new_ground.php" class="ground-card-action">
                            View
                        </a>
                    </div>
                <?php
            }
        ?>
    </div>
</body>
</html>