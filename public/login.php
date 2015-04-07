<?php
    require_once(__DIR__.'/../bootstrap.php');
	$sid=session_id();
	if(isset($_SESSION['user']))
	{
		header('Location: /');
		exit;
	}

	if(isset($_POST['user'])&&isset($_POST['pass']))
	{
		$user=$_POST['user'];
		$usha=hash('sha512',$user);

		$db= require('../private/database.php');

		$stat=$db->prepare('SELECT * FROM users WHERE name=?');
		$stat->execute(array($usha));
		$ret=$stat->fetchAll();

		if(count($ret)>0)
		{
			$ret=$ret[0];
			if(TOTP::getOTP($ret['secret'])['otp']==$_POST['pass'])
			{
				$_SESSION['user']=$ret['id'];
				$_SESSION['name']=$user;
				header('Location: /');
			}else{
                echo "other fail";
            }
		}else{
            echo "fail";
        }
	}
	else
	{
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"/>
	<title>Login</title>
</head>
<body>
	<form method="post">
		<input name="user" type="text" placeholder="username" required/>
		<input name="pass" type="text" placeholder="TOTP Key" required/>
		<button type="submit">Login</button>
	</form>
	Or register <a href="/register.php">here</a>!
</body>
</html>
<?php
	}

