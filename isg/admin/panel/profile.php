<?php
session_start();
require "../connect.php";
require '../lib/password.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header('Location: ../login.php');
    exit;
}
$id=$_SESSION['user_id'];
$başlangıç=$pdo->prepare("SELECT * FROM users WHERE id = '$id'");
$başlangıç->execute();
$girişler=$başlangıç-> fetchAll(PDO::FETCH_OBJ);
foreach ($girişler as $giriş) {
    $fn = $giriş->firstname;
    $ln = $giriş->lastname;
    $ume = $giriş->username;
    $auth = $giriş->auth;
    $picture= $giriş->picture;
}
if ($auth == 7) {
  $sorgu=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `isletme_id` = '$id' OR `isletme_id_2` = '$id' OR `isletme_id_3` = '$id'");
  $sorgu->execute();
  $comps=$sorgu-> fetchAll(PDO::FETCH_OBJ);
  foreach ($comps as $comp) {
    $company_name = $comp->name;
  }
}
if (isset($_POST['şifre'])) {
    $mevcut = !empty($_POST['mevcut']) ? trim($_POST['mevcut']) : null;
    $yeni = !empty($_POST['yeni']) ? trim($_POST['yeni']) : null;
    $yeni_tekrar = !empty($_POST['yeni_tekrar']) ? trim($_POST['yeni_tekrar']) : null;
    if ($yeni != $yeni_tekrar) {
        ?>
<div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
  <strong>Yeni girilen şifreler eşleşmiyor. Tekrar Deneyiniz</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<?php
    } else {
        $mevcut = md5(md5($mevcut));
        $yeni = md5(md5($yeni));
        $sorgu = $pdo->prepare("SELECT * FROM `users` WHERE `id` = '$id'");
        $sorgu->execute();
        $users=$sorgu-> fetchAll(PDO::FETCH_OBJ);
        foreach ($users as $user) {
            $password = $user->password;
        }
        if ($mevcut != $password) {
            ?>
<div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
  <strong>Mevcut şifrenizi yanlış girdiniz. Lütfen Tekrar Deneyiniz</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<?php
        } else {
            $sql = "UPDATE `users` SET `password` = '$yeni' WHERE `users`.`id` = '$id'";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute();
            if ($result) {
                ?>
<div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
  <strong>Şifreniz başarıyla değiştirildi!</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<?php
            }
        }
    }
}

