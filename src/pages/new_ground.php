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
    try {
        $db = DB\connect();
        $query = "SELECT `svvid`, `name` FROM `user`, `user_type` WHERE `user`.`type` = `user_type`.`id` AND `user_type`.`label` = 'ground_manager'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $groundManagers = $stmt->fetchAll();
    } catch (Exception $err) {
        Redirect\toErrorPage($err->getMessage());
    }

    function uploadGroundPhoto() {
        $uploadedFilename = $_FILES['ground-photo']['name'];
        $uploadedFileLocation = $_FILES['ground-photo']['tmp_name'];
        $newFilename =  md5($uploadedFilename . time()) . "." . pathinfo($uploadedFilename, PATHINFO_EXTENSION);
        $newFileLocation = APP_ROOT . "public/upload/grounds/" . $newFilename;
        move_uploaded_file($uploadedFileLocation, $newFileLocation);
        return "/public/upload/grounds/" . $newFilename;
    }

    function createGround() {
        try {
            $db = DB\connect();
            $uploadedGroundPhoto = uploadGroundPhoto();
            $query = "INSERT INTO `ground` (`name`, `photo`, `manager_svvid`) VALUES (:name, :photo, :manager_svvid);";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":name", $_POST['ground-name']);
            $stmt->bindParam(":photo", $uploadedGroundPhoto);
            $stmt->bindParam(":manager_svvid", $_POST['ground-manager-svvid']);
            $stmt->execute();
            return $db->lastInsertId();
        } catch (Exception $err) {
            Redirect\toErrorPage($err->getMessage());
        }
    }

    function createPrimaryZone($groundId) {
        try {
            $db = DB\connect();
            $query = "INSERT INTO `zone` (`name`, `is_primary`, `is_multi_zonal`, `amenities`, `ground_id`) VALUES (:name, true, :is_multi_zonal, :amenities, :groundId);";
            $isMultiZonal = isset($_POST['ground-multi-zone']) ? 1 : 0;
            $stmt = $db->prepare($query);
            $stmt->bindParam(":name", $_POST['ground-name']);
            $stmt->bindParam(":is_multi_zonal", $isMultiZonal);
            $stmt->bindParam(":amenities", $_POST['ground-amenities']);
            $stmt->bindParam(":groundId", $groundId);
            $stmt->execute();
        } catch (Exception $err) {
            Redirect\toErrorPage($err->getMessage());
        }
    }

    function createZone($groundId, $zoneName, $zoneAmenities) {
        try {
            $db = DB\connect();
            $query = "INSERT INTO `zone` (`name`, `is_primary`, `amenities`, `ground_id`) VALUES (:name, false, :amenities, :groundId);";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":name", $zoneName);
            $stmt->bindParam(":amenities", $zoneAmenities);
            $stmt->bindParam(":groundId", $groundId);
            $stmt->execute();
        } catch (Exception $err) {
            Redirect\toErrorPage($err->getMessage());
        }
    }

    $errMsg = null;
    if (isset($_POST['submit'])) {
        if (
            isset($_POST['ground-name']) && $_POST['ground-name'] != "" &&
            isset($_POST['ground-manager-svvid']) && $_POST['ground-manager-svvid'] != "" && 
            filter_var($_POST['ground-manager-svvid'], FILTER_VALIDATE_EMAIL)
        ) {
            $createdGroundId = createGround();
            createPrimaryZone($createdGroundId);
            if (isset($_POST['ground-multi-zone'])) {
                $zones = [];
                foreach ($_POST as $key => $value) {
                    if (strpos($key, "ground-zone") === 0) {
                        $parts = explode("-", $key);
                        $i = $parts[2] - 1;
                        $type = $parts[3];
                        if (array_key_exists($i, $zones))
                            $zones[$i][$type] = $value;
                        else
                            array_push($zones, [$type => $value]);
                    }
                }
                foreach ($zones as $zone) {
                    createZone($createdGroundId, $zone['name'], $zone['amenities']);
                }
            }
            Redirect\toGroundsPage();
        } else {
            $errMsg = "Please fill all details";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once(APP_ROOT. "templates/head_base.php"); ?>
    <link rel="stylesheet" href="/public/styles/new_ground.css">
    <title>Add a Ground | ZSchedule</title>
</head>
<body>
    <?php include_once(APP_ROOT. "templates/navbar.php"); ?>
    <div class="mobile-container">
        <h1> Add a new ground </h1>
        <form method="post" enctype="multipart/form-data">
            <div class="form-element">
                <label>Choose a photo</label>
                <input type="file" name="ground-photo" required>
                <div id="selected-image-box" class="form-input"> </div>
            </div>
            <div class="form-element">
                <label>Ground name</label>
                <input type="text" name="ground-name" required>
            </div>
            <div class="form-element">
                <label>Amenities</label>
                <input type="text" name="ground-amenities">
            </div>
            <div class="form-element">
                <label>Choose a ground manager</label>
                <select name="ground-manager-svvid" required>
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
            <div class="form-element inline">
                <label for="ground-multi-zone">Multi-zonal ground?</label>
                <input type="checkbox" name="ground-multi-zone" id="ground-multi-zone">
            </div>

            <div id="ground-zones"></div>

            <input type="submit" name="submit">
        </form>

        <?php 
            if (isset($errMsg)) {
                ?>
                    <p class="err-msg">
                        <?php echo $errMsg ?>
                    </p>
                <?php
            }
        ?>
    </div>

    <script>
        document.forms[0]["ground-photo"].addEventListener("change", (e) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById("selected-image-box").style.backgroundImage = "url(" + e.target.result + ")";
            }
            reader.readAsDataURL(e.target.files[0]);
        })

        const groundZonesFormElementsHTML = `
            <div class="form-element inline">
                <h2>Ground Zones</h2>
                <button type="button" id="add-ground-zone">
                    Add
                </button>
            </div>
            <div class="form-element">
                <label>Ground Zone 1</label>
                <input type="text" name="ground-zone-1-name" placeholder="Ground zone name" required>
                <input type="text" name="ground-zone-1-amenities" placeholder="Amenities for this zone">
            </div>
        `;

        const groundZoneFormElementHTML = (i) => `
            <div class="form-element">
                <label>Ground Zone ${i}</label>
                <input type="text" name="ground-zone-${i}-name" placeholder="Ground zone name" required>
                <input type="text" name="ground-zone-${i}-amenities" placeholder="Amenities for this zone">
            </div>
        `;

        document.forms[0]["ground-multi-zone"].addEventListener("change", (e) => {
            console.log(e.target.checked);
            const parent = document.getElementById("ground-zones");
            if (e.target.checked) {
                parent.innerHTML += groundZonesFormElementsHTML;
                document.getElementById("add-ground-zone").addEventListener("click", (e) => {
                    const elem = document.createElement("div");
                    elem.innerHTML = groundZoneFormElementHTML(parent.children.length);
                    parent.appendChild(elem);
                })
            } else {
                while (parent.firstChild) parent.removeChild(parent.lastChild);
            }
        })
    </script>
</body>
</html>