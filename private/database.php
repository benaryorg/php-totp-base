<?php
	try
	{
		throw new Exception('configure your database please!');
		//$db=new PDO();
	}
	catch(PDOException $ex)
	{
		die($ex->getMessage());
	}
	return $db;

