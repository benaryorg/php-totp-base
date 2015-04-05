<?php
	session_start();
	$sid=session_id();
	if(isset($_SESSION['user']))
	{
		echo 'loggedin as '.$_SESSION['name'];
	}
	else
	{
		header('Location: login.php');
	}

