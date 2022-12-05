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
    function uploadGroundPhoto() {
        $uploadedFilename = $_FILES['ground-photo']['name'];
        $uploadedFileLocation = $_FILES['ground-photo']['tmp_name'];
        $newFilename =  md5($uploadedFilename . time()) . "." . pathinfo($uploadedFilename, PATHINFO_EXTENSION);
        $newFileLocation = APP_ROOT . "public/upload/grounds/" . $newFilename;
        move_uploaded_file($uploadedFileLocation, $newFileLocation);
        return "/public/upload/grounds/" . $newFilename;
    }

    if (isset($_POST["delete"])) {
        $groundId = $_POST["delete"];
        try {
            $db = DB\connect();
            $query = "DELETE FROM `ground` WHERE `id` = :groundId;";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":groundId", $groundId);
            $stmt->execute();

        } catch (Exception $err) {
            Redirect\toErrorPage($err->getMessage());
        }
    }

    if (isset($_FILES["ground-photo"]) && $_FILES["ground-photo"]["size"] > 0) {
        $groundId = $_POST["change-photo"];
        try {
            $db = DB\connect();

            $query = "SELECT `photo` FROM `ground` WHERE `id` = :groundId;";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":groundId", $groundId);
            $stmt->execute();
            $existingPhotoFilename = $stmt->fetchAll()[0]['photo'];

            unlink(APP_ROOT . $existingPhotoFilename);
            $photo = uploadGroundPhoto();

            $query = "UPDATE `ground` SET `photo` = :photo WHERE `id` = :groundId;";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":groundId", $groundId);
            $stmt->bindParam(":photo", $photo);
            $stmt->execute();

        } catch (Exception $err) {
            Redirect\toErrorPage($err->getMessage());
        }
    }

    if (isset($_POST["update-details"])) {
        try {
            $db = DB\connect();

            $groundId = $_POST["update-details"];
            $query = "UPDATE `ground` SET `name` = :groundName, `manager_svvid` = :managerSVVID WHERE `id` = :groundId;";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":groundId", $groundId);
            $stmt->bindParam(":groundName", $_POST["ground-name"]);
            $stmt->bindParam(":managerSVVID", $_POST["ground-manager-svvid"]);
            $stmt->execute();

            $query = "UPDATE `zone` SET `name` = :groundName, `amenities` = :groundAmenities WHERE `ground_id` = :groundId AND `is_primary` = true;";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":groundId", $groundId);
            $stmt->bindParam(":groundName", $_POST["ground-name"]);
            $stmt->bindParam(":groundAmenities", $_POST["ground-amenities"]);
            $stmt->execute();

        } catch (Exception $err) {
            Redirect\toErrorPage($err->getMessage());
        }
    }

    if (isset($_POST["add-zone"])) {
        // print_r("Adding zone: " . $_POST["add-zone"]);
        try {
            $db = DB\connect();
            $groundId = $_POST["add-zone"];

            $query = "SELECT count(*) as `count` FROM `zone` WHERE `ground_id` = :groundId AND `is_primary` = false;";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":groundId", $groundId);
            $stmt->execute();
            $countOfZones = $stmt->fetchAll()[0]['count'];

            if ($countOfZones == 0) {
                $query = "UPDATE `zone` SET `is_multi_zonal` = true WHERE `ground_id` = :groundId AND `is_primary` = true;";
                $stmt = $db->prepare($query);
                $stmt->bindParam(":groundId", $groundId);
                $stmt->execute();
            }

            $query = "INSERT INTO `zone` (`name`, `is_primary`, `amenities`, `ground_id`) VALUES (:zoneName, false, :zoneAmenities, :groundId);";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":groundId", $groundId);
            $stmt->bindParam(":zoneName", $_POST["zone-name"]);
            $stmt->bindParam(":zoneAmenities", $_POST["zone-amenities"]);
            $stmt->execute();

        } catch (Exception $err) {
            Redirect\toErrorPage($err->getMessage());
        }
    }

    if (isset($_POST["update-zone"])) {
        try {
            $db = DB\connect();
            $zoneId = $_POST["update-zone"];
            $query = "UPDATE `zone` SET `name` = :zoneName, `amenities` = :zoneAmenities WHERE `id` = :zoneId;";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":zoneId", $zoneId);
            $stmt->bindParam(":zoneName", $_POST["zone-name"]);
            $stmt->bindParam(":zoneAmenities", $_POST["zone-amenities"]);
            $stmt->execute();

        } catch (Exception $err) {
            Redirect\toErrorPage($err->getMessage());
        }
    }

    if (isset($_POST["delete-zone"])) {
        try {
            $db = DB\connect();
            $zoneId = $_POST["delete-zone"];

            $query = "SELECT count(*) as `count`, `ground_id` FROM `zone` WHERE `ground_id` IN (SELECT `ground_id` FROM `zone` WHERE `id` = :zoneId) GROUP BY `ground_id`;";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":zoneId", $zoneId);
            $stmt->execute();
            $results = $stmt->fetchAll()[0];
            $countOfZones = $results['count'] - 1;
            $groundId = $results['ground_id'];

            if ($countOfZones == 1) {
                $query = "UPDATE `zone` SET `is_multi_zonal` = false WHERE `ground_id` = :groundId AND `is_primary` = true;";
                $stmt = $db->prepare($query);
                $stmt->bindParam(":groundId", $groundId);
                $stmt->execute();
            }

            $query = "DELETE FROM `zone` WHERE `id` = :zoneId;";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":zoneId", $zoneId);
            $stmt->execute();

        } catch (Exception $err) {
            Redirect\toErrorPage($err->getMessage());
        }
    }

    Redirect\toGroundsPage();
?>