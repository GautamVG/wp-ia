<?php include_once(dirname(__DIR__) . "/bootstrap.php") ?>

<?php 
    include_once(APP_ROOT . "lib/db.php");
    include_once(APP_ROOT . "lib/errors.php");
    include_once(APP_ROOT . "lib/redirect.php");
?>

<?php 
    function isValid($svvid, $pwd) {
        $db = DB\connect();
        try {
            $stmt = $db->prepare("SELECT * FROM `user` WHERE `svvid` = ':svvid' LIMIT 1");
            $stmt->bindParam(':svvid', $svvid);
            $response = $stmt->execute();
            if ($response) {
                $results = $response->fetchAll();
                if (count($results) > 0 && password_verify($pwd, $results[0]['pwd']))
                    return true;
            }
        } catch (Exception $err) {
            Redirect\toErrorPage($err->getMessage());
        }
        return false;
    }

    $errMsg = null;
    if (isset($_POST['login'])) {
        if (isset($_POST['svvid']) && isset($_POST['pwd'])) {
            $svvid = $_POST['svvid'];
            $pwd = $_POST['pwd'];
            if (!isValid($svvid, $pwd)) {
                $errMsg = "Incorrect SVV ID or Password";
            } else {
                session_start();
                $_SESSION['svvid'] = $svvid;
                Redirect\toHomePage();
            }
        } else {
            $errMsg = "Please fill all details";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once(APP_ROOT. "templates/head_base.html"); ?>
    <link rel="stylesheet" href="/public/styles/login.css">
    <title>Login | ZSchedule</title>
</head>
<body>
    <div class="container">
        <h1>ZSchedule</h1>
        <h3>Welcome</h3>
        <form method="POST" />
            <label>SVV ID</label>
            <input type="email" name="svvid" required />
            <label>Password</label>
            <input type="password" name="pwd" required />
            <input type="submit" name="login" />
        </form>
        <p>
            Forgot password?
            <a href="./forgot_password.php">Sign up</a> 
        </p>
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
</body>
</html>