if (isset($_POST['bilgi_kaydet'])) {
    $firstname = !empty($_POST['firstname']) ? trim($_POST['firstname']) : null;
    $lastname = !empty($_POST['lastname']) ? trim($_POST['lastname']) : null;
    $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : null;
    $tc = !empty($_POST['tc']) ? trim($_POST['tc']) : null;
    $start_date = !empty($_POST['start_date']) ? trim($_POST['start_date']) : null;

    $sql = "UPDATE `osgb_workers` SET `firstname` = '$firstname', `lastname` = '$lastname', `phone` = '$phone', `tc` = '$tc', `start_date` = '$start_date' WHERE `mail` = '$ume'";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute();
    $sql2 = "UPDATE `users` SET `firstname` = '$firstname', `lastname` = '$lastname', `phone` = '$phone' WHERE `id` = '$id'";
    $stmt2 = $pdo->prepare($sql2);
    $result2 = $stmt2->execute();
    if ($result & $result2) {
        ?>
<div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
  <strong>Bilgileriniz düzenlendi!</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<?php
    }
}
if(isset($_FILES['file']) && isset($_POST['save_image'])){
    $file= $_FILES['file'];
    $fileName = $_FILES['file']['name'];
    $fileTmp = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $filesError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    $random = rand(1,99999999999);
    $fileExt = explode('.',$_FILES['file']['name']);
    $fileActualExt = strtolower(end($fileExt));
    $allowed = array('jpg','jpeg','png');
    if(in_array($fileActualExt,$allowed)){
        if($_FILES['file']['error'] ===  0){
            if($_FILES['file']['size'] < 10000000){
                $fileNameNew = $random.$fn.$ln.$id.".".$fileActualExt;
                $fileDestination = 'assets/users/'.$fileNameNew;
                move_uploaded_file($_FILES['file']['tmp_name'],$fileDestination);
                $sql = "UPDATE users SET picture='$fileNameNew' WHERE id ='$id';";
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute();
                if ($result) {
                  ?>
                  <div class='alert alert-primary alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
                    <strong>Profil fotoğrafınız güncellendi!</strong>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
                      <span aria-hidden='true'>&times;</span>
                    </button>
                  </div>
                  <script type="text/javascript">
                    window.location.href = "profile.php";
                  </script>
                  <?php
                }
                else {
                  ?>
                  <div class='alert alert-danger alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
                    <strong>Database sorunu. Tekrar Deneyiniz. Sorun devam ediyorsa <b>laserayyildiz@gmail.com</b> adresine sorunla ilgili mail atabilirsiniz!</strong>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
                      <span aria-hidden='true'>&times;</span>
                    </button>
                  </div>
                  <?php

                }
            }else{
              ?>
              <div class='alert alert-danger alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
                <strong>Dosya çok büyük!</strong>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
                  <span aria-hidden='true'>&times;</span>
                </button>
              </div>
              <?php
            }
        }else{
          ?>
          <div class='alert alert-danger alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
            <strong>Dosya yüklenirken bir hatayla karşılaşıldı!</strong>
            <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>
          <?php
        }
    }else{
      ?>
      <div class='alert alert-danger alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
        <strong>Dosya tipi uygun değil!</strong>
        <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>
      <?php
    }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title>Profil</title>
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
      <?php if ($auth == 7){ ?>
        <a class="navbar-brand" title="Anasayfa" style="color: black;" href="companies/<?=$company_name?>/index.php?tab=genel_bilgiler"><b><?= mb_convert_case($company_name, MB_CASE_TITLE, "UTF-8") ?></b></a>
      <?php }
      else {?>
        <a class="navbar-brand" title="Anasayfa" style="color: black;" href="index.php"><b>Özgür OSGB</b></a>
        <?php
        }
        ?>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span></button>
        <?php if ($auth != 7): ?>
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
        <?php endif; ?>

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
          <h3 class="text-dark mb-4">Profil</h3>
          <div class="row mb-3">
            <div class="col-lg-6" style="margin: auto; ">
              <div class="card shadow-lg mb-3">
                <div class="card-header py-3">
                  <p class="text-primary m-0 font-weight-bold">Profil Resmi</p>
                </div>
                <div class="card-body text-center shadow">
                  <img class="img-thumbnail mb-3 mt-4" src="assets/users/<?=$picture?>" width="160" height="160">
                  <form action="profile.php" method="POST" enctype="multipart/form-data">
                    <input class="btn btn-dark" type="file" name="file" id="file" style="width:300px;" />
                    <input class="btn btn-primary" type="submit" style="height:44px;width:300px;" value="Kaydet" name="save_image" />
                  </form>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="card shadow-lg mb-3">
                <div class="card-header py-3">
                  <p class="text-primary m-0 font-weight-bold">Şifre</p>
                </div>
                <div class="card-body text-center shadow">
                  <form action="profile.php" method="POST" enctype="multipart/form-data" autocomplete="off">
                    <div class="form-group">
                      <label style="float:left;"><b>Mevcut Şifre</b></label>
                      <div class="input-group" id="show_hide_password">
                        <input class="form-control" type="password" name="mevcut" autocomplete="off" required>
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon1"><a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label style="float:left;"><b>Yeni Şifre</b></label>
                      <div class="input-group" id="show_hide_password">
                        <input class="form-control" type="password" minlength="8" name="yeni" autocomplete="new-password" required>
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon1"><a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label style="float:left;"><b>Yeni Şifre Tekrar</b></label>
                      <div class="input-group" id="show_hide_password">
                        <input class="form-control" type="password" minlength="8" name="yeni_tekrar" autocomplete="new-password" required>
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon1"><a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>
                        </div>
                      </div>
                    </div>
                    <div>
                      <button name="şifre" id="şifre" type="submit" style="width:200px;float:left" class="btn btn-success">Değiştir</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-lg-12" style="margin: auto;">
              <div class="row mb-3">
                <div class="col">
                  <div class="card shadow-lg mb-3">
                    <div class="card-header py-3">
                      <p class="text-primary m-0 font-weight-bold">Kullanıcı Bilgileri</p>
                    </div>
                    <div class="card-body shadow">
                      <form action="profile.php" method="POST">
                        <?php
                                  $sorgu3=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$id'");
                                  $sorgu3->execute();
                                  $users=$sorgu3-> fetchAll(PDO::FETCH_OBJ);
                                  foreach ($users as $user) {
                                      $mail = $user->username;
                                      $firstname = $user->firstname;
                                      $lastname = $user->lastname;
                                      $phone = $user->phone;
                                  }
                                  $sorgu4=$pdo->prepare("SELECT * FROM `osgb_workers` WHERE `mail` = '$mail'");
                                  $sorgu4->execute();
                                  $users=$sorgu4-> fetchAll(PDO::FETCH_OBJ);
                                  foreach ($users as $user) {
                                      $tc = $user->tc;
                                      $type = $user->user_type;
                                      $date = $user->start_date;
                                  }
                                  ?>
                        <div class="form-row">
                          <div class="col-sm-4">
                            <div class="form-group">
                              <label for="email"><strong>E-mail</strong></label>
                              <input class="form-control" type="email" name="username" readonly value="<?= $mail ?>">
                            </div>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="col-sm-4">
                            <div class="form-group">
                              <label for="first_name">
                                <strong>İsim</strong><br></label>
                              <input name="firstname" class="form-control" type="text" value="<?= $firstname ?>" required>
                            </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group">
                              <label for="last_name">
                                <strong>Soy İsim</strong><br></label>
                              <input name="lastname" class="form-control" type="text" value="<?= $lastname ?>" required>
                            </div>
                          </div>
                        </div>

                        <div class="form-row">
                          <?php
                                      if ($auth == 0) {?>
                          <div class="col-sm-4">
                            <div class="form-group">
                              <label for="tc"><strong>T.C. Kimlik No</strong><br></label>
                              <input class="form-control" type="phone" minlength="11" maxlength="11" name="tc" value="<?= $tc ?>" required>
                            </div>
                          </div>
                          <?php } ?>
                          <div class="col-sm-4">
                            <div class="form-group">
                              <label for="phone">
                                <strong>Telefon No</strong><br></label>
                              <input name="phone" class="form-control" type="phone" pattern="(\d{4})(\d{3})(\d{2})(\d{2})" maxlength="11" minlength="11" value="<?= $phone ?>" required>
                            </div>
                          </div>
                        </div>
                        <?php
                                    if ($auth == 0) {?>
                        <div class="form-row">
                          <div class="col-sm-4">
                            <div class="form-group">
                              <label for="user_type">
                                <strong>Kullanıcı Türü</strong><br></label>
                              <input name="user_type" class="form-control" type="text" value="<?= $type ?>" required readonly>
                            </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group">
                              <label for="start_date">
                                <strong>İşe Giriş Tarihi</strong><br></label>
                              <input name="start_date" class="form-control" type="date" value="<?= $date ?>" required>
                            </div>
                          </div>
                        </div>
                        <?php } ?>
                        <button name="bilgi_kaydet" id="bilgi_kaydet" type="submit" style="width:200px;" class="btn btn-success">Kaydet</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      <footer class="bg-white sticky-footer">
        <div class="container my-auto">
          <div class="text-center my-auto copyright"><span>Copyright © ÖzgürOSGB 2020</span></div>
        </div>
      </footer>
    </div>
  </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a></div>
  <script src="assets/js/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      $("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if ($('#show_hide_password input').attr("type") == "text") {
          $('#show_hide_password input').attr('type', 'password');
          $('#show_hide_password i').addClass("fa-eye-slash");
          $('#show_hide_password i').removeClass("fa-eye");
        } else if ($('#show_hide_password input').attr("type") == "password") {
          $('#show_hide_password input').attr('type', 'text');
          $('#show_hide_password i').removeClass("fa-eye-slash");
          $('#show_hide_password i').addClass("fa-eye");
        }
      });
    });

  </script>
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
