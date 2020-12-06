<!DOCTYPE HTML>
<html lang="tr">
<head>
	<meta charset="UTF-8">
	<title></title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body>
	  <div class="container">
	  <?php

      include("../connect.php");

        $username = $_GET["username"];
        $kod    = $_GET["valid_code"];

        if (!$username || !$kod) {
            echo '<div style="margin-top:20px;" class="alert alert-warning">gerekli kodlar bos gozukuyor ..</div>';
        } else {
            $query = $pdo->prepare("SELECT * FROM users WHERE username=? and valid_code=? and valid=?");
            $query->execute(array($username,$kod,0));
            $query->fetch(PDO::FETCH_ASSOC);
            $kontrol = $query->rowCount();

            if ($kontrol) {
                $update = $pdo->prepare("UPDATE users SET valid=? WHERE username=? and valid_code=? and valid=?");

                $result =  $update->execute(array(1,$username,$kod,0));

                if ($result == true) {
                    echo "<div class='alert alert-success' role='alert'>Üyeliğiniz onaylanmıştır.<a href='../login.php' class='alert-link'>Giriş</a> yapabilirsiniz!</div>";
                } else {
                    echo '<div style="margin-top:20px;" class="alert alert-warning">Onaylama basarasız oldu mysql hatası. Lütfen bizimle iletişime geçin.</div>';
                }
            } else {
                echo '<div style="margin-top:20px;" class="alert alert-warning">Onay kodu yanlıs ya da daha önce onaylanmış</div>';
            }
        }
      ?>
	 </div>
</body>
</html>
