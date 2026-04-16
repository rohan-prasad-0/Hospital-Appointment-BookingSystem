<?php

    $server = "127.0.0.1";
    $user = "root";
    $password = "";
    $db_name = "abc_web_db";

    $mysqli = new mysqli($server, $user, $password, $db_name);

    if ($mysqli->connect_error) {
        die("Connection Failed");
    }

?>