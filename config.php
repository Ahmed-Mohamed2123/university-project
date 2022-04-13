<?php

// create a new session or complete an existing session
session_start();

// define => we add constants that are repeated with us in the code a lot
define("BASEURL", "http://localhost/university-project/");
define("BASEURLPAGES", "http://localhost/university-project/pages/");
define("ASSETS", "http://localhost/university-project/assets/");

define("BL", __DIR__ . "/");
define("BLP", __DIR__ . "/pages/");


// connect to database
require_once(BL.'utils/db.php');
