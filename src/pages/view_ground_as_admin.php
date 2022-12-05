<?php include_once(dirname(__DIR__) . "/bootstrap.php") ?>

<?php 
    include_once(APP_ROOT . "lib/db.php");
    include_once(APP_ROOT . "lib/redirect.php");
?>

<?php 
    session_start();
    if (!(isset($_SESSION) && isset($_SESSION['userData']) && $_SESSION['userData']['user_type_label'] == "admin")) Redirect\toLoginPage();
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

            $db = DB\connect();
            $query = "SELECT `svvid`, `name` FROM `user`, `user_type` WHERE `user`.`type` = `user_type`.`id` AND `user_type`.`label` = 'ground_manager'";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $groundManagers = $stmt->fetchAll();
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

        <form action="/scripts/ground_actions.php" method="POST" id="ground-actions" class="btn-grp" enctype="multipart/form-data">
            <label class="btn">
                Change Photo
                <input type="file" name="ground-photo" onchange="form.submit()" hidden>
                <input type="hidden" name="change-photo" value="<?php echo $ground['ground_id'] ?>">
            </label>
            <button 
                name="delete" 
                value="<?php echo $ground['ground_id'] ?>"
            >Delete</button>

        </form>

        <form action="/scripts/ground_actions.php" method="POST" id="update-details">
            <div class="form-element">
                <label>Ground name</label>
                <input 
                    type="text" 
                    name="ground-name" 
                    value="<?php echo $ground['name'] ?>"
                >
            </div>
            <div class="form-element">
                <label>Amenities</label>
                <input 
                    type="text" 
                    name="ground-amenities"
                    value="<?php echo($ground['amenities'] == null || $ground['amenities'] == "" ? "No amenities specified" : $ground['amenities']) ?>"
                >
            </div>
            <div class="form-element">
                <label>Choose a ground manager</label>
                <select 
                    name="ground-manager-svvid" 
                    value="<?php echo $ground["manager_svvid"] ?>"
                >
                    <?php 
                        foreach ($groundManagers as $groundManager) {
                            ?>
                                <option value="<?php echo $groundManager["svvid"] ?>">
                                    <?php echo $groundManager["name"] ?>
                                </option>
                            <?php
                        }
                    ?>
                </select>
            </div>
            <button 
                name="update-details" 
                value="<?php echo $ground['ground_id'] ?>"
            >Update details</button>
        </form>

        <?php
            if (count($zones) > 0) {
                ?> 
                    <div class="form-element inline">
                        <h2>Ground Zones</h2>
                    </div>
                    <?php 
                        for ($i = 0; $i < count($zones); $i++) {
                            ?>
                                <form action="/scripts/ground_actions.php" method="POST" id="update-zone-details">
                                    <div class="form-element">
                                        <label> Ground Zone <?php echo $i ?> </label>
                                        <input 
                                            type="text" 
                                            name="zone-name" 
                                            value="<?php echo $zones[$i]['name'] ?>" 
                                        >
                                        <input 
                                            type="text" 
                                            name="zone-amenities" 
                                            value="<?php echo $zones[$i]['amenities'] ?>"
                                        >
                                        <button 
                                            name="update-zone" 
                                            value="<?php echo $zones[$i]['id'] ?>"
                                        >Update</button>
                                        <button 
                                            name="delete-zone" 
                                            value="<?php echo $zones[$i]['id'] ?>"
                                        >Delete</button>
                                    </div>
                                </form>
                            <?php
                        }
                    ?>
                <?php
            }
        ?>

        <form action="/scripts/ground_actions.php" method="POST" id="update-zone-details">
            <div class="form-element">
                <label>Zone Name</label>
                <input 
                    type="text" 
                    name="zone-name" 
                    required
                >
                <label>Zone Amenities</label>
                <input 
                    type="text" 
                    name="zone-amenities" 
                    required
                >
                <button 
                    name="add-zone" 
                    value="<?php echo $ground['ground_id'] ?>"
                >Add zone</button>
            </div>
        </form>

        <?php 
            if ($ground['close_time'] != null || $ground['open_time'] != null) {
                ?>
                    <form action="/scripts/open_ground.php" method="POST" id="controls">
                        <p>This ground is closed from 
                            <?php echo $ground['close_time'] ?>
                        to 
                            <?php echo $ground['open_time'] ?>
                        </p>
                        <input type="hidden" name="ground-id" value="<?php echo $_GET['g'] ?>">
                        <input type="submit" name="submit" value="Open now" />
                    </form>
                <?php
            } else {
                ?> 
                    <form action="/scripts/close_ground.php" method="POST" id="controls">
                        <h3>Close the ground</h3>
                        <div class="top-row">
                            <div class="form-element">
                                <label class="form-label">Closing time</label>
                                <input 
                                    type="time" 
                                    name="close-time" 
                                />
                            </div>
                            <div class="form-element">
                                <label class="form-label">Opening time</label>
                                <input 
                                    type="time" 
                                    name="open-time" 
                                />
                            </div>
                        </div>
                        <input type="hidden" name="ground-id" value="<?php echo $_GET['g'] ?>">
                        <input type="submit" name="submit" />
                    </form>
                <?php
            }
        ?>
    </div>
</body>
</html>