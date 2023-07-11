<?php

define('DB_DSN', 'mysql:host=localhost;dbname=p5;charset=UTF8');

define('DB_USER', 'root');

define('DB_PASS', '');

define('DB_OPTIONS', array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
