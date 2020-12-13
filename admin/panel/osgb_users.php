<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header('Location: ../login.php');
    exit;
}
require '../connect.php';
require '../lib/password.php';
$id=$_SESSION['user_id'];
$başlangıç=$pdo->prepare("SELECT * FROM users WHERE id = '$id'");
$başlangıç->execute();
$girişler=$başlangıç-> fetchAll(PDO::FETCH_OBJ);
foreach ($girişler as $giriş) {
    $fn = $giriş->firstname;
    $ln = $giriş->lastname;
    $auth = $giriş->auth;
    $ume = $giriş->username;
    $picture= $giriş->picture;
}
if ($auth != 1) {
    header('Location: 403..php');
}
if (isset($_POST['sil'])) {
    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $sql = "UPDATE `osgb_workers` SET `deleted` = 1 WHERE `mail` = '$email' ";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute();

    $sql2 = "UPDATE `users` SET `auth` = 15 WHERE `username` = '$email' ";
    $stmt2 = $pdo->prepare($sql2);
    $result2 = $stmt2->execute();
    if ($result && $result2) {
        ?>
      <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
        <strong>Çalışan SİLİNMİŞTİR!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
<?php
    }
}
if (isset($_POST['onay'])) {
    $firstname = !empty($_POST['firstname']) ? trim($_POST['firstname']) : null;
    $lastname = !empty($_POST['lastname']) ? trim($_POST['lastname']) : null;
    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : null;
    $tc = !empty($_POST['tc']) ? trim($_POST['tc']) : null;
    $start_date = !empty($_POST['start_date']) ? trim($_POST['start_date']) : null;
    $user_type = !empty($_POST['user_type']) ? trim($_POST['user_type']) : null;
    $not = !empty($_POST['not']) ? trim($_POST['not']) : null;


    $sor=$pdo->prepare("SELECT * FROM `osgb_workers` WHERE `tc` = '$tc'");
    $sor->execute();
    $workers=$sor-> fetchAll(PDO::FETCH_OBJ);
    foreach ($workers as $worker) {
        $id = $worker->id;
    }
    $sql = "UPDATE `osgb_workers` SET `user_type` = '$user_type', `firstname` = '$firstname', `lastname` = '$lastname', `mail` = '$email', `phone` = '$phone',
  `tc` = '$tc', `start_date` = '$start_date', `worker_text` = '$not' WHERE `osgb_workers`.`id` = '$id'";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute();
    if ($result) {
        ?>
<div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
  <strong>Çalışan bilgileri düzenlendi!</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<?php
    }
}
if (isset($_POST['kayıt'])) {
    $firstname = !empty($_POST['firstname']) ? trim($_POST['firstname']) : null;
    $lastname = !empty($_POST['lastname']) ? trim($_POST['lastname']) : null;
    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : null;
    $tc = !empty($_POST['tc']) ? trim($_POST['tc']) : null;
    $start_date = !empty($_POST['start_date']) ? trim($_POST['start_date']) : null;
    $user_type = !empty($_POST['user_type']) ? trim($_POST['user_type']) : null;
    $kod = md5(rand(0, 9999999999));

    $sql = "SELECT COUNT(mail) AS num FROM osgb_workers WHERE mail = :email AND `deleted` = 0";
    $sql2 = "SELECT COUNT(tc) AS num FROM osgb_workers WHERE tc = :tc AND `deleted` = 0";

    $stmt = $pdo->prepare($sql);
    $stmt2 = $pdo->prepare($sql2);

    $stmt->bindValue(':email', $email);
    $stmt2->bindValue(':tc', $tc);

    $stmt->execute();
    $stmt2->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

    if ($row['num'] > 0) {
        ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Bu mail ile kayıtlı çalışan bulunmaktadır!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <?php
    } elseif ($row2['num'] > 0) {
        ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Bu tc ile kayıtlı çalışan bulunmaktadır!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <?php
    } else {
        $sql = "INSERT INTO osgb_workers (user_type, firstname, lastname, mail, phone, tc, start_date)
        VALUES (:user_type, :firstname, :lastname, :email, :phone, :tc, :start_date)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':user_type', $user_type);
        $stmt->bindValue(':firstname', $firstname);
        $stmt->bindValue(':lastname', $lastname);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':tc', $tc);
        $stmt->bindValue(':start_date', $start_date);

        $result = $stmt->execute();

        if ($result) {
            $min = 15263548;
            $max = 94523675;
            $pass = rand($min, $max);
            include("../register/mail/PHPMailerAutoload.php");
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
          $mail->Password = ""; // Mail adresimizin sifresi
          $mail->SetFrom("osgbdeneme@gmail.com", "Özgür OSGB"); // Mail attigimizda gorulecek ismimiz
          $mail->AddAddress($email); // Maili gonderecegimiz kisi yani alici
          $mail->addReplyTo($email, $firstname, $lastname);
            $mail->Subject = "Çalışan Kaydı"; // Konu basligi
          $mail->Body = "<div style='background:#eee;padding:5px;margin:5px;width:300px;'> eposta : ".$email."</div> <br />
           <p><b>Merhaba $firstname, Özgür OSGB'ye çalışan olarak kaydınız yapılmıştır.<br>Lütfen öncelikle aşağıdaki linki tarayıcınızda açarak üyeliğinizi onaylayın</b></p>
           <br>http://142.93.97.101/isg/admin/register/validate.php?username=".$email."&valid_code=".$kod."
           <h4><b>Giriş bilgileriniz:</b></h4>
           <h5><b>Kullanıcı adı:</b></h5> $email
           <h5><b>Şifre:</b></h5> $pass
           <h4><b>Kullanıcı bilgileriniz:</b></h4>
           <h5><b>Kullanıcı türü:</b></h5> $user_type
           <h5><b>Ad:</b></h5> $firstname
           <h5><b>Soyad:</b></h5> $lastname
           <h5><b>Telefon No:</b></h5> $phone
           <h5><b>T.C. Kimlik No:</b></h5> $tc
          "; // Mailin icerigi
          if (!$mail->Send()) {
              ?>
          <script type="text/javascript">
            function getConfirmation() {
              var retVal = confirm("Veritabanına kaydınız yapıldı fakat onay maili gönderilemedi. Lütfen bizimle ilteşime geçin");
              if (retVal == true) {
                return true;
              }
            }
            getConfirmation();

          </script>
<?php
          } else {
              if ($user_type == "İsg Uzmanı 1" || $user_type == "İsg Uzmanı 2" || $user_type == "İsg Uzmanı 3") {
                  $user_auth = 0;
              } elseif ($user_type == "Yönetici") {
                  $user_auth = 1;
              } elseif ($user_type == "Hekim") {
                  $user_auth = 2;
              } elseif ($user_type == "Diğer Sağlık Personeli") {
                  $user_auth = 3;
              } elseif ($user_type == "Ofis Personeli") {
                  $user_auth = 4;
              } elseif ($user_type == "Muhasebe Personeli") {
                  $user_auth = 6;
              } else {
                  $user_auth = 7;
              }
              $passwordHash =md5(md5($pass));
              $add_user = "INSERT INTO `users` (`firstname`, `lastname`, `username`, `phone`, `password`, `valid_code`, `auth`)
             VALUES ('$firstname', '$lastname', '$email', '$phone', '$passwordHash', '$kod', '$user_auth')";
              $stmt2 = $pdo->prepare($add_user);
              $result = $stmt2->execute();
              if (!$result) {
              }

              $sorgu5=$pdo->prepare("SELECT * FROM `users` WHERE `username` = '$email'");
              $sorgu5->execute();
              $users5=$sorgu5-> fetchAll(PDO::FETCH_OBJ);
              foreach ($users5 as $user5) {
                  $id = $user5->id;
              }
             ?>
            <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
              <strong>Yeni Çalışan Eklendi!</strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

              <?php
          }
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title>Çalışanlar</title>
  <link rel="shortcut icon" href="../images/osgb_amblem.ico">
  </link>
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
  <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
  <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
  <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
  <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
 <style>
    .dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {background-color: #ddd;}

.dropdown:hover .dropdown-content {display: block;}

.dropdown:hover .dropbtn {background-color: #3e8e41;}

  </style>
</head>

<body id="page-top">
    <nav class="navbar shadow navbar-expand mb-3 bg-warning topbar static-top">
      <img width="55" height="40" class="rounded-circle img-profile" src="assets/img/nav_brand.jpg" />
      <a class="navbar-brand" title="Anasayfa" style="color: black;" href="index.php"><b>Özgür OSGB</b></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span></button>

      <ul class="navbar-nav navbar-expand mr-auto">
        <li class="nav-item">
        <div class="dropdown no-arrow">
          <a style="color:black;" class="nav-link btn btn-warning dropdown-toggle"type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-building"></i><span>&nbsp;İşletmeler</span></a>
              <div class="dropdown-content" aria-labelledby="dropdownMenu2">
                <a class="dropdown-item" type="button" href="companies.php"><i class="fas fa-stream"></i><span>&nbsp;İşletme Listesi</span></a>
                <a class="dropdown-item" type="button" href="deleted_companies.php"><i class="fas fa-eraser"></i><span>&nbsp;Silinen İşletmeler</span></a>
                <?php
                if($auth == 1){?>
                <a class="dropdown-item" type="button" href="change_validate.php"><i class="fas fa-exchange-alt"></i><span>&nbsp;Onay Bekleyenler</span></a>
                <?php }?>
              </div>
        </div>
        </li>
        <li class="nav-item">
          <a style="color: black;" class="nav-link btn-warning" href="reports.php"><i
              class="fas fa-folder"></i><span>&nbsp;Raporlar</span></a>
        </li>
        <li class="nav-item">
            <a style="color: black;" class="nav-link btn-warning" href="calendar/index.php"><i class="fas fa-calendar-alt"></i><span>&nbsp;Takvim</span></a>
          </li>
        <?php
            if ($auth == 1) {
          ?>
        <li class="nav-item"><a style="color: black;" class="nav-link btn-warning" href="settings.php"><i
              class="fas fa-wrench"></i><span>&nbsp;Ayarlar</span></a></li>
        <li class="nav-item">
        <div class="dropdown no-arrow">
          <button style="color:black;" class="nav-link btn btn-warning dropdown-toggle"type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
              class="fas fa-users"></i><span>&nbsp;Çalışanlar</span></button>
              <div class="dropdown-content" aria-labelledby="dropdownMenu2">
                <a class="dropdown-item" type="button" href="osgb_users.php"><i class="fas fa-stream"></i><span>&nbsp;Çalışan Listesi</span></a>
                <a class="dropdown-item" type="button" href="deleted_workers.php"><i class="fas fa-eraser"></i><span>&nbsp;Silinen Çalışanlar</span></a>
                <a class="dropdown-item" type="button" href="authentication.php"><i class="fas fa-user-edit"></i><span>&nbsp;Yetkilendir</span></a>
              </div>
        </div>
        </li>
        <?php
            }
          ?>
      </ul>
      <ul class="nav navbar-nav navbar-expand flex-nowrap ml-auto">
        <li class="nav-item dropdown no-arrow mx-1">
          <div class="nav-item dropdown no-arrow">
            <?php
              $bildirim_say = $pdo->query("SELECT COUNT(*) FROM `notifications` WHERE `user_id` = '$id' ORDER BY reg_date")->fetchColumn();
                    ?>
          <a href="notifications.php" title="Bildirimler" class="nav-link"
            data-bs-hover-animate="rubberBand">
            <i style="color: black;" class="fas fa-bell fa-fw"></i>
            <span class="badge badge-danger badge-counter"><?= $bildirim_say ?></span></a>
          </div>
        </li>
        <li class="nav-item dropdown no-arrow mx-1">
          <div class="nav-item dropdown no-arrow">
            <a style="color: black;" title="Mesajlar" href="messages.php" class="dropdown-toggle nav-link"
              data-bs-hover-animate="rubberBand">
              <i style="color: black;" class="fas fa-envelope fa-fw"></i>
              <?php
                $mesaj_say = $pdo->query("SELECT COUNT(*) FROM `message` WHERE `kime` = '$ume' ORDER BY tarih")->fetchColumn();
                      ?>
              <span class="badge badge-danger badge-counter"><?=$mesaj_say?></span></a>
          </div>
          <div class="shadow dropdown-list dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown"></div>
        </li>
        <div class="d-none d-sm-block topbar-divider"></div>
        <li class="nav-item">
          <div class="nav-item">
            <a href="profile.php" class="nav-link" title="Profil">
              <span style="color:black;" class="d-none d-lg-inline mr-2 text-600"><?=$fn?> <?=$ln?></span><img
                class="rounded-circle img-profile" src="assets/users/<?=$picture?>"></a>
        </li>
        <div class="d-none d-sm-block topbar-divider"></div>
          <li class="nav-item"><a style="color: black;" title="Çıkış" class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i><span>&nbsp;Çıkış</span></a></li>
          </div>
      </ul>
    </nav>
        <div class="container-fluid">
          <div class="card-header border text-dark bg-light">
          <h1  style="text-align:center;"><b>Çalışanlar</b></h1></div>
          <div class="card shadow-lg">
            <div class="card-body border">
              <div class="form-group col-md-4" style="float:right;">
                <input type="text" class="form-control" id="myInput" onkeyup="myFunction()" placeholder="Çalışan ismi ile ara...">
              </div>
              <div id="dataTable_filter">
                <div>
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@getbootstrap">Yeni Çalışan Ekle</button>
                  <button type="button" class="btn btn-danger" onclick="window.location.href='deleted_workers.php'">Arşiv
                  </button>
                </div>
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content modal-lg">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Çalışan Bilgileri</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form action="osgb_users.php" method="POST">
                          <div class="row">
                            <div class="col-sm-6">
                              <label for="user_type"><strong>Kullanıcı türünü seçin</strong></label>
                              <select class="form-control" placeholder="Kullanıcı Türünü seçin" list="user_type" name="user_type" required>
                                <option value="" disabled selected>Kullanıcı Türü</option>
                                <optgroup label="İsg Uzmanı">
                                  <option value="İsg Uzmanı 1">İsg Uzmanı 1</option>
                                  <option value="İsg Uzmanı 2">İsg Uzmanı 2</option>
                                  <option value="İsg Uzmanı 3">İsg Uzmanı 3</option>
                                </optgroup>
                                <option value="Yönetici">Yönetici</option>
                                <option value="Hekim">Hekim</option>
                                <option value="Ofis Personeli">Ofis Personeli</option>
                                <option value="Diğer Sağlık Personeli">Diğer Sağlık Personeli</option>
                                <option value="Muhasebe Personeli">Muhasebe Personeli</option>
                              </select>
                            </div>
                          </div>
                          <br>
                          <div class="row">
                            <div class="col-sm">
                              <label for="firstname"><strong>Adı</strong></label>
                              <input type="text" class="form-control" placeholder="Adı" name="firstname" required>
                            </div>
                            <div class="col-sm">
                              <label for="lastname"><strong>Soy Adı </strong></label>
                              <input type="text" class="form-control" placeholder="Soy Adı" name="lastname" required>
                            </div>
                          </div>
                          <br>
                          <div class="row">
                            <div class="col-sm-10">
                              <label for="email"><strong>E-mail </strong></label>
                              <input type="email" class="form-control" placeholder="E-mail" name="email" required>
                            </div>
                          </div>
                          <br>
                          <div class="row">
                            <div class="col-sm-6">
                              <label for="phone"><strong>Telefon No </strong></label>
                              <input type="tel" class="form-control" name="phone" placeholder="Tel: 05XXXXXXXXX" pattern="(\d{4})(\d{3})(\d{2})(\d{2})" maxlength="11" required>
                            </div>
                            <div class="col-sm-6">
                              <label for="start_date"><strong>İşe Giriş Tarihi </strong></label>
                              <input type="date" class="form-control" placeholder="İşe giriş" name="start_date" required>
                            </div>
                          </div>
                          <br>
                          <div class="row">
                            <div class="col-sm-6">
                              <label for="tc"><strong>T.C Kimlik No </strong></label>
                              <input type="tel" class="form-control" placeholder="T.C Kimlik No" name="tc" minlength="11" maxlength="11" required>
                            </div>
                          </div>
                          <br>
                          <div style="float: right;">
                            <button id="kayıt" name="kayıt" type="submit" class="btn btn-success">Kaydet</button>
                            <button type="reset" class="btn btn-danger">Sıfırla</button>
                          </div>
                      </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                <table class="table table-striped table-bordered table-hover" id="dataTable">
                  <thead class="thead-dark">
                    <tr>
                      <th>Ad</th>
                      <th>Soyad</th>
                      <th>Çalışma Alanı</th>
                      <th>T.C Kimlik No</th>
                      <th>İşe Giriş Tarihi</th>
                      <th>Düzenle/Sil</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                              $sorgu=$pdo->prepare("SELECT * FROM `osgb_workers` WHERE `deleted` = 0");
                              $sorgu->execute();
                              $workers=$sorgu-> fetchAll(PDO::FETCH_OBJ);
                              foreach ($workers as $key=>$worker) {?>
                    <tr>
                      <td><?= $worker->firstname ?></td>
                      <td><?= $worker->lastname ?></td>
                      <td><?= $worker->user_type ?></td>
                      <td><?= $worker->tc ?></td>
                      <td><?= $worker->start_date ?></td>
                      <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#a<?php echo $key; ?>" data-whatever="@getbootstrap">Düzenle</button></td>
                    </tr>
                    <div class="modal fade" id="a<?php echo $key; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Düzenle</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <form action="osgb_users.php" method="POST">
                              <div class="row">
                                <div class="col-sm-6">
                                  <label><strong>Kullanıcı türünü seçin</strong></label>
                                  <select class="form-control" placeholder="Kullanıcı Türünü seçin" list="user_type" name="user_type" required>
                                    <option value="<?= $worker->user_type ?>" disabled selected><?= $worker->user_type ?></option>
                                    <optgroup label="İsg Uzmanı">
                                      <option value="İsg Uzmanı 1">İsg Uzmanı 1</option>
                                      <option value="İsg Uzmanı 2">İsg Uzmanı 2</option>
                                      <option value="İsg Uzmanı 3">İsg Uzmanı 3</option>
                                    </optgroup>
                                    <option value="Yönetici">Yönetici</option>
                                    <option value="Hekim">Hekim</option>
                                    <option value="Ofis Personeli">Ofis Personeli</option>
                                    <option value="Diğer Sağlık Personeli">Diğer Sağlık Personeli</option>
                                    <option value="Muhasebe Personeli">Muhasebe Personeli</option>
                                  </select>
                                </div>
                              </div>
                              <br>
                              <div class="row">
                                <div class="col-sm-6">
                                  <label for="firstname"><strong>Adı</strong></label>
                                  <input type="text" class="form-control" placeholder="Adı" name="firstname" value="<?= $worker->firstname ?>" required>
                                </div>
                                <div class="col-sm-6">
                                  <label for="lastname"><strong>Soy Adı </strong></label>
                                  <input type="text" class="form-control" placeholder="Soy Adı" name="lastname" value="<?= $worker->lastname ?>" required>
                                </div>
                              </div>
                              <br>
                              <div class="row">
                                <div class="col-sm-10">
                                  <label for="email"><strong>E-mail </strong></label>
                                  <input type="email" class="form-control" placeholder="E-mail" name="email" value="<?= $worker->mail ?>" readonly required>
                                </div>
                              </div>
                              <br>
                              <div class="row">
                                <div class="col-sm-6">
                                  <label for="phone"><strong>Telefon No </strong></label>
                                  <input type="tel" class="form-control" name="phone" placeholder="Tel: 05XXXXXXXXX" pattern="(\d{4})(\d{3})(\d{2})(\d{2})" maxlength="11" value="<?= $worker->phone ?>" required>
                                </div>
                                <div class="col-sm-6">
                                  <label for="start_date"><strong>İşe Giriş Tarihi </strong></label>
                                  <input type="date" class="form-control" placeholder="İşe giriş" name="start_date" value="<?= $worker->start_date ?>" required>
                                </div>
                              </div>
                              <br>
                              <div class="row">
                                <div class="col-sm-6">
                                  <label for="tc"><strong>T.C Kimlik No </strong></label>
                                  <input type="number" class="form-control" placeholder="T.C Kimlik No" name="tc" min="11" maxlength="11" value="<?= $worker->tc ?>" readonly required>
                                </div>
                              </div>
                              <br>
                              <label for="not"><strong>Çalışan hakkında not </strong></label>
                              <textarea class="form-control" id="not" name="not" rows="5" cols="120" style="max-width: 100%;"><?= $worker->worker_text?></textarea>
                              <br>
                              <div style="float: right;">
                                <button id="onay" name="onay" type="submit" class="btn btn-success">Kaydet</button>
                                <button type="submit" class="btn btn-danger" name="sil" id="sil">Sil</button>
                              </div>
                          </div>
                          </form>
                        </div>
                      </div>
                    </div>
              </div>
              <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <td><strong>Ad</strong></td>
                  <td><strong>Soyad</strong></td>
                  <td><strong>Çalışma Alanı</strong></td>
                  <td><strong>T.C Kimlik No</strong></td>
                  <td><strong>İşe Giriş Tarihi</strong></td>
                  <td><strong>Düzenle/Sil</strong></td>
                </tr>
              </tfoot>
              </table>
            </div>
          </div>
        </div>
    </div>
    <footer class="bg-white sticky-footer">
      <div class="container my-auto">
        <div class="text-center my-auto copyright"><span>Copyright © ÖzgürOSGB 2020</span></div>
      </div>
    </footer>
  </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a></div>
  <script>
    function myFunction() {
      // Declare variables
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("myInput");
      filter = input.value.toUpperCase();
      table = document.getElementById("dataTable");
      tr = table.getElementsByTagName("tr");

      // Loop through all table rows, and hide those who don't match the search query
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
          txtValue = td.textContent || td.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        }
      }
    }

  </script>
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/js/chart.min.js"></script>
  <script src="assets/js/bs-init.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
  <script src="assets/js/theme.js"></script>
  <script type="text/javascript">
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>
</body>

</html>
