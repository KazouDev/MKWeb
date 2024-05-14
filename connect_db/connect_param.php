<?php
    $env = parse_ini_file('../.env');
    var_dump($env);
    $server = $env["DB_SERVER"];
    $driver = $env["DB_DRIVER"];
    $dbname = $env["DB_NAME"];
    $user   = $env["DB_USER"];
    $pass	= $env["DB_PASS"];
?>