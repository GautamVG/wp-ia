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

        if ($_SESSION['userData']['user_type_label'] == "admin") {
            // As an Admin
            $query = "SELECT `ground`.*, `user`.`name` as `manager_name` FROM `ground`, `user` WHERE `ground`.`manager_svvid` = `user`.`svvid`;";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $managedGrounds = $stmt->fetchAll();
        } else if ($_SESSION['userData']['user_type_label'] == "ground_manager") {
            $query = "SELECT `ground`.*, `user`.`name` as `manager_name` FROM `ground`, `user` WHERE `ground`.`manager_svvid` = `user`.`svvid` AND `ground`.`manager_svvid` = :svvid;";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":svvid", $_SESSION['userData']['svvid']);
            $stmt->execute();
            $managedGrounds = $stmt->fetchAll();

            $query = "SELECT `ground`.*, `user`.`name` as `manager_name` FROM `ground`, `user` WHERE `ground`.`manager_svvid` = `user`.`svvid` AND `ground`.`manager_svvid` != :svvid;";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":svvid", $_SESSION['userData']['svvid']);
            $stmt->execute();
            $grounds = $stmt->fetchAll();
        } else {
            $query = "SELECT `ground`.*, `user`.`name` as `manager_name` FROM `ground`, `user` WHERE `ground`.`manager_svvid` = `user`.`svvid`;";
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
        <h1>College Grounds</h1>

        <?php 
            if ($_SESSION['userData']['user_type_label'] == "admin") {
                ?>
                    <div class="cards-grid">
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
                    </div>
                <?php
            }
        ?>

        <?php 
            if (count($managedGrounds) > 0) {
                ?> 
                    <h2>Grounds managed by you</h2>
                    <div class="cards-grid">
                        <?php 
                            foreach ($managedGrounds as $ground) {
                                ?> 
                                    <div class="card ground-card">
                                        <div class="ground-photo">
                                            <img src="<?php echo $ground['photo'] ?>" alt="Ground Photo" />
                                        </div>
                                        <div class="ground-name">
                                            <?php echo $ground['name'] ?>
                                        </div>
                                        <div class="ground-manager">
                                            <?php echo $ground['manager_name'] ?>
                                        </div>
                                        <a 
                                            href="<?php 
                                                if ($_SESSION['userData']['user_type_label'] == "admin")
                                                    echo("/pages/view_ground_as_admin.php?g=".$ground['id']);
                                                else
                                                    echo("/pages/view_ground_as_manager.php?g=".$ground['id']);
                                            ?>" 
                                            class="ground-card-action"
                                        >
                                            Edit
                                        </a>
                                    </div>
                                <?php
                            }
                        ?>
                    </div>
                <?php
            }
        ?>

        <?php 
            if (count($grounds) > 0) {
                ?> 
                    <h2>All grounds</h2>
                    <div class="cards-grid">
                        <?php 
                            foreach ($grounds as $ground) {
                                ?> 
                                    <div class="card ground-card">
                                        <div class="ground-photo">
                                            <img src="<?php echo $ground['photo'] ?>" alt="Ground Photo" />
                                        </div>
                                        <div class="ground-name">
                                            <?php echo $ground['name'] ?>
                                        </div>
                                        <div class="ground-manager">
                                            <?php echo $ground['manager_name'] ?>
                                        </div>
                                        <a href="/pages/view_ground.php?g=<?php echo $ground['id'] ?>" class="ground-card-action">
                                            View
                                        </a>
                                    </div>
                                <?php
                            }
                        ?>
                    </div>
                <?php
            }
        ?>
    </div>
</body>
</html>