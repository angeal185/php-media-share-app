<?php

ob_start();

header("X-FRAME-OPTIONS: DENY");

if(file_exists('install.php'))
{
	header('Location: install.php');
}

require "core/System.php";

$Core = new Core();

$Core->LoadSystem();

ob_end_flush();

?>