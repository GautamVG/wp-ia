<?php include_once(dirname(__DIR__) . "/bootstrap.php") ?>

<?php 
    include_once(APP_ROOT . "lib/db.php");
    include_once(APP_ROOT . "lib/redirect.php");
?>

<?php 
    require_once(APP_ROOT . 'vendor/autoload.php');
?>

<?php 
    function requestPasswordResetToken($svvid) {
        $token = bin2hex(random_bytes(15)) . time();
        $hashedToken = password_hash($token, PASSWORD_BCRYPT);
        try {
            $db = DB\connect();
            $query = "UPDATE `user` SET `pwd_reset_token` = :token WHERE `svvid` = :svvid";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":token", $hashedToken);
            $stmt->bindParam(":svvid", $svvid);
            $stmt->execute();
            return $token;
        } catch (Exception $err) {
            Redirect\toErrorPage($err->getMessage());
        }
        return false;
    }

    function sendPasswordResetEmail($token, $svvid) {
        try {
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();                                            
            $mail->Host       = 'smtp.gmail.com';                    
            $mail->SMTPAuth   = true;                                
            $mail->Username   = EMAIL_ADDRESS;
            $mail->Password   = EMAIL_PASS;

            //Recipients
            $mail->setFrom('zschedule@somaiya.edu', 'ZSchedule');
            $mail->addAddress($svvid);    

            //Content
            $mail->isHTML(true);          
            $mail->Subject = 'ZSchedule account password reset link';
            $mail->Body = "<a href='" . APP_ROOT_URL . "/pages/resetPassword.php?svvid=" . $svvid . "&token=" . $token . "'>Click here</a> to change your password. If you did not initiate this request, kindly ignore. The link will expire in 15 minutes.";
            $mail->send();
        } catch (PHPMailer\PHPMailer\Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    $err = true;
    $msg = null;

    if (isset($_POST['request'])) {
        if (isset($_POST['svvid'])) {
            $svvid = $_POST['svvid'];

            $token = requestPasswordResetToken($svvid);
            if (!$token) {
                $err = true;
                $msg = "Incorrect SVV ID";
            } else {
                sendPasswordResetEmail($token, $svvid);
                $err = false;
                $msg = "Password reset link has been sent";
            }
        } else {
            $err = true;
            $msg = "Please fill all details";
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
        <h1>Reset your password</h1>
        <h3>We will send you a link to your registered email</h3>
        <form method="POST">
            <label>SVV ID</label>
            <input type="email" name="svvid" required />
            <input type="submit" name="request" />
        </form>
    </div>

    <?php 
        if (isset($msg)) {
            ?>
                <p 
                    class="<?php 
                        echo "msg" . ($err ? "err" : "success")
                    ?>"
                >
                    <?php echo $msg ?>
                </p>
            <?php
        }
    ?>
</body>
</html>