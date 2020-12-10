<?php session_start();
$isim = 'yeni_yapı';
require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
require_once('../../calendar/utils/auth.php');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header('Location: ../../../login.php');
    exit;
}

require '../../../connect.php';
$id=$_SESSION['user_id'];

$başlangıç=$pdo->prepare("SELECT * FROM users WHERE id = '$id'");
$başlangıç->execute();
$girişler=$başlangıç-> fetchAll(PDO::FETCH_OBJ);
foreach ($girişler as $giriş) {
    $fn = $giriş->firstname;
    $ln = $giriş->lastname;
    $un = $giriş->username;
    $ume = $giriş->username;
    $yetkili_auth = $giriş->auth;
    $picture= $giriş->picture;
    $giriş_auth = $giriş->auth;
}

$yetki=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `name` = '$isim'");
$yetki->execute();
$yets=$yetki-> fetchAll(PDO::FETCH_OBJ);
foreach ($yets as $yet) {
    $company_id=$yet->id;
    $yetkili_uzman_id = $yet->uzman_id;
    $yetkili_uzman_id_2 = $yet->uzman_id_2;
    $yetkili_uzman_id_3 = $yet->uzman_id_3;

    $yetkili_hekim_id = $yet->hekim_id;
    $yetkili_hekim_id_2 = $yet->hekim_id_2;
    $yetkili_hekim_id_3 = $yet->hekim_id_3;

    $yetkili_id = $yet->yetkili_id;

    $yetkili_saglık_p_id = $yet->saglık_p_id;
    $yetkili_saglık_p_id_2 = $yet->saglık_p_id_2;

    $yetkili_ofis_p_id = $yet->ofis_p_id;
    $yetkili_ofis_p_id_2 = $yet->ofis_p_id_2;

    $yetkili_muhasebe_p_id = $yet->muhasebe_p_id;
    $yetkili_muhasebe_p_id_2 = $yet->muhasebe_p_id_2;
}
if ($yetkili_auth != 1) {
    if ($yetkili_hekim_id == $id) {
        echo "";
    }elseif ($yetkili_hekim_id_2 == $id) {
        echo "";
    }elseif ($yetkili_hekim_id_3 == $id) {
        echo "";
    } elseif ($yetkili_uzman_id == $id) {
        echo "";
    }elseif ($yetkili_uzman_id_2 == $id) {
        echo "";
    }elseif ($yetkili_uzman_id_3 == $id) {
        echo "";
    } elseif ($yetkili_id == $id) {
        echo "";
    } elseif ($yetkili_saglık_p_id == $id) {
        echo "";
    }elseif ($yetkili_saglık_p_id_2 == $id) {
        echo "";
    } elseif ($yetkili_ofis_p_id == $id) {
        echo "";
    }elseif ($yetkili_ofis_p_id_2 == $id) {
        echo "";
    } elseif ($yetkili_muhasebe_p_id == $id) {
        echo "";
    }elseif ($yetkili_muhasebe_p_id_2 == $id) {
        echo "";
    } else {
        header('Location: ../../403.php');
    }
}

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title><?= $isim ?></title>
  <link rel="shortcut icon" href="../../../images/osgb_amblem.ico"></link>
  <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
  <link rel="stylesheet" href="../../assets/fonts/fontawesome-all.min.css">
  <link rel="stylesheet" href="../../assets/fonts/font-awesome.min.css">
  <link rel="stylesheet" href="../../assets/fonts/ionicons.min.css">
  <link rel="stylesheet" href="../../assets/fonts/fontawesome5-overrides.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
  <link href='../../calendar/css/fullcalendar.min.css' rel='stylesheet' />
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

    #calendar {
      max-width: %100;
    }
    .nocheckbox {
      display: none;
    }

    .label-on {
        border-radius: 3px;
        background: red;
        color: #ffffff;
        padding: 10px;
        border: 1px solid red;
        display: table-cell;
    }

    .label-off {
        border-radius: 3px;
        background: white;
        border: 1px solid red;
        padding: 10px;
        display: table-cell;
    }

      #calendar a.fc-event {
      color: #fff; /* bootstrap default styles make it black. undo */
      background-color: #0065A6;
    }

  </style>
</head>

