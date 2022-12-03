<?php 
    if (count($argv) == 1) die();
    for ($i = 1; $i < count($argv); $i++)
        echo $argv[$i] . " = " . password_hash($argv[$i], PASSWORD_BCRYPT) . "\n";
?>