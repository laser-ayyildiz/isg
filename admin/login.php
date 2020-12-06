<?php

session_start();

require 'lib/password.php';
require 'connect.php';

if ($_POST) {
    $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
    $passwordAttempt = !empty($_POST['password']) ? trim($_POST['password']) : null;

    $sql = "SELECT id, username, password,valid FROM users WHERE username = :username and password = :password and valid = 1";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':username', $username);

    $stmt->bindValue(':password', md5(md5($passwordAttempt)));

    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user === false) {

        ?>
      <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
        <strong>Kullanıcı adı veya şifre yanlış. Lütfen tekrar deneyin</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php
    } else {
        $başlangıç=$pdo->prepare("SELECT * FROM `users` WHERE `username` = '$username'");
        $başlangıç->execute();
        $girişler=$başlangıç-> fetchAll(PDO::FETCH_OBJ);
        foreach ($girişler as $giriş) {
          $auth = $giriş->auth;
        }
        if ($auth == 15) {
          ?>
          <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
            <strong>Bütün yetkileriniz silinmiştir. Bir hata olduğunu düşünüyorsanız yöneticinizle görüşün!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <?php
        }
        else {
          //Provide the user with a login session.
          $_SESSION['user_id'] = $user['id'];
          $_SESSION['logged_in'] = time();
          //Redirect to our protected page, which we called home.php
          header("Location: panel/index.php");
        }
      }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
	<title>İSG</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body>
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="images/osgb_amblem.jpg" alt="IMG">
				</div>
				<form class="login100-form validate-form" action=login.php method="post">
					<span class="login100-form-title">
						<h1>Hoş Geldiniz!</h1>
					</span>
					<div class="wrap-input100 validate-input" data-validate = "Geçerli bir mail giriniz: ex@abc.xyz">
						<input class="input100" type="text" name="username" id="username" placeholder="Email">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Lütfen şifrenizi girin">
						<input class="input100" type="password" name="password" id="password" placeholder="Şifre">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>

					<div class="container-login100-form-btn" style="padding-bottom:150px;">
						<button class="login100-form-btn" style="background-color:blue;">
							Giriş Yap
						</button>
					</div>

				</form>
			</div>
		</div>
	</div>
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="vendor/select2/select2.min.js"></script>
	<script src="vendor/tilt/tilt.jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script type="text/javascript">
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
  </script>
	<script >
  $('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
	<script src="js/main.js"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>
