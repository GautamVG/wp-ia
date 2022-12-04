<?php include_once(dirname(__DIR__) . "/bootstrap.php") ?>

<?php 
    include_once(APP_ROOT . "lib/db.php");
    include_once(APP_ROOT . "lib/redirect.php");
?>

<?php 
    function verifyToken($svvid, $token) {
        $time = substr($token, 30);
        $timePassed = time() - $time;
        if ($timePassed < 0 || $timePassed > 15*60) return false;

        try {
            $db = DB\connect();
            $query = "SELECT * FROM `user` WHERE `svvid` = :svvid AND `pwd_reset_token` = :token";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":svvid", $svvid);
            $stmt->bindParam(":token", $token);
            $stmt->execute();
            if (count($stmt->fetchAll()) == 0) return false;
        } catch (Exception $err) {
            Redirect\toErrorPage($err->getMessage());
        }

        return true;
    }

    function resetPassword($svvid, $pwd) {
        try {
            $db = DB\connect();
            $hashedPwd = password_hash($pwd, PASSWORD_BCRYPT);
            $query = "UPDATE `user` SET `pwd` = :hashedPwd , `pwd_reset_token` = NULL WHERE `svvid` = :svvid";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":svvid", $svvid);
            $stmt->bindParam(":hashedPwd", $hashedPwd);
            $stmt->execute();
        } catch (Exception $err) {
            Redirect\toErrorPage($err->getMessage());
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once(APP_ROOT. "templates/head_base.php"); ?>
    <link rel="stylesheet" href="/public/styles/request_password_reset.css">
    <title>Reset Password | ZSchedule</title>
</head>
<body>
    <div class="mobile-container">

        <?php 
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                if (isset($_GET['svvid']) && isset($_GET['token'])) {
                    $svvid = $_GET['svvid'];
                    $token = $_GET['token'];
                    ?> 
                        <h1>Reset your password</h1>
                        <form method="POST">
                            <label>Enter new password</label>
                            <input type="password" name="pwd" required />
                            <input type="hidden" name="svvid" value="<?php echo $svvid ?>">
                            <input type="hidden" name="token" value="<?php echo $token ?>">
                            <input type="submit" name="request" />
                        </form>
                    <?php
                } else {
                    Redirect\toBookingsPage();
                }
            } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['svvid']) && isset($_POST['token']) && isset($_POST['pwd'])) {
                    $svvid = $_POST['svvid'];
                    $token = $_POST['token'];
                    $pwd = $_POST['pwd'];

                    $requestValid = verifyToken($svvid, $token);
                    if ($requestValid) {
                        resetPassword($svvid, $pwd);
                        ?> 
                            <h1>Your password has been reset</h1>
                            <a href="/pages/login.php">You may login</a>
                        <?php
                    } else {
                        ?> 
                            <h1>This reset link has expired</h1>
                        <?php
                    }
                } else {
                    ?> 
                        <h1>There was an error</h1>
                        <h2>Please try again</h2>
                    <?php
                }
            }
        ?>
    </div>
</body>
</html>