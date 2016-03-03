<?php
	include('baza.php');

	session_start();


	if(isset($_POST['login'])){

		$login = $_POST['login'];
		$pass = md5($_POST['pass']);

		$sth = $pdo->prepare('SELECT * FROM `users` WHERE user = :login AND pass = :pass');
		$sth->bindparam(':login', $login, PDO::PARAM_STR);
		$sth->bindparam(':pass', $pass, PDO::PARAM_STR);

		$sth->execute();

		$result = $sth->fetch();

		if($result && isset($result['id'])){

			$_SESSION['logged'] = true;
			$_SESSION['userlogin'] = $result['login'];
			header('location:loop.php');

		}
	}

	if(!isset($_SESSION['logged']) || $_SESSION['logged'] = false){
?>

		<form action="login.php" method="post">

		Login:</br>
		<input type="text" name="login"><br><br>

		Pass: <br>
		<input type="password" name="pass"><br><br>

		<input type="submit" value="Zaloguj">
		</form>

	

<?php
	
	die;
}
?>