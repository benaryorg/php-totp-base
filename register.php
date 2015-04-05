<?php
	session_start();
	$sid=session_id();
	if(isset($_SESSION['user']))
	{
		header('Location: /');
		exit;
	}

	require_once('private/BaconQrCode/vendor/autoload.php');
	require_once('private/totp/totp.php');
	$db=require_once('private/database.php');

	if(isset($_POST['user']))
	{
		$user=$_POST['user'];
		$usha=hash('sha512',$user);

		$q=$db->prepare('SELECT id FROM users WHERE name=?');
		$q->execute(array($usha)) or die('There was a problem, please try againx!');
		$ret=$q->fetchAll();
		$q=null;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"/>
	<title>Register</title>
</head>
<body>
<?php
		if(count($ret)>0)
		{
?>
	Username already taken!
<?php
		}
		else
		{
			$secret=TOTP::genSecret(256)['secret'];
			$r=new \BaconQrCode\Renderer\Image\Svg();
			$r->setHeight(512);
			$r->setWidth(512);
			$w=new \BaconQrCode\Writer($r);
			$q=$db->prepare('INSERT INTO users(name,secret) VALUES(?,?)');
			$q->execute(array($usha,$secret)) or die('There was a problem, please try again!');
			$q=null;
			$qr=$w->writeString(TOTP::genURI('totp.benary.org',$secret)['uri']);
?>
	Protect this code with your life! You will never be able to log back in if
	you lose it.<br/>
	Username: <?=$user?><br/>
	Secret: <?=$secret?><br/>
	<?=$qr?><br/>
	Now please log yourself in: <a href="/">Login</a><br/>
<?php
		}
	}
	else
	{
?>
	<form method="post">
		<input name="user" type="text" placeholder="username" />
		<button type="submit">Register</button>
	</form>
<?php
	$db=null;
	}

?>
</body>
</html>

