<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header('Location: ../login.php');
    exit;
}
require '../connect.php';
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
    header('Location: 403.php');
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title>Ayarlar</title>
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
    <div class="card shadow-lg border">
      <div class="card-header bg-light">
        <h1 class="text-dark mb-1" style="text-align: center;"><b>Ayarlar</b></h1>
      </div>
        <div class="card-group">
          <div class="card bg-light"><img class="card-img-top w-50 d-block" style="margin: auto;margin-top:10px;" src="assets/img/kurum_profili.png">
            <div class="card-body">
              <h4 class="card-title"><b>Kurum Profili</b></h4>
              <p class="card-text">Kurumunuzun bilgilerini düzenleyin</p><a class="btn btn-primary" role="button" href="osgb_comp.php" style="margin-top: 30px;">Düzenle</a>
            </div>
          </div>
          <div class="card bg-light"><img class="card-img-top w-50 d-block" style="margin: auto;" src="assets/img/users.png">
            <div class="card-body" style="margin-top:10px;">
              <h4 class="card-title"><b>Kullanıcılar</b></h4>
              <p class="card-text">Çalışanlarınızı ve bilgilerini düzenleyin</p><a class="btn btn-primary" role="button" href="osgb_users.php" style="margin-top: 30px;">Düzenle</a>
            </div>
          </div>
          <div class="card bg-light"><img class="card-img-top w-50 d-block" style="margin: auto; margin-top:10px;" src="assets/img/user.png">
            <div class="card-body">
              <h4 class="card-title"><b>Hesap</b></h4>
              <p class="card-text">Hesap ayarlarınızı düzenleyin</p><a class="btn btn-primary" role="button" href="profile.php" style="margin-top: 30px;">Düzenle</a>
            </div>
          </div>
        </div>
    </div>
      <footer class="bg-white sticky-footer">
        <div class="container my-auto">
          <div class="text-center my-auto copyright"><span>Copyright © ÖzgürOSGB 2020</span></div>
        </div>

      </footer>
      <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/js/chart.min.js"></script>
  <script src="assets/js/bs-init.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
  <script src="assets/js/theme.js"></script>
</body>

</html>
