<?php include_once(dirname(__DIR__) . "/bootstrap.php") ?>

<?php 
    include_once(APP_ROOT . "lib/db.php");
    include_once(APP_ROOT . "lib/errors.php");
    include_once(APP_ROOT . "lib/redirect.php");
?>

<?php 
    session_start();
    if (isset($_SESSION) && isset($_SESSION['userData'])) Redirect\toBookingsPage();
?>

<?php 
    function signIn($svvid, $pwd) {
        $db = DB\connect();
        try {
            $stmt = $db->prepare("SELECT name, svvid, pwd, photo, label AS user_type_label FROM user, user_type WHERE svvid = :svvid AND user.type = user_type.id LIMIT 1;");
            $stmt->bindParam(":svvid", $svvid);
            $stmt->execute();
            $results = $stmt->fetchAll();
            if (count($results) > 0 && password_verify($pwd, $results[0]['pwd']))
                return $results[0];
        } catch (Exception $err) {
            Redirect\toErrorPage($err->getMessage());
        }
        return false;
    }

    $errMsg = null;
    if (isset($_POST['login'])) {
        if (
            isset($_POST['svvid']) && 
            isset($_POST['pwd']) && 
            filter_var($_POST['svvid'], FILTER_VALIDATE_EMAIL)
        ) {
            $svvid = $_POST['svvid'];
            $pwd = $_POST['pwd'];
            $userData = signIn($svvid, $pwd);
            if (!$userData) {
                $errMsg = "Incorrect SVV ID or Password";
            } else {
                session_start();
                $_SESSION['userData'] = $userData;
                Redirect\toBookingsPage();
            }
        } else {
            $errMsg = "Please fill all details";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once(APP_ROOT. "templates/head_base.php"); ?>
    <link rel="stylesheet" href="/public/styles/login.css">
    <title>Login | ZSchedule</title>
</head>
<body>
    <div class="mobile-container">
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
            <a href="./request_password_reset.php">Reset it</a> 
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