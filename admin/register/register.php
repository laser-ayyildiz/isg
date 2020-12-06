<?php
session_start();
header("Location: ../login.php");
require '../lib/password.php';
require '../connect.php';

if (isset($_POST['register'])) {
    $firstname = !empty($_POST['firstname']) ? trim($_POST['firstname']) : null;
    $lastname = !empty($_POST['lastname']) ? trim($_POST['lastname']) : null;
    $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
    $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : null;
    $pass = !empty($_POST['password']) ? trim($_POST['password']) : null;
    $kod    = md5(rand(0, 9999999999));
    $auth = !empty($_POST['auth']) ? trim($_POST['auth']) : null;

    $sql = "SELECT COUNT(username) AS num FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':username', $username);

    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['num'] > 0) {
        ?>

      <script type = "text/javascript">
            function getConfirmation() {
               var retVal = confirm("Bu mail adresiyle daha önce kayıt yapıldı");
               if( retVal == true ) {
                  return true;
               }
            }
            getConfirmation();
      </script>


      <?php
    } else {
        $passwordHash =md5(md5($pass));

        $sql = "INSERT INTO users (firstname, lastname, username, phone, password,valid_code,auth) VALUES (:firstname, :lastname, :username, :phone, :password, :valid_code, :auth)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':firstname', $firstname);
        $stmt->bindValue(':lastname', $lastname);
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':password', $passwordHash);
        $stmt->bindValue(':valid_code', $kod);
        $stmt->bindValue(':auth', $auth);

        $result = $stmt->execute();

        if ($result) {
            include("mail/PHPMailerAutoload.php");
            $mail = new PHPMailer;
            $mail->IsSMTP();
            //$mail->SMTPDebug = 1; // hata ayiklama: 1 = hata ve mesaj, 2 = sadece mesaj
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls'; // Güvenli baglanti icin ssl normal baglanti icin tls
                $mail->Host = "smtp.gmail.com"; // Mail sunucusuna ismi
                $mail->Port = 587; // Gucenli baglanti icin 465 Normal baglanti icin 587
                $mail->IsHTML(true);
            $mail->SetLanguage("tr", "phpmailer/language");
            $mail->CharSet  ="utf-8";
            $mail->Username = "osgbdeneme@gmail.com"; // Mail adresimizin kullanicı adi
                $mail->Password = "deneme123"; // Mail adresimizin sifresi
                $mail->SetFrom("osgbdeneme@gmail.com", $firstname); // Mail attigimizda gorulecek ismimiz
                $mail->AddAddress($username); // Maili gonderecegimiz kisi yani alici
                $mail->addReplyTo($username, $firstname, $lastname);
            $mail->Subject = "Üye Onay Maili"; // Konu basligi
                $mail->Body = "<div style='background:#eee;padding:5px;margin:5px;width:300px;'> eposta : ".$username."</div> <br /> Onay Linki : <br />

    			 http://localhost/isg/admin/register/validate.php?username=".$username."&valid_code=".$kod."


    			"; // Mailin icerigi
                if (!$mail->Send()) {
                    ?>
            <script type = "text/javascript">
                  function getConfirmation() {
                     var retVal = confirm("Veritabanına kaydınız yapıldı fakat onay maili gönderilemedi. Lütfen bizimle ilteşime geçin");
                     if( retVal == true ) {
                        return true;
                     }
                  }
                  getConfirmation();
            </script>
            <?php
                } else {
                    ?>
            <script type = "text/javascript">
                  function getConfirmation() {
                     var retVal = confirm("Kayıt işleminiz yapılmıştır. E-mailinize gelen onay maili ile hesabınızı aktif hale getirebilirsiniz");
                     if( retVal == true ) {
                        return true;
                     }
                  }
                  getConfirmation();
            </script>
            <?php
            $sorgu=$pdo->prepare("SELECT * FROM `users` WHERE `username` = '$username'");
                    $sorgu->execute();
                    $users=$sorgu-> fetchAll(PDO::FETCH_OBJ);
                    foreach ($users as $user) {
                        $id = $user->id;
                    }
                    $source = "../panel/assets/img/avatars/custom.png";
                    $destination = "../panel/assets/users/$id.jpeg";
                    copy($source, $destination);
                }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">
    <title>İsg-Kayıt Ol</title>
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">
    <link href="css/main.css" rel="stylesheet" media="all">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body>
    <div class="page-wrapper bg-gra-03 p-t-45 p-b-50">
        <div class="wrapper wrapper--w790">
            <div class="card card-5">
                <div class="card-heading">
                    <h2 class="title">Kayıt Ol</h2>
                </div>
                <div class="card-body">

                    <form action="register.php" method="POST" autocomplete="off">
                      <div class="form-row">
                          <div class="name">Kullanıcı Türü</div>
                          <div class="value">
                              <div class="input-group">
                                <select class="input--style-5" name="auth" id="auth" autocomplete="off" style="height:50px"required/>
                                  <option value="" disabled>Kullanıcı Türü</option>
                                  <option value="0">Özgür OSGB Çalışanı</option>
                                  <option value="2">Ortak Çalışılan İşletme</option>
                                </select>
                              </div>
                          </div>
                      </div>
                        <div class="form-row m-b-55">
                            <div class="name">İsim</div>
                            <div class="value">
                                <div class="row row-space">
                                    <div class="col-6">
                                        <div class="input-group-desc">
                                            <input class="input--style-5" type="text" id="firstname" name="firstname" required>
                                            <label class="label--desc">isim</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group-desc">
                                            <input class="input--style-5" type="text" id="lastname" name="lastname" required>
                                            <label class="label--desc">soy isim</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="name">Şifre</div>
                            <div class="value">
                                <div class="input-group">
                                    <input class="input--style-5" type="password" id="password" name="password" minlength="8" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="name">Email</div>
                            <div class="value">
                                <div class="input-group">
                                    <input class="input--style-5" type="email" id="username" name="username"required>
                                </div>
                            </div>
                        </div>
                        <div class="form-row m-b-55">
                            <div class="name">Telefon No</div>
                            <div class="value">
                              <div class="input-group-desc">
                                  <input class="input--style-5" type="tel" id="phone" name="phone" pattern="(\d{4})(\d{3})(\d{2})(\d{2})" maxlength="11" required>
                                  <label class="label--desc">0(5XX)XXXXXXX</label>
                              </div>
                            </div>
                        </div>
                        <div>
                            <input type="submit" class="btn btn-primary" name="register" id="register" value="Kayıt Ol" style="height:60px;width:300px;float:left;"></button>
                        </div>
                        <div >
                          <input type="button" class="btn btn-secondary" onClick="location.href='../login.php'" value="Giriş Ekranına Dön" style="height:60px;width:300px;float:right;"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/datepicker/moment.min.js"></script>
    <script src="vendor/datepicker/daterangepicker.js"></script>
    <script src="js/global.js"></script>

</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>
