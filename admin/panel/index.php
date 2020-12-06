<?php
session_start();
require '../connect.php';

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
    $picture= $giriş->picture;
    $auth = $giriş->auth;
}
if ($auth == 5 || $auth == 7) {
    header('Location: 403.php');
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <link rel="shortcut icon" href="../images/osgb_amblem.ico">
  </link>
  <title>Ana Sayfa</title>
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
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
  <div>
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
          <a href="notifications.php" title="Bildirimler" class="nav-link"
            data-bs-hover-animate="rubberBand"><span
                class="badge badge-danger badge-counter">3+</span><i style="color: black;"
                class="fas fa-bell fa-fw"></i></a>
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
            <a href="profile.php" class="nav-link" title="Profil" data-bs-hover-animate="tada">
              <span style="color:black;" class="d-none d-lg-inline mr-2 text-600"><?=$fn?> <?=$ln?></span><img
                class="rounded-circle img-profile" src="assets/users/<?=$picture?>"></a>
        </li>
        <div class="d-none d-sm-block topbar-divider"></div>
          <li class="nav-item"><a style="color: black;" title="Çıkış" class="nav-link" href="logout.php" data-bs-hover-animate="flip"><i class="fas fa-sign-out-alt" ></i><span>&nbsp;Çıkış</span></a></li>
      </ul>
  </div>
  </nav>
  <div class="container-fluid">
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
      <h3 class="text-dark mb-0">Ana Sayfa</h3>
    </div>
    <div class="row">
      <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-left-primary py-2">
          <div class="card-body">
            <div class="row align-items-center no-gutters">
              <div class="col mr-2">
                <div class="text-uppercase text-primary font-weight-bold text-xs mb-1"><span
                    style="min-width: 0px;max-width: 0px;">Anlaşılan işletme sayısı</span></div>
                <?php
                $isletme_say = $pdo->query('SELECT COUNT(*) FROM `coop_companies` WHERE `change` = 1')->fetchColumn();
                  ?>
                <div class="text-dark font-weight-bold h5 mb-0"><span><?= $isletme_say ?></span></div>
              </div>
              <div class="col-auto"><i class="fa fa-group fa-2x text-gray-300"></i></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-left-success py-2">
          <div class="card-body">
            <div class="row align-items-center no-gutters">
              <div class="col mr-2">
                <?php
                  $calisan_say = $pdo->query('SELECT COUNT(*) FROM `coop_workers`')->fetchColumn();
                ?>
                <div class="text-uppercase text-success font-weight-bold text-xs mb-1"><span>toplam çalışan
                    sayısı</span></div>
                <div class="text-dark font-weight-bold h5 mb-0"><span><?= $calisan_say ?></span></div>
              </div>
              <div class="col-auto"><i class="fas fa-user fa-2x text-gray-300"></i></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-left-info py-2">
          <div class="card-body">
            <div class="row align-items-center no-gutters">
              <div class="col mr-2">
                <div class="text-uppercase text-info font-weight-bold text-xs mb-1"><span>Toplam Ekipman sayısı</span>
                </div>
                <div class="row no-gutters align-items-center">
                  <div class="col-auto">
                    <?php
                      $ekipman_say = $pdo->query('SELECT COUNT(*) FROM `equipment`')->fetchColumn();
                    ?>
                    <div class="text-dark font-weight-bold h5 mb-0 mr-3"><span><?= $ekipman_say ?></span></div>
                  </div>
                </div>
              </div>
              <div class="col-auto"><i class="fas fa-truck-loading fa-2x text-gray-300"></i></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-left-warning py-2">
          <div class="card-body">
            <div class="row align-items-center no-gutters">
              <div class="col mr-2">
                <?php
                  $gorev_say = $pdo->query('SELECT COUNT(*) FROM `events`')->fetchColumn();
                ?>
                <div class="text-uppercase text-warning font-weight-bold text-xs mb-1"><span>Planlanmış Etkinlik
                    Sayısı</span></div>
                <div class="text-dark font-weight-bold h5 mb-0"><span><?= $gorev_say ?></span></div>
              </div>
              <div class="col-auto"><i class="fas fa-pencil-alt fa-2x text-gray-300"></i></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-7 col-xl-8">
        <div class="card shadow mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="text-primary font-weight-bold m-0">Aylara göre anlaşılan işletme sayıları</h6>
          </div>
          <?php
                    $sorgu=$pdo->prepare("SELECT MONTH(contract_date) as month FROM coop_companies WHERE `change` = 1");
                    $sorgu->execute();
                    $companies=$sorgu->fetchAll(PDO::FETCH_OBJ);
                    $ocak = 0; $şubat = 0; $mart = 0; $nisan = 0; $mayıs = 0; $haziran = 0; $temmuz = 0; $ağustos = 0; $eylül = 0; $ekim = 0; $kasım = 0; $aralık = 0;
                    foreach ($companies as $company) {
                        if ($company->month == "1") {
                            $ocak += 1;
                        } elseif ($company->month == "2") {
                            $şubat += 1;
                        } elseif ($company->month == "3") {
                            $mart += 1;
                        } elseif ($company->month == "4") {
                            $nisan += 1;
                        } elseif ($company->month == "5") {
                            $mayıs += 1;
                        } elseif ($company->month == "6") {
                            $haziran += 1;
                        } elseif ($company->month == "7") {
                            $temmuz += 1;
                        } elseif ($company->month == "8") {
                            $ağustos += 1;
                        } elseif ($company->month == "9") {
                            $eylül += 1;
                        } elseif ($company->month == "10") {
                            $ekim += 1;
                        } elseif ($company->month == "11") {
                            $kasım += 1;
                        } elseif ($company->month == "12") {
                            $aralık += 1;
                        }
                    }
                      ?>
          <div class="card-body">
            <div class="chart-area"><canvas
                data-bs-chart="{&quot;type&quot;:&quot;line&quot;,&quot;data&quot;:{&quot;labels&quot;:[&quot;Ocak&quot;,&quot;Şubat&quot;,&quot;Mart&quot;,&quot;Nisan&quot;,&quot;Mayıs&quot;,&quot;Haziran&quot;,&quot;Temmuz&quot;,&quot;Ağustos&quot;,&quot;Eylül&quot;,&quot;Ekim&quot;,&quot;Kasım&quot;,&quot;Aralık&quot;],&quot;datasets&quot;:[{&quot;label&quot;:&quot;İşletme Sayısı&quot;,&quot;fill&quot;:true,&quot;data&quot;:[&quot;<?=$ocak?>&quot;,&quot;<?=$şubat?>&quot;,&quot;<?=$mart?>&quot;,&quot;<?=$nisan?>&quot;,&quot;<?=$mayıs?>&quot;,&quot;<?=$haziran?>&quot;,&quot;<?=$temmuz?>&quot;,&quot;<?=$ağustos?>&quot;,&quot;<?=$eylül?>&quot;,&quot;<?=$ekim?>&quot;,&quot;<?=$kasım?>&quot;,&quot;<?=$aralık?>&quot;],&quot;backgroundColor&quot;:&quot;rgba(78, 115, 223, 0.05)&quot;,&quot;borderColor&quot;:&quot;rgba(78, 115, 223, 1)&quot;,&quot;borderWidth&quot;:&quot;3&quot;}]},&quot;options&quot;:{&quot;maintainAspectRatio&quot;:false,&quot;legend&quot;:{&quot;display&quot;:false},&quot;title&quot;:{&quot;display&quot;:false},&quot;scales&quot;:{&quot;xAxes&quot;:[{&quot;gridLines&quot;:{&quot;color&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;zeroLineColor&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;drawBorder&quot;:false,&quot;drawTicks&quot;:false,&quot;borderDash&quot;:[&quot;10&quot;],&quot;zeroLineBorderDash&quot;:[&quot;10&quot;],&quot;drawOnChartArea&quot;:true},&quot;ticks&quot;:{&quot;fontColor&quot;:&quot;#858796&quot;,&quot;beginAtZero&quot;:false,&quot;padding&quot;:20}}],&quot;yAxes&quot;:[{&quot;gridLines&quot;:{&quot;color&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;zeroLineColor&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;drawBorder&quot;:false,&quot;drawTicks&quot;:false,&quot;borderDash&quot;:[&quot;10&quot;],&quot;zeroLineBorderDash&quot;:[&quot;10&quot;]},&quot;ticks&quot;:{&quot;fontColor&quot;:&quot;#858796&quot;,&quot;beginAtZero&quot;:false,&quot;padding&quot;:20}}]}}}"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-5 col-xl-4">
        <div class="card shadow mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="text-primary font-weight-bold m-0"></h6>
            <h6 class="text-primary font-weight-bold m-0">Türlerine Göre işletmeler</h6>
            <p></p>
          </div>
          <div class="card-body">
            <?php
                      $plot=$pdo->prepare("SELECT comp_type as type FROM coop_companies WHERE `change` = 1");
                      $plot->execute();
                      $hizmet = 0; $sağlık = 0; $sanayi = 0; $tarım = 0; $diğer = 0;
                      $tables=$plot->fetchAll(PDO::FETCH_OBJ);
                      foreach ($tables as $table) {
                          if ($table->type == "Hizmet") {
                              $hizmet += 1;
                          } elseif ($table->type == "Sağlık") {
                              $sağlık += 1;
                          } elseif ($table->type == "Sanayi") {
                              $sanayi += 1;
                          } elseif ($table->type == "Tarım") {
                              $tarım += 1;
                          } else {
                              $diğer += 1;
                          }
                      }
                        ?>
            <div class="chart-area"><canvas
                data-bs-chart="{&quot;type&quot;:&quot;doughnut&quot;,&quot;data&quot;:{&quot;labels&quot;:[&quot;Hizmet&quot;,&quot;Sağlık&quot;,&quot;Sanayi&quot;,&quot;Tarım&quot;,&quot;Diğer&quot;],&quot;datasets&quot;:[{&quot;label&quot;:&quot;&quot;,&quot;backgroundColor&quot;:[&quot;#4e73df&quot;,&quot;#1cc88a&quot;,&quot;#36b9cc&quot;,&quot;rgba(255,0,0,0.7)&quot;,&quot;orange&quot;],&quot;borderColor&quot;:[&quot;#ffffff&quot;,&quot;#ffffff&quot;,&quot;#ffffff&quot;,&quot;#ffffff&quot;,&quot;#ffffff&quot;],&quot;data&quot;:[&quot;<?=$hizmet?>&quot;,&quot;<?=$sağlık?>&quot;,&quot;<?=$sanayi?>&quot;,&quot;<?=$tarım?>&quot;,&quot;<?=$diğer?>&quot;]}]},&quot;options&quot;:{&quot;maintainAspectRatio&quot;:false,&quot;legend&quot;:{&quot;display&quot;:false},&quot;title&quot;:{&quot;display&quot;:false}}}"></canvas>
            </div>
            <div class="text-center small mt-4"><span class="mr-2"><i
                  class="fas fa-circle text-primary"></i>Hizmet</span>
              <span class="mr-2"><i class="fas fa-circle text-success"></i>Sağlık</span>
              <span class="mr-2"><i class="fas fa-circle text-info"></i>Sanayi</span>
              <span class="mr-2"><i class="fas fa-circle text-primary"
                  style="color: rgba(255,0,0,0.7);filter: brightness(103%) contrast(200%) grayscale(0%) hue-rotate(137deg) invert(0%);"></i>Tarım</span>
              <span class="mr-2"><i class="fas fa-circle text" style="color: orange;"></i>Diğer</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="text-primary font-weight-bold m-0">Tehlikesine göre işletmeler</h6>
          </div>
          <div class="card-body">
            <?php
              $az_tehlikeli = $pdo->query('SELECT COUNT(*) FROM `coop_companies` WHERE `danger_type` = 1')->fetchColumn();
              $orta_tehlikeli = $pdo->query('SELECT COUNT(*) FROM `coop_companies` WHERE `danger_type` = 2')->fetchColumn();
              $cok_tehlikeli = $pdo->query('SELECT COUNT(*) FROM `coop_companies` WHERE `danger_type` = 3')->fetchColumn();
              $az_tehlikeli_say = $az_tehlikeli/$isletme_say*100;
              $orta_tehlikeli_say = $orta_tehlikeli/$isletme_say*100;
              $cok_tehlikeli_say = $cok_tehlikeli/$isletme_say*100;
            ?>

              <h4 class="small font-weight-bold">Az Tehlikeli İşletmeler<span class="float-right"><?= $az_tehlikeli_say ?>%</span></h4>
              <div class="progress mb-4">
                <div class="progress-bar progress-bar-animated progress-bar-striped bg-success" aria-valuenow="<?= $az_tehlikeli_say ?>" aria-valuemin="0" aria-valuemax="100"
                  style="width: <?= $az_tehlikeli_say ?>%;"><span class="sr-only"><?= $az_tehlikeli_say ?>%</span></div>
              </div>
              <h4 class="small font-weight-bold">Orta Tehlikeli İşletmeler<span class="float-right"><?= $orta_tehlikeli_say ?>%</span></h4>
              <div class="progress mb-4">
                <div class="progress-bar progress-bar-animated progress-bar-striped bg-warning" aria-valuenow="<?= $orta_tehlikeli_say ?>" aria-valuemin="0" aria-valuemax="100"
                  style="width: <?= $orta_tehlikeli_say ?>%;"><span class="sr-only"><?= $orta_tehlikeli_say ?>%</span></div>
              </div>
              <h4 class="small font-weight-bold">Çok Tehlikeli İşletmeler<span class="float-right"></span><span
                  class="float-right"><?= $cok_tehlikeli_say ?>%</span></h4>
              <div class="progress mb-4">
                <div class="progress-bar progress-bar-animated progress-bar-striped bg-danger" aria-valuenow="<?= $cok_tehlikeli_say ?>" aria-valuemin="0" aria-valuemax="100"
                  style="width: <?= $cok_tehlikeli_say ?>%;"><span class="sr-only"><?= $cok_tehlikeli_say ?>%</span></div>
              </div>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="row">
          <div class="col-lg-6 mb-4">
            <div class="card text-white bg-warning shadow">
              <div class="card-body">
                <p class="m-0">Kısa Zamanda Doldurulması Gereken Raporlar</p>
                <p class="text-white-50 small m-0">55</p>
              </div>
            </div>
          </div>
          <div class="col-lg-6 mb-4">
            <div class="card text-white bg-danger shadow">
              <div class="card-body">
                <p class="m-0">Acil Olarak Doldurulması Gereken Raporlar</p>
                <p class="text-white-50 small m-0">15</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card text-white bg-primary shadow">
              <div class="card-body">
                <p class="m-0">Doldurulmuş Raporlar</p>
                <p class="text-white-50 small m-0">350</p>
              </div>
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
  </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
  </div>
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/js/chart.min.js"></script>
  <script src="assets/js/bs-init.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
  <script src="assets/js/theme.js"></script>
</body>

</html>