<body id="page-top">
  <nav class="navbar shadow navbar-expand mb-3 bg-warning topbar static-top">
      <img width="55" height="40" class="rounded-circle img-profile" src="../../assets/img/nav_brand.jpg" />
      <a class="navbar-brand" title="Anasayfa" style="color: black;" href="../../index.php"><b>Özgür OSGB</b></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span></button>

      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
        <div class="dropdown no-arrow">
          <a style="color:black;" class="nav-link btn btn-warning dropdown-toggle"type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-building"></i><span>&nbsp;İşletmeler</span></a>
              <div class="dropdown-content" aria-labelledby="dropdownMenu2">
                <a class="dropdown-item" type="button" href="../../companies.php"><i class="fas fa-stream"></i><span>&nbsp;İşletme Listesi</span></a>
                <a class="dropdown-item" type="button" href="../../deleted_companies.php"><i class="fas fa-eraser"></i><span>&nbsp;Silinen İşletmeler</span></a>
                <?php
                if($giriş_auth == 1){?>
                <a class="dropdown-item" type="button" href="../../change_validate.php"><i class="fas fa-exchange-alt"></i><span>&nbsp;Onay Bekleyenler</span></a>
                <?php }?>
              </div>
        </div>
        </li>
        <li class="nav-item">
          <a style="color: black;" class="nav-link btn-warning" href="../../reports.php"><i
              class="fas fa-folder"></i><span>&nbsp;Raporlar</span></a>
        </li>
        <li class="nav-item">
            <a style="color: black;" class="nav-link btn-warning" href="../../calendar/index.php"><i class="fas fa-calendar-alt"></i><span>&nbsp;Takvim</span></a>
          </li>
        <?php
            if ($giriş_auth == 1) {
          ?>
        <li class="nav-item"><a style="color: black;" class="nav-link btn-warning" href="../../settings.php"><i
              class="fas fa-wrench"></i><span>&nbsp;Ayarlar</span></a></li>
        <li class="nav-item">
        <div class="dropdown no-arrow">
          <button style="color:black;" class="nav-link btn btn-warning dropdown-toggle"type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
              class="fas fa-users"></i><span>&nbsp;Çalışanlar</span></button>
              <div class="dropdown-content" aria-labelledby="dropdownMenu2">
                <a class="dropdown-item" type="button" href="../../osgb_users.php"><i class="fas fa-stream"></i><span>&nbsp;Çalışan Listesi</span></a>
                <a class="dropdown-item" type="button" href="../../deleted_workers.php"><i class="fas fa-eraser"></i><span>&nbsp;Silinen Çalışanlar</span></a>
                <a class="dropdown-item" type="button" href="../../authentication.php"><i class="fas fa-user-edit"></i><span>&nbsp;Yetkilendir</span></a>
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
          <a href="../../notifications.php" title="Bildirimler" class="nav-link"
            data-bs-hover-animate="rubberBand"><span
                class="badge badge-danger badge-counter">3+</span><i style="color: black;"
                class="fas fa-bell fa-fw"></i></a>
          </div>
        </li>
        <li class="nav-item dropdown no-arrow mx-1">
          <div class="nav-item dropdown no-arrow">
            <a style="color: black;" title="Mesajlar" href="../../messages.php" class="dropdown-toggle nav-link"
              data-bs-hover-animate="rubberBand">
              <i style="color: black;" class="fas fa-envelope fa-fw"></i>
              <?php
                    $msg=$pdo->prepare("SELECT * FROM `message` WHERE `kime` = '$ume' ORDER BY tarih");
                    $msg->execute();
                    $messages=$msg-> fetchAll(PDO::FETCH_OBJ);
                    $i = 0;
                    foreach ($messages as $key=>$message) {
                        $i++;
                    }
                      ?>
              <span class="badge badge-danger badge-counter"><?=$i?></span></a>
          </div>
          <div class="shadow dropdown-list dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown"></div>
        </li>
        <div class="d-none d-sm-block topbar-divider"></div>
        <li class="nav-item">
          <div class="nav-item">
            <a href="../../profile.php" class="nav-link" title="Profil">
              <span style="color:black;" class="d-none d-lg-inline mr-2 text-600"><?=$fn?> <?=$ln?></span><img
                class="rounded-circle img-profile" src="../../assets/users/<?=$picture?>"></a>
        </li>
        <div class="d-none d-sm-block topbar-divider"></div>
          <li class="nav-item"><a style="color: black;" title="Çıkış" class="nav-link" href="../../logout.php"><i class="fas fa-sign-out-alt"></i><span>&nbsp;Çıkış</span></a></li>
      </ul>
      </div>
      </nav>
  <div class="container">
    <div class="card" style="width:%100">
      <div class="card-header" style="text-align:center;">
        <h1><b><?= mb_convert_case($isim, MB_CASE_TITLE, "UTF-8")?></b></h1>
      </div>
      <div class="card-body">
        <div class="card-deck">
          <div class="card">
            <img class="card-img-top" src="../svg/file-signature-solid.svg" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">Genel Bilgiler</h5>
              <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
              <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
            </div>
          </div>
          <div class="card">
            <img class="card-img-top" src="..." alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">Card title</h5>
              <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
              <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
            </div>
          </div>
          <div class="card">
            <img class="card-img-top" src="..." alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">Card title</h5>
              <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
              <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
            </div>
          </div>
        </div>
        <br>
        <div class="card-deck">
          <div class="card">
            <img class="card-img-top" src="../svg/file-signature-solid.svg" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">Genel Bilgiler</h5>
              <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
              <a href="#"></a>
            </div>
          </div>
          <div class="card">
            <img class="card-img-top" src="..." alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">Card title</h5>
              <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
              <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
            </div>
          </div>
          <div class="card">
            <img class="card-img-top" src="..." alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">Card title</h5>
              <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
              <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
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
  <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
  <script src="../../assets/js/jquery.min.js"></script>
  <script src="../../assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="../../assets/js/chart.min.js"></script>
  <script src="../../assets/js/bs-init.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
  <script src="../../assets/js/theme.js"></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js'></script>
  <script src='../../calendar/js/fullcalendar.min.js'></script>
  <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js'></script>

</body>

</html>
