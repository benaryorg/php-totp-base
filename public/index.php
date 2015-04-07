<?php
    require_once(__DIR__.'/../bootstrap.php');
	$sid=session_id();
	if(isset($_SESSION['user']))
	{
		echo 'loggedin as '.$_SESSION['name'];
	}
	else
	{
		header('Location: login.php');
	}

