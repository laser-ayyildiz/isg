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
    $un = $giriş->username;
    $ume = $giriş->username;
    $picture= $giriş->picture;
    $auth = $giriş->auth;
}
if ($auth == 5 || $auth == 7) {
    header('Location: 403.php');
}
$ekle=$pdo->prepare("SELECT * FROM osgb_workers WHERE id = '$id'");
$ekle->execute();
$girişler=$ekle-> fetchAll(PDO::FETCH_OBJ);
foreach ($girişler as $giriş) {
    $fn = $giriş->firstname;
    $ln = $giriş->lastname;
    $un = $giriş->username;
    $ume = $giriş->username;
}
if (isset($_POST['kaydet'])) {
    $comp_type = !empty($_POST['comp_type']) ? trim($_POST['comp_type']) : null;
    $name = !empty($_POST['name']) ? trim($_POST['name']) : null;
    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : null;
    $is_veren = !empty($_POST['is_veren']) ? trim($_POST['is_veren']) : null;
    $city = !empty($_POST['countrySelect']) ? trim($_POST['countrySelect']) : null;
    $town = !empty($_POST['citySelect']) ? trim($_POST['citySelect']) : null;
    $contract_date = !empty($_POST['contract_date']) ? trim($_POST['contract_date']) : null;

    $uzman_id = !empty($_POST['uzman_id']) ? trim($_POST['uzman_id']) : 0;
    $uzman_id_2 = !empty($_POST['uzman_id_2']) ? trim($_POST['uzman_id_2']) : 0;
    $uzman_id_3 = !empty($_POST['uzman_id_3']) ? trim($_POST['uzman_id_3']) : 0;

    $hekim_id = !empty($_POST['hekim_id']) ? trim($_POST['hekim_id']) : 0;
    $hekim_id_2 = !empty($_POST['hekim_id_2']) ? trim($_POST['hekim_id_2']) : 0;
    $hekim_id_3 = !empty($_POST['hekim_id_3']) ? trim($_POST['hekim_id_3']) : 0;

    $saglık_p_id = !empty($_POST['saglık_p_id']) ? trim($_POST['saglık_p_id']) : 0;
    $saglık_p_id_2 = !empty($_POST['saglık_p_id_2']) ? trim($_POST['saglık_p_id_2']) : 0;

    $ofis_p_id = !empty($_POST['ofis_p_id']) ? trim($_POST['ofis_p_id']) : 0;
    $ofis_p_id_2 = !empty($_POST['ofis_p_id_2']) ? trim($_POST['ofis_p_id_2']) : 0;

    $muhasebe_p_id = !empty($_POST['muhasebe_p_id']) ? trim($_POST['muhasebe_p_id']) : 0;
    $muhasebe_p_id_2 = !empty($_POST['muhasebe_p_id_2']) ? trim($_POST['muhasebe_p_id_2']) : 0;

    $address = !empty($_POST['address']) ? trim($_POST['address']) : null;
    $remi_freq = !empty($_POST['remi_freq']) ? trim($_POST['remi_freq']) : 0;

    $nace_kodu = !empty($_POST['nace_kodu']) ? trim($_POST['nace_kodu']) : 0;
    $mersis_no = !empty($_POST['mersis_no']) ? trim($_POST['mersis_no']) : 0;
    $sgk_sicil = !empty($_POST['sgk_sicil']) ? trim($_POST['sgk_sicil']) : 0;
    $vergi_no = !empty($_POST['vergi_no']) ? trim($_POST['vergi_no']) : 0;
    $vergi_dairesi = !empty($_POST['vergi_dairesi']) ? trim($_POST['vergi_dairesi']) : null;
    $katip_is_yeri_id = !empty($_POST['katip_is_yeri_id']) ? trim($_POST['katip_is_yeri_id']) : 0;
    $katip_kurum_id = !empty($_POST['katip_kurum_id']) ? trim($_POST['katip_kurum_id']) : 0;



    $sql = "SELECT COUNT(name) AS num FROM coop_companies WHERE name = :name";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':name', $name);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['num'] > 0) {
        ?>
<div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
  <strong>Bu isimle daha önce kayıt yapıldı!</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php
    } else {
        $sql = "INSERT INTO `coop_companies`(`comp_type`, `name`, `mail`, `phone`, `is_veren`, `address`, `city`, `town`, `contract_date`, `uzman_id`, `uzman_id_2`, `uzman_id_3`,
          `hekim_id`, `hekim_id_2`, `hekim_id_3`, `saglık_p_id`, `saglık_p_id_2`, `ofis_p_id`, `ofis_p_id_2`, `muhasebe_p_id`, `muhasebe_p_id_2`, `remi_freq`,
          `nace_kodu`,`mersis_no`,`sgk_sicil`,`vergi_no`,`vergi_dairesi`,`katip_is_yeri_id`,`katip_kurum_id`)
        VALUES('$comp_type', '$name', '$email', '$phone', '$is_veren' ,'$address', '$city', '$town', '$contract_date', '$uzman_id', '$uzman_id_2', '$uzman_id_3',
           '$hekim_id', '$hekim_id_2', '$hekim_id_3', '$saglık_p_id', '$saglık_p_id_2', '$ofis_p_id', '$ofis_p_id_2', '$muhasebe_p_id', '$muhasebe_p_id_2', '$remi_freq',
           '$nace_kodu','$mersis_no','$sgk_sicil','$vergi_no','$vergi_dairesi','$katip_is_yeri_id','$katip_kurum_id')";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            ?>
<div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
  <strong>Yeni işletme başarıyla eklendi!</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<?php
            mkdir("companies/$name", 0777);
            mkdir("companies/$name/ziyaret_raporlari", 0777);
            mkdir("companies/$name/isletme_raporlari", 0777);
            $dosya = "/exmple_comp.php";
            $dt = fopen(__DIR__.$dosya, "r");
            $içerik = fread($dt, 10000000);
            fclose($dt);
            $file = fopen(__DIR__."/companies/$name/index.php", "w");
            $isim = "$";
            $is = "isim";
            fwrite($file, "<?php session_start();
$isim$is = '$name';
");
            fwrite($file, $içerik);

            fclose($file);

        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title>İşletmeler</title>
  <link rel="shortcut icon" href="../images/osgb_amblem.ico"></link>
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
  <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
  <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
  <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
  <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
      </ul>
    </div>
  </nav>
        <div class="container-fluid">
          <div class="card">
            <div class="card-header bg-light">
              <h1 class="text-dark mb-1" style="text-align: center;"><b>İşletmeler</b></h1>
            </div>
            <div class="card shadow-lg">
            <div class="card-body bg-light text-light">
                <button class="btn btn-primary" data-toggle="modal" data-target="#addCompany" data-whatever="@getbootstrap">Yeni İşletme Ekle</a>
                <?php
                if ($auth == 1) {
                ?>
                <button type="button" onclick="window.location.href='change_validate.php'" class="btn btn-success ml-1">Onay Bekleyenler</button>
                <?php
                }
                ?>
                <button type="button" onclick="window.location.href='deleted_companies.php'" class="btn btn-danger ml-1">Arşiv</button>

                <input type="text" class="form-control" style="float:right;max-width:600px;display:block;" id="myInput" onkeyup="myFunction()" placeholder="İşletme Adı ile ara...">

              <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                <table class="table table-striped table-bordered table-hover" id="dataTable">
                  <thead class="thead-dark">
                    <tr>
                      <th>İşletme Adı</th>
                      <th>Sektör</th>
                      <th>Telefon</th>
                      <th>E-mail</th>
                      <th>Şehir</th>
                      <th>İlçe</th>
                      <th>Anlaşma Tarihi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                        if ($auth == 0) {
                            $sorgu=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `change` = '1' AND (`uzman_id` = '$id' OR `uzman_id_2` = '$id' OR `uzman_id_3` = '$id' OR `yetkili_id` = '$id') AND `deleted` = 0 ORDER BY name");
                        } elseif ($auth == 1) {
                            $sorgu=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `change` = '1' AND `deleted` = 0 ORDER BY name");
                        } elseif ($auth == 2) {
                            $sorgu=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `change` = '1' AND (`hekim_id` = '$id' OR `hekim_id_2` = '$id' OR `hekim_id_3` = '$id' OR `yetkili_id` = '$id') AND `deleted` = 0  ORDER BY name");
                        } elseif ($auth == 3) {
                            $sorgu=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `change` = '1' AND (`saglık_p_id` = '$id' OR `saglık_p_id_2` = '$id' OR `yetkili_id` = '$id') AND `deleted` = 0  ORDER BY name");
                        } elseif ($auth == 4) {
                            $sorgu=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `change` = '1' AND (`ofis_p_id` = '$id' OR `ofis_p_id_2` = '$id' OR `yetkili_id` = '$id') AND `deleted` = 0  ORDER BY name");
                        } elseif ($auth == 6) {
                            $sorgu=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `change` = '1' AND (`muhasebe_p_id` = '$id' OR `muhasebe_p_id_2` = '$id' OR `yetkili_id` = '$id') AND `deleted` = 0  ORDER BY name");
                        } else {
                            header('Location: 403.php');
                            exit;
                        }
                        $sorgu->execute();
                        $companies=$sorgu-> fetchAll(PDO::FETCH_OBJ);
                        foreach ($companies as $key=>$company) {
                            ?>
                    <tr>
                      <td><a href="companies/<?= $company->name ?>/index.php?tab=genel_bilgiler"><?= $company->name ?></a></td>
                      <td><?= $company->comp_type ?></td>
                      <td><?= $company->phone ?></td>
                      <td><?= $company->mail ?></td>
                      <td><?= $company->city ?></td>
                      <td><?= $company->town ?></td>
                      <td><?= $company->contract_date ?></td>
                    </tr>
                    <?php
                        } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td><strong>İşletme Adı</strong></td>
                      <td><strong>Sektör</strong></td>
                      <td><strong>Telefon</strong></td>
                      <td><strong>E-mail</strong></td>
                      <td><strong>Şehir</strong></td>
                      <td><strong>İlçe</strong></td>
                      <td><strong>Anlaşma Tarihi</strong></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
          </div>
        </div>
        <div class="modal fade" id="addCompany" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <form action="companies.php" method="POST">
            <div class="modal-dialog modal-xl" role="document">
              <div class="modal-content">
                <div class="modal-c-tabs">
                  <ul class="nav nav-tabs justify-content-center bg-light" style="padding-top: 10px"role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active text-dark" id="link1-tab" data-toggle="tab" href="#link1" role="tab" aria-selected="true" aria-controls="link1">
                      <h5><b>İşletme Genel Bilgileri</b></h5></a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link text-dark" id="link2-tab" data-toggle="tab" role="tab" href="#link2" aria-selected="false" aria-controls="link2">
                        <h5><b>OSGB Çalışanları</b></h5></a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link text-dark" id="link3-tab" data-toggle="tab" role="tab" href="#link3" aria-selected="false" aria-controls="link3">
                        <h5><b>Devlet Bilgileri</b></h5></a>
                    </li>
                    <li class="nav-item">
                      <a  href="#" type="nav-link" class="nav-link" data-dismiss="modal" aria-label="Close" style="color:red;">
                        <h5><b>Kapat</b></h5>
                       </a>
                    </li>
                  </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade in show active" id="link1" role="tabpanel" aria-labelledby="link1-tab">
                            <div class="modal-body">
                                <div class="row">
                                  <div class="col-sm-6">
                                    <br>
                                    <label class="input-group mb-3" for="comp_type"><h5><strong>İşletmenin çalıştığı sektörü seçiniz<a style="color:red">*</a></strong></h5></label>
                                    <select class="form-control" placeholder="Sektörü" list="comp_type" name="comp_type" id="comp_type" autocomplete="off" required />
                                    <option value="" disabled selected>Sektör</option>
                                    <option value="Hizmet">Hizmet</option>
                                    <option value="Sağlık">Sağlık</option>
                                    <option value="Sanayi">Sanayi</option>
                                    <option value="Tarım">Tarım</option>
                                    <option value="Diğer">Diğer</option>
                                    </select>
                                  </div>
                                </div>
                                <br>
                                <div class="row">
                                  <div class="col-sm-6">
                                    <label for="name"><h5><strong>İşletme Adı<a style="color:red">*</a></strong></h5></label>
                                    <input class="form-control" type="text" placeholder="Adı" name="name" id="name" maxlength="250" required>
                                  </div>
                                  <div class="col-sm-6">
                                    <label for="email"><h5><strong>İşletme Mail Adresi<a style="color:red">*</a></strong></h5></label>
                                    <input class="form-control" type="email" placeholder="E-mail" name="email" id="email" maxlength="125" required>
                                  </div>
                                </div>
                                <br>
                                <div class="row">
                                  <div class="col-sm-6">
                                    <label for="phone"><h5><strong>İşletme Telefon No<a style="color:red">*</a></strong></h5></label>
                                    <input class="form-control" type="tel" name="phone" id="phone" placeholder="Tel: 0XXXXXXXXXX" pattern="(\d{4})(\d{3})(\d{2})(\d{2})" maxlength="11" required>
                                  </div>
                                  <div class="col-sm-6">
                                    <label for="is_veren"><h5><strong>İşveren Ad Soyad<a style="color:red">*</a></strong></h5></label>
                                    <input class="form-control" type="text" name="is_veren" id="is_veren" placeholder="İşveren Ad Soyad" maxlength="50" required>
                                  </div>
                                </div>
                                <br>
                                <div class="row">
                                  <div class="col-sm-4">
                                    <label for="countrySelect"><h5><strong>Bulunduğu Şehir<a style="color:red">*</a></strong></h5></label>
                                    <select class="form-control" id="countrySelect" name="countrySelect" size="1" onchange="makeSubmenu(this.value)" required>
                                      <option value="">Şehir</option>
                                      <option>Adana</option>
                                      <option>Adıyaman</option>
                                      <option>Afyonkarahisar</option>
                                      <option>Ağrı</option>
                                      <option>Amasya</option>
                                      <option>Ankara</option>
                                      <option>Antalya</option>
                                      <option>Artvin</option>
                                      <option>Aydın</option>
                                      <option>Balıkesir</option>
                                      <option>Bilecik</option>
                                      <option>Bingöl</option>
                                      <option>Bitlis</option>
                                      <option>Bolu</option>
                                      <option>Burdur</option>
                                      <option>Bursa</option>
                                      <option>Çanakkale</option>
                                      <option>Çankırı</option>
                                      <option>Çorum</option>
                                      <option>Denizli</option>
                                      <option>Diyarbakır</option>
                                      <option>Edirne</option>
                                      <option>Elazığ</option>
                                      <option>Erzincan</option>
                                      <option>Erzurum</option>
                                      <option>Eskişehir</option>
                                      <option>Gaziantep</option>
                                      <option>Giresun</option>
                                      <option>Gümüşhane</option>
                                      <option>Hakkâri</option>
                                      <option>Hatay</option>
                                      <option>Isparta</option>
                                      <option>Mersin</option>
                                      <option>İstanbul</option>
                                      <option>İzmir</option>
                                      <option>Kars</option>
                                      <option>Kastamonu</option>
                                      <option>Kayseri</option>
                                      <option>Kırklareli</option>
                                      <option>Kırşehir</option>
                                      <option>Kocaeli</option>
                                      <option>Konya</option>
                                      <option>Kütahya</option>
                                      <option>Malatya</option>
                                      <option>Manisa</option>
                                      <option>Kahramanmaraş</option>
                                      <option>Mardin</option>
                                      <option>Muğla</option>
                                      <option>Muş</option>
                                      <option>Nevşehir</option>
                                      <option>Niğde</option>
                                      <option>Ordu</option>
                                      <option>Rize</option>
                                      <option>Sakarya</option>
                                      <option>Samsun</option>
                                      <option>Siirt</option>
                                      <option>Sinop</option>
                                      <option>Sivas</option>
                                      <option>Tekirdağ</option>
                                      <option>Tokat</option>
                                      <option>Trabzon</option>
                                      <option>Tunceli</option>
                                      <option>Şanlıurfa</option>
                                      <option>Uşak</option>
                                      <option>Van</option>
                                      <option>Yozgat</option>
                                      <option>Zonguldak</option>
                                      <option>Aksaray</option>
                                      <option>Bayburt</option>
                                      <option>Karaman</option>
                                      <option>Kırıkkale</option>
                                      <option>Batman</option>
                                      <option>Şırnak</option>
                                      <option>Bartın</option>
                                      <option>Ardahan</option>
                                      <option>Iğdır</option>
                                      <option>Yalova</option>
                                      <option>Karabük</option>
                                      <option>Kilis</option>
                                      <option>Osmaniye</option>
                                      <option>Düzce</option>
                                    </select>
                                  </div>
                                  <div class="col-sm-4">
                                    <label for="citySelect"><h5><strong>Bulunduğu İlçe<a style="color:red">*</a></strong></h5></label>
                                    <select class="form-control" id="citySelect" name="citySelect" size="1" required>
                                      <option value="" disabled selected>İlçe</option>
                                      <option></option>
                                    </select>
                                  </div>
                                  <div class="col-sm-4">
                                    <label for="contract_date"><h5><strong>İşletme Anlaşma Tarihi<a style="color:red">*</a></strong></h5></label>
                                    <input class="form-control" type="date" placeholder="Anlaşma Tarihi" name="contract_date" id="contract_date" required>
                                  </div>
                                </div>
                                <br>
                                <div class="row">
                                  <div class="col-sm-12">
                                  <label for="address"><h5><b>Adres<a style="color:red">*</a></b></h5></label>
                                  <textarea class="form-control" id="address" name="address" rows="3" style="max-width: 100%;" maxlength="2500" required></textarea>
                                  </div>
                                </div>
                                <br>
                                <div class="row">
                                  <div class="col-sm-3">
                                  <label for="remi_freq"><h5><b>Periyodik Muayene Aralığı</b></h5></label>
                                  <select class="form-control" id="remi_freq" name="remi_freq" size="1" >
                                    <option value ="">Periyodik Muayene Aralığı</option>
                                    <option value = 1>1 Ay</option>
                                    <option value = 2>2 Ay</option>
                                    <option value = 3>3 Ay</option>
                                    <option value = 4>4 Ay</option>
                                    <option value = 5>5 Ay</option>
                                    <option value = 6>6 Ay</option>
                                    <option value = 7>7 Ay</option>
                                    <option value = 8>8 Ay</option>
                                    <option value = 9>9 Ay</option>
                                    <option value = 10>10 Ay</option>
                                    <option value = 11>11 Ay</option>
                                    <option value = 12>12 Ay</option>
                                    <option value = 18>18 Ay</option>
                                    <option value = 24>24 Ay</option>
                                    <option value = 36>36 Ay</option>
                                  </select>
                                  </div>
                                </div>
                            </div>
                          <div class="modal-footer">
                            <button class="btn btn-primary next" id="next1" name="next1">Devam Et</button>
                          </div>
                        </div>

                        <div class="tab-pane fade" id="link2" role="tabpanel" aria-labelledby="link2-tab">
                            <div class="modal-body">
                            <h3 style="text-align: center;"><u><b>İsg Uzmanı Seç</b></u></h3>
                            <div class="row">
                              <div class="col-sm-4">
                                <label for="uzman_id"><h6><b>1. İsg Uzmanı</b></h6></label>
                                <select class="form-control" name="uzman_id" id="uzman_id" autocomplete="off" size="1">
                                  <option value="" disabled selected><b>1. İsg Uzmanı</b></option>
                                  <?php
                                  $çalışan=$pdo->prepare("SELECT * FROM users WHERE `auth` = 0");
                                  $çalışan->execute();
                                  $uzmanlar=$çalışan-> fetchAll(PDO::FETCH_OBJ);
                                  foreach ($uzmanlar as $uzman) {
                                      $fir = $uzman->firstname;
                                      $las = $uzman->lastname;
                                      $çalışan_id = $uzman->id;
                                      $posta = $uzman->username; ?>
                                  <option value="<?= $çalışan_id ?>">
                                    <?php
                                          $çalışan_type=$pdo->prepare("SELECT * FROM `osgb_workers` WHERE `mail` = '$posta'");
                                      $çalışan_type->execute();
                                      $tips=$çalışan_type-> fetchAll(PDO::FETCH_OBJ);
                                      foreach ($tips as $tip) {
                                          $yazdir = $tip->user_type;
                                      } ?>
                                    <?=$yazdir." ".$fir." ".$las ?>
                                  </option>
                                  <?php
                                  }
                                   ?>
                                </select>
                              </div>
                              <div class="col-sm-4">
                                <label for="uzman_id_2"><h6><b>2. İsg Uzmanı</b></h6></label>
                                <select class="form-control" name="uzman_id_2" id="uzman_id_2" autocomplete="off" size="1">
                                  <option value="" disabled selected>2. İsg Uzmanı</option>
                                  <?php
                                  $çalışan=$pdo->prepare("SELECT * FROM users WHERE `auth` = 0");
                                  $çalışan->execute();
                                  $uzmanlar=$çalışan-> fetchAll(PDO::FETCH_OBJ);
                                  foreach ($uzmanlar as $uzman) {
                                      $fir = $uzman->firstname;
                                      $las = $uzman->lastname;
                                      $çalışan_id = $uzman->id;
                                      $posta = $uzman->username; ?>
                                  <option value="<?= $çalışan_id ?>">
                                    <?php
                                          $çalışan_type=$pdo->prepare("SELECT * FROM `osgb_workers` WHERE `mail` = '$posta'");
                                      $çalışan_type->execute();
                                      $tips=$çalışan_type-> fetchAll(PDO::FETCH_OBJ);
                                      foreach ($tips as $tip) {
                                          $yazdir = $tip->user_type;
                                      } ?>
                                    <?=$yazdir." ".$fir." ".$las ?>
                                  </option>
                                  <?php
                                  }
                                   ?>
                                </select>
                              </div>
                              <div class="col-sm-4">
                                <label for="uzman_id_3"><h6><b>3. İsg Uzmanı</b></h6></label>
                                <select class="form-control" name="uzman_id_3" id="uzman_id_3" autocomplete="off" size="1">
                                  <option value="" disabled selected>3. İsg Uzmanı</option>
                                  <?php
                                  $çalışan=$pdo->prepare("SELECT * FROM users WHERE `auth` = 0");
                                  $çalışan->execute();
                                  $uzmanlar=$çalışan-> fetchAll(PDO::FETCH_OBJ);
                                  foreach ($uzmanlar as $uzman) {
                                      $fir = $uzman->firstname;
                                      $las = $uzman->lastname;
                                      $çalışan_id = $uzman->id;
                                      $posta = $uzman->username; ?>
                                  <option value="<?= $çalışan_id ?>">
                                    <?php
                                          $çalışan_type=$pdo->prepare("SELECT * FROM `osgb_workers` WHERE `mail` = '$posta'");
                                      $çalışan_type->execute();
                                      $tips=$çalışan_type-> fetchAll(PDO::FETCH_OBJ);
                                      foreach ($tips as $tip) {
                                          $yazdir = $tip->user_type;
                                      } ?>
                                    <?=$yazdir." ".$fir." ".$las ?>
                                  </option>
                                  <?php
                                  }
                                   ?>
                                </select>
                              </div>
                              </div>
                            <hr style="border-top: 1px dashed red;">

                            <h3 style="text-align: center;"><u><b>İş Yeri Hekimi Seç</b></u></h3>
                            <div class="row">
                              <div class="col-sm-4">
                                <label for="hekim_id"><h6><strong>1.İş Yeri Hekimi</strong></h6></label>
                                <select class="form-control" name="hekim_id" id="hekim_id" autocomplete="off" size="1">
                                  <option value="" disabled selected>İş Yeri Hekimi</option>
                                  <?php
                                 $çalışan2=$pdo->prepare("SELECT * FROM users WHERE `auth` = 2");
                                 $çalışan2->execute();
                                 $hekimler=$çalışan2-> fetchAll(PDO::FETCH_OBJ);
                                 foreach ($hekimler as $hekim) {
                                     $fir2 = $hekim->firstname;
                                     $las2 = $hekim->lastname;
                                     $çalışan_id2 = $hekim->id;
                                     $posta2 = $hekim->username; ?>
                                  <option value="<?= $çalışan_id2 ?>"><?= $fir2." ".$las2 ?></option>
                                  <?php
                                 } ?>
                                </select>
                              </div>
                              <div class="col-sm-4">
                                <label for="hekim_id_2"><h6><strong>2.İş Yeri Hekimi</strong></h6></label>
                                <select class="form-control" name="hekim_id_2" id="hekim_id_2" autocomplete="off" size="1">
                                  <option value="" disabled selected>İş Yeri Hekimi</option>
                                  <?php
                                 $çalışan2=$pdo->prepare("SELECT * FROM users WHERE `auth` = 2");
                                 $çalışan2->execute();
                                 $hekimler=$çalışan2-> fetchAll(PDO::FETCH_OBJ);
                                 foreach ($hekimler as $hekim) {
                                     $fir2 = $hekim->firstname;
                                     $las2 = $hekim->lastname;
                                     $çalışan_id2 = $hekim->id;
                                     $posta2 = $hekim->username; ?>
                                  <option value="<?= $çalışan_id2 ?>"><?= $fir2." ".$las2 ?></option>
                                  <?php
                                 } ?>
                                </select>
                              </div>
                              <div class="col-sm-4">
                                <label for="hekim_id_3"><h6><strong>3.İş Yeri Hekimi</strong></h6></label>
                                <select class="form-control" name="hekim_id_3" id="hekim_id_3" autocomplete="off" size="1">
                                  <option value="" disabled selected>İş Yeri Hekimi</option>
                                  <?php
                                 $çalışan2=$pdo->prepare("SELECT * FROM users WHERE `auth` = 2");
                                 $çalışan2->execute();
                                 $hekimler=$çalışan2-> fetchAll(PDO::FETCH_OBJ);
                                 foreach ($hekimler as $hekim) {
                                     $fir2 = $hekim->firstname;
                                     $las2 = $hekim->lastname;
                                     $çalışan_id2 = $hekim->id;
                                     $posta2 = $hekim->username; ?>
                                  <option value="<?= $çalışan_id2 ?>"><?= $fir2." ".$las2 ?></option>
                                  <?php
                                 } ?>
                                </select>
                              </div>
                              </div>
                            <hr style="border-top: 1px dashed red;">

                            <h3 style="text-align: center;"><u><b>Personel Seç</b></u></h3>
                            <div class="row">
                              <div class="col-sm-4">
                                <label for="saglık_p_id"><strong>Diğer Sağlık Personeli</strong></label>
                                <select class="form-control" name="saglık_p_id" id="saglık_p_id" autocomplete="off" size="1">
                                  <option value="" disabled selected>Diğer Sağlık Personeli</option>
                                  <?php
                                 $çalışan3=$pdo->prepare("SELECT * FROM users WHERE `auth` = 3");
                                 $çalışan3->execute();
                                 $sağlıkçılar=$çalışan3-> fetchAll(PDO::FETCH_OBJ);
                                 foreach ($sağlıkçılar as $sağlıkçı) {
                                     $fir3 = $sağlıkçı->firstname;
                                     $las3 = $sağlıkçı->lastname;
                                     $çalışan_id3 = $sağlıkçı->id;
                                     $posta3 = $sağlıkçı->username; ?>
                                  <option value="<?= $çalışan_id3 ?>"><?= $fir3." ".$las3 ?></option>
                                  <?php
                                 } ?>
                                </select>
                              </div>
                              <div class="col-sm-4">
                                <label for="ofis_p_id"><strong>Ofis Personeli</strong></label>
                                <select class="form-control" name="ofis_p_id" id="ofis_p_id" autocomplete="off" size="1">
                                  <option value="" disabled selected>Ofis Personeli</option>
                                  <?php
                                 $çalışan4=$pdo->prepare("SELECT * FROM users WHERE `auth` = 4");
                                 $çalışan4->execute();
                                 $ofisler=$çalışan4-> fetchAll(PDO::FETCH_OBJ);
                                 foreach ($ofisler as $ofis) {
                                     $fir4 = $ofis->firstname;
                                     $las4 = $ofis->lastname;
                                     $çalışan_id4 = $ofis->id;
                                     $posta4 = $ofis->username; ?>
                                  <option value="<?= $çalışan_id4 ?>"><?= $fir4." ".$las4 ?></option>
                                  <?php
                                 } ?>
                                </select>
                              </div>
                              <div class="col-sm-4">
                                <label for="muhasebe_p_id"><strong>Muhasebe Personeli</strong></label>
                                <select class="form-control" name="muhasebe_p_id" id="muhasebe_p_id" autocomplete="off" size="1">
                                  <option value="" disabled selected>Muhasebe Personeli</option>
                                  <?php
                                 $çalışan5=$pdo->prepare("SELECT * FROM users WHERE `auth` = 6");
                                 $çalışan5->execute();
                                 $muhasebeler=$çalışan5-> fetchAll(PDO::FETCH_OBJ);
                                 foreach ($muhasebeler as $muhasebe) {
                                     $fir5 = $muhasebe->firstname;
                                     $las5 = $muhasebe->lastname;
                                     $çalışan_id5 = $muhasebe->id;
                                     $posta5 = $muhasebe->username; ?>
                                  <option value="<?= $çalışan_id5 ?>"><?= $fir5." ".$las5 ?></option>
                                  <?php
                                 } ?>
                                </select>
                              </div>
                              </div>
                            <br>
                            <div class="row">
                              <div class="col-sm-4">
                                <label for="saglık_p_id_2"><strong>2.Diğer Sağlık Personeli</strong></label>
                                <select class="form-control" name="saglık_p_id_2" id="saglık_p_id_2" autocomplete="off" size="1">
                                  <option value="" disabled selected>2.Diğer Sağlık Personeli</option>
                                  <?php
                                 $çalışan3=$pdo->prepare("SELECT * FROM users WHERE `auth` = 3");
                                 $çalışan3->execute();
                                 $sağlıkçılar=$çalışan3-> fetchAll(PDO::FETCH_OBJ);
                                 foreach ($sağlıkçılar as $sağlıkçı) {
                                     $fir3 = $sağlıkçı->firstname;
                                     $las3 = $sağlıkçı->lastname;
                                     $çalışan_id3 = $sağlıkçı->id;
                                     $posta3 = $sağlıkçı->username; ?>
                                  <option value="<?= $çalışan_id3 ?>"><?= $fir3." ".$las3 ?></option>
                                  <?php
                                 } ?>
                                </select>
                              </div>
                              <div class="col-sm-4">
                                <label for="ofis_p_id_2"><strong>2.Ofis Personeli</strong></label>
                                <select class="form-control" name="ofis_p_id_2" id="ofis_p_id_2" autocomplete="off" size="1">
                                  <option value="" disabled selected>2.Ofis Personeli</option>
                                  <?php
                                 $çalışan4=$pdo->prepare("SELECT * FROM users WHERE `auth` = 4");
                                 $çalışan4->execute();
                                 $ofisler=$çalışan4-> fetchAll(PDO::FETCH_OBJ);
                                 foreach ($ofisler as $ofis) {
                                     $fir4 = $ofis->firstname;
                                     $las4 = $ofis->lastname;
                                     $çalışan_id4 = $ofis->id;
                                     $posta4 = $ofis->username; ?>
                                  <option value="<?= $çalışan_id4 ?>"><?= $fir4." ".$las4 ?></option>
                                  <?php
                                 } ?>
                                </select>
                              </div>
                              <div class="col-sm-4">
                                <label for="muhasebe_p_id_2"><strong>2.Muhasebe Personeli</strong></label>
                                <select class="form-control" name="muhasebe_p_id_2" id="muhasebe_p_id_2" autocomplete="off" size="1">
                                  <option value="" disabled selected>2.Muhasebe Personeli</option>
                                  <?php
                                 $çalışan5=$pdo->prepare("SELECT * FROM users WHERE `auth` = 6");
                                 $çalışan5->execute();
                                 $muhasebeler=$çalışan5-> fetchAll(PDO::FETCH_OBJ);
                                 foreach ($muhasebeler as $muhasebe) {
                                     $fir5 = $muhasebe->firstname;
                                     $las5 = $muhasebe->lastname;
                                     $çalışan_id5 = $muhasebe->id;
                                     $posta5 = $muhasebe->username; ?>
                                  <option value="<?= $çalışan_id5 ?>"><?= $fir5." ".$las5 ?></option>
                                  <?php
                                 } ?>
                                </select>
                              </div>
                              </div>
                              </div>
                          <div class="modal-footer">
                            <button class="btn btn-danger previous">Geri Git</button>
                            <button class="btn btn-primary next" id="next2" name="next2">Devam Et</button>
                          </div>
                        </div>

                        <div class="tab-pane fade" id="link3" role="tabpanel" aria-labelledby="home-tab">

                            <div class="modal-body">
                            <div class="row col-12">
                              <label for="nace_kodu"><h4><b>NACE Kodunu Seçiniz</b></h4></label>
                              <select name="nace_kodu" class="form-control" required>
                                <option value="" disabled selected>NACE Kodunu Seçiniz</option>
                                <option value="81.22.03">Nesne veya binaların (ameliyathaneler vb.) sterilizasyonu faaliyetleri.Binalar ile ilgili hizmetler ve çevre düzenlemesi faaliyetleri 3</option>
                                <option value="82.20.01">Çağrı merkezlerinin faaliyetleri  2</option>
                                <option value="86.90.17">İnsan sağlığı hizmetleri 3</option>
                                <option value="85.59.16">Çocuk kulüplerinin faaliyetleri (6 yaş ve üzeri çocuklar için) 1</option>
                                <option value="71.12.14">Yapı denetim kuruluşları 1</option>
                                <option value="56.10.21">Oturacak yeri olmayan fast-food (hamburger, sandviç, tost vb.) satış yerleri (büfeler dahil), al götür tesisleri (içli pide ve lahmacun fırınları hariç) ve benzerleri tarafından sağlanan diğer yemek hazırlama ve sunum faaliyetleri 1</option>
                                <option value="56.10.20">Oturacak yeri olmayan içli pide ve lahmacun fırınlarının faaliyetleri (al götür tesisi olarak hizmet verenler) 1</option>
                                <option value="47.89.19">Seyyar olarak ve motorlu araçlarla diğer malların perakende ticareti 1</option>
                                <option value="47.82.03">Seyyar olarak ve motorlu araçlarla tekstil, giyim eşyası ve ayakkabı perakende ticareti 1</option>
                                <option value="47.81.12">Seyyar olarak ve motorlu araçlarla gıda ürünleri ve içeceklerin (alkollü içecekler hariç) perakende ticareti 1</option>
                                <option value="47.79.06">Belirli bir mala tahsis edilmiş mağazalarda kullanılmış giysiler ve aksesuarlarının perakende ticareti 1/option>
                                <option value="45.20.09">Motorlu kara taşıtlarının sadece boyanması faaliyetleri 3</option>
                                <option value="25.99.90">Başka yerde sınıflandırılmamış diğer fabrikasyon metal ürünlerin imalatı 2</option>
                                <option value="08.99.01">Aşındırıcı (törpüleyici) materyaller (zımpara), amyant, silisli fosil artıklar, arsenik cevherleri, sabuntaşı (talk) ve feldispat madenciliği (kuartz, mika, şist, talk, silis, sünger taşı, asbest, doğal korindon vb.) 3</option>
                                <option value="08.93.02">Deniz, göl ve kaynak tuzu üretimi (tuzun yemeklik tuza dönüştürülmesi hariç) 2</option>
                                <option value="23.99.07">Amyantlı kağıt imalatı 3</option>
                              </select>
                            </div>
                            <br>
                            <div class="row">
                              <div class="col-6">
                                <label for="mersis_no"><h4><b>Kurum Mersis No Giriniz</b></h4></label>
                                <input class="form-control" id="mersis_no" name="mersis_no" type="tel" min="16" maxlength="16" placeholder="Mersis No">
                              </div>
                              <div class="col-6">
                                <label for="sgk_sicil"><h4><b>SGK Sicil No Giriniz</b></h4></label>
                                <input class="form-control" id="sgk_sicil" name="sgk_sicil" type="tel" min="12" maxlength="12" placeholder="Mersis No">
                              </div>
                            </div>
                            <br>
                            <div class="row">
                              <div class="col-6">
                                <label for="vergi_no"><h4><b>Vergi No Giriniz</b></h4></label>
                                <input class="form-control" id="vergi_no" name="vergi_no" type="tel" min="10" maxlength="10" placeholder="Vergi No">
                              </div>
                              <div class="col-6">
                                <label for="vergi_dairesi"><h4><b>Vergi Dairesi Giriniz</b></h4></label>
                                <input class="form-control" id="vergi_dairesi" name="vergi_dairesi" type="text" maxlength="500" placeholder="Vergi Dairesi">
                              </div>
                            </div>
                            <br>
                            <div class="row">
                              <div class="col-6">
                                <label for="katip_is_yeri_id"><h4><b>İSG-KATİP İş Yeri ID</b></h4></label>
                                <input class="form-control" id="katip_is_yeri_id" name="katip_is_yeri_id" type="tel" maxlength="30" placeholder="İSG-KATİP İş Yeri ID">
                              </div>
                              <div class="col-6">
                                <label for="katip_kurum_id"><h4><b>İSG-KATİP Kurum ID</b></h4></label>
                                <input class="form-control" id="katip_kurum_id" name="katip_kurum_id" type="tel" maxlength="30" placeholder="İSG-KATİP Kurum ID">
                              </div>
                            </div>
                          </div>
                            <div class="modal-footer">
                              <button class="btn btn-danger previous" >Geri Git</button>
                              <input class="btn btn-success" type="submit" value="Kaydet" name="kaydet" id="kaydet"/>
                            </div>
                          </form>
                        </div>
                    </div>
              </div>
              </div>
            </div>
          </form>
      </div>
      <footer class="bg-white sticky-footer">
        <div class="container my-auto">
          <div class="text-center my-auto copyright"><span>Copyright © ÖzgürOSGB 2020</span></div>
        </div>
      </footer>
    </div>
  </div>
  <div name="scripts">
    <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/chart.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="assets/js/theme.js"></script>
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
      var citiesByState = {
        Adana: ["Aladağ", "Ceyhan", "Çukurova", "Feke", "İmamoğlu", "Karaisalı", "Karataş", "Kozan", "Pozantı", "Saimbeyli", "Sarıçam", "Seyhan", "Tufanbeyli", "Yumurtalık", "Yüreğir"],
        Adıyaman: ["Besni", "Çelikhan", "Gerger", "Gölbaşı", "Kahta", "Merkez", "Samsat", "Sincik", "Tut"],
        Afyonkarahisar: ["Başmakçı", "Bayat", "Bolvadin", "Çay", "Çobanlar", "Dazkırı", "Dinar", "Emirdağ", "Evciler", "Hocalar", "İhsaniye", "İscehisar", "Kızılören", "Merkez", "Sandıklı", "Sinanpaşa", "Sultandağı", "Şuhut"],
        Ağrı: ["Diyadin", "Doğubayazıt", "Eleşkirt", "Hamur", "Merkez", "Patnos", "Taşlıçay", "Tutak"],
        Amasya: ["Göynücek", "Gümüşhacıköy", "Hamamözü", "Merkez", "Merzifon", "Suluova", "Taşova"],
        Ankara: ["Altındağ", "Ayaş", "Bala", "Beypazarı", "Çamlıdere", "Çankaya", "Çubuk", "Elmadağ", "Güdül", "Haymana", "Kalecik", "Kızılcahamam", "Nallıhan", "Polatlı", "Şereflikoçhisar", "Yenimahalle", "Gölbaşı", "Keçiören", "Mamak", "Sincan",
          "Kazan", "Akyurt", "Etimesgut", "Evren", "Pursaklar"
        ],
        Antalya: ["Akseki", "Alanya", "Elmalı", "Finike", "Gazipaşa", "Gündoğmuş", "Kaş", "Korkuteli", "Kumluca", "Manavgat", "Serik", "Demre", "İbradı", "Kemer", "Aksu", "Döşemealtı", "Kepez", "Konyaaltı", "Muratpaşa"],
        Artvin: ["Ardanuç", "Arhavi", "Merkez", "Borçka", "Hopa", "Şavşat", "Yusufeli", "Murgul"],
        Aydın: ["Merkez", "Bozdoğan", "Efeler", "Çine", "Germencik", "Karacasu", "Koçarlı", "Kuşadası", "Kuyucak", "Nazilli", "Söke", "Sultanhisar", "Yenipazar", "Buharkent", "İncirliova", "Karpuzlu", "Köşk", "Didim"],
        Balıkesir: ["Altıeylül", "Ayvalık", "Merkez", "Balya", "Bandırma", "Bigadiç", "Burhaniye", "Dursunbey", "Edremit", "Erdek", "Gönen", "Havran", "İvrindi", "Karesi", "Kepsut", "Manyas", "Savaştepe", "Sındırgı", "Gömeç", "Susurluk", "Marmara"],
        Bilecik: ["Merkez", "Bozüyük", "Gölpazarı", "Osmaneli", "Pazaryeri", "Söğüt", "Yenipazar", "İnhisar"],
        Bingöl: ["Merkez", "Genç", "Karlıova", "Kiğı", "Solhan", "Adaklı", "Yayladere", "Yedisu"],
        Bitlis: ["Adilcevaz", "Ahlat", "Merkez", "Hizan", "Mutki", "Tatvan", "Güroymak"],
        Bolu: ["Merkez", "Gerede", "Göynük", "Kıbrıscık", "Mengen", "Mudurnu", "Seben", "Dörtdivan", "Yeniçağa"],
        Burdur: ["Ağlasun", "Bucak", "Merkez", "Gölhisar", "Tefenni", "Yeşilova", "Karamanlı", "Kemer", "Altınyayla", "Çavdır", "Çeltikçi"],
        Bursa: ["Gemlik", "İnegöl", "İznik", "Karacabey", "Keles", "Mudanya", "Mustafakemalpaşa", "Orhaneli", "Orhangazi", "Yenişehir", "Büyükorhan", "Harmancık", "Nilüfer", "Osmangazi", "Yıldırım", "Gürsu", "Kestel"],
        Çanakkale: ["Ayvacık", "Bayramiç", "Biga", "Bozcaada", "Çan", "Merkez", "Eceabat", "Ezine", "Gelibolu", "Gökçeada", "Lapseki", "Yenice"],
        Çankırı: ["Merkez", "Çerkeş", "Eldivan", "Ilgaz", "Kurşunlu", "Orta", "Şabanözü", "Yapraklı", "Atkaracalar", "Kızılırmak", "Bayramören", "Korgun"],
        Çorum: ["Alaca", "Bayat", "Merkez", "İskilip", "Kargı", "Mecitözü", "Ortaköy", "Osmancık", "Sungurlu", "Boğazkale", "Uğurludağ", "Dodurga", "Laçin", "Oğuzlar"],
        Denizli: ["Acıpayam", "Buldan", "Çal", "Çameli", "Çardak", "Çivril", "Merkez", "Merkezefendi", "Pamukkale", "Güney", "Kale", "Sarayköy", "Tavas", "Babadağ", "Bekilli", "Honaz", "Serinhisar", "Baklan", "Beyağaç", "Bozkurt"],
        Diyarbakır: ["Kocaköy", "Çermik", "Çınar", "Çüngüş", "Dicle", "Ergani", "Hani", "Hazro", "Kulp", "Lice", "Silvan", "Eğil", "Bağlar", "Kayapınar", "Sur", "Yenişehir", "Bismil"],
        Edirne: ["Merkez", "Enez", "Havsa", "İpsala", "Keşan", "Lalapaşa", "Meriç", "Uzunköprü", "Süloğlu"],
        Elazığ: ["Ağın", "Baskil", "Merkez", "Karakoçan", "Keban", "Maden", "Palu", "Sivrice", "Arıcak", "Kovancılar", "Alacakaya"],
        Erzincan: ["Çayırlı", "Merkez", "İliç", "Kemah", "Kemaliye", "Refahiye", "Tercan", "Üzümlü", "Otlukbeli"],
        Erzurum: ["Aşkale", "Çat", "Hınıs", "Horasan", "İspir", "Karayazı", "Narman", "Oltu", "Olur", "Pasinler", "Şenkaya", "Tekman", "Tortum", "Karaçoban", "Uzundere", "Pazaryolu", "Köprüköy", "Palandöken", "Yakutiye", "Aziziye"],
        Eskişehir: ["Çifteler", "Mahmudiye", "Mihalıççık", "Sarıcakaya", "Seyitgazi", "Sivrihisar", "Alpu", "Beylikova", "İnönü", "Günyüzü", "Han", "Mihalgazi", "Odunpazarı", "Tepebaşı"],
        Gaziantep: ["Araban", "İslahiye", "Nizip", "Oğuzeli", "Yavuzeli", "Şahinbey", "Şehitkamil", "Karkamış", "Nurdağı"],
        Giresun: ["Alucra", "Bulancak", "Dereli", "Espiye", "Eynesil", "Merkez", "Görele", "Keşap", "Şebinkarahisar", "Tirebolu", "Piraziz", "Yağlıdere", "Çamoluk", "Çanakçı", "Doğankent", "Güce"],
        Gümüşhane: ["Merkez", "Kelkit", "Şiran", "Torul", "Köse", "Kürtün"],
        Hakkari: ["Çukurca", "Merkez", "Şemdinli", "Yüksekova"],
        Hatay: ["Altınözü", "Arsuz", "Defne", "Dörtyol", "Hassa", "Antakya", "İskenderun", "Kırıkhan", "Payas", "Reyhanlı", "Samandağ", "Yayladağı", "Erzin", "Belen", "Kumlu"],
        Isparta: ["Atabey", "Eğirdir", "Gelendost", "Merkez", "Keçiborlu", "Senirkent", "Sütçüler", "Şarkikaraağaç", "Uluborlu", "Yalvaç", "Aksu", "Gönen", "Yenişarbademli"],
        Mersin: ["Anamur", "Erdemli", "Gülnar", "Mut", "Silifke", "Tarsus", "Aydıncık", "Bozyazı", "Çamlıyayla", "Akdeniz", "Mezitli", "Toroslar", "Yenişehir"],
        İstanbul: ["Adalar", "Bakırköy", "Beşiktaş", "Beykoz", "Beyoğlu", "Çatalca", "Eyüp", "Fatih", "Gaziosmanpaşa", "Kadıköy", "Kartal", "Sarıyer", "Silivri", "Şile", "Şişli", "Üsküdar", "Zeytinburnu", "Büyükçekmece", "Kağıthane", "Küçükçekmece",
          "Pendik", "Ümraniye", "Bayrampaşa", "Avcılar", "Bağcılar", "Bahçelievler", "Güngören", "Maltepe", "Sultanbeyli", "Tuzla", "Esenler", "Arnavutköy", "Ataşehir", "Başakşehir", "Beylikdüzü", "Çekmeköy", "Esenyurt", "Sancaktepe", "Sultangazi"
        ],
        İzmir: ["Aliağa", "Bayındır", "Bergama", "Bornova", "Çeşme", "Dikili", "Foça", "Karaburun", "Karşıyaka", "Kemalpaşa", "Kınık", "Kiraz", "Menemen", "Ödemiş", "Seferihisar", "Selçuk", "Tire", "Torbalı", "Urla", "Beydağ", "Buca", "Konak",
          "Menderes", "Balçova", "Çiğli", "Gaziemir", "Narlıdere", "Güzelbahçe", "Bayraklı", "Karabağlar"
        ],
        Kars: ["Arpaçay", "Digor", "Kağızman", "Merkez", "Sarıkamış", "Selim", "Susuz", "Akyaka"],
        Kastamonu: ["Abana", "Araç", "Azdavay", "Bozkurt", "Cide", "Çatalzeytin", "Daday", "Devrekani", "İnebolu", "Merkez", "Küre", "Taşköprü", "Tosya", "İhsangazi", "Pınarbaşı", "Şenpazar", "Ağlı", "Doğanyurt", "Hanönü", "Seydiler"],
        Kayseri: ["Bünyan", "Develi", "Felahiye", "İncesu", "Pınarbaşı", "Sarıoğlan", "Sarız", "Tomarza", "Yahyalı", "Yeşilhisar", "Akkışla", "Talas", "Kocasinan", "Melikgazi", "Hacılar", "Özvatan"],
        Kırklareli: ["Babaeski", "Demirköy", "Merkez", "Kofçaz", "Lüleburgaz", "Pehlivanköy", "Pınarhisar", "Vize"],
        Kırşehir: ["Çiçekdağı", "Kaman", "Merkez", "Mucur", "Akpınar", "Akçakent", "Boztepe"],
        Kocaeli: ["Gebze", "Gölcük", "Kandıra", "Karamürsel", "Körfez", "Derince", "Başiskele", "Çayırova", "Darıca", "Dilovası", "İzmit", "Kartepe"],
        Konya: ["Akşehir", "Beyşehir", "Bozkır", "Cihanbeyli", "Çumra", "Doğanhisar", "Ereğli", "Hadim", "Ilgın", "Kadınhanı", "Karapınar", "Kulu", "Sarayönü", "Seydişehir", "Yunak", "Akören", "Altınekin", "Derebucak", "Hüyük", "Karatay", "Meram",
          "Selçuklu", "Taşkent", "Ahırlı", "Çeltik", "Derbent", "Emirgazi", "Güneysınır", "Halkapınar", "Tuzlukçu", "Yalıhüyük"
        ],
        Kütahya: ["Altıntaş", "Domaniç", "Emet", "Gediz", "Merkez", "Simav", "Tavşanlı", "Aslanapa", "Dumlupınar", "Hisarcık", "Şaphane", "Çavdarhisar", "Pazarlar"],
        Malatya: ["Akçadağ", "Arapgir", "Arguvan", "Darende", "Doğanşehir", "Hekimhan", "Merkez", "Pütürge", "Yeşilyurt", "Battalgazi", "Doğanyol", "Kale", "Kuluncak", "Yazıhan"],
        Manisa: ["Akhisar", "Alaşehir", "Demirci", "Gördes", "Kırkağaç", "Kula", "Merkez", "Salihli", "Sarıgöl", "Saruhanlı", "Selendi", "Soma", "Şehzadeler", "Yunusemre", "Turgutlu", "Ahmetli", "Gölmarmara", "Köprübaşı"],
        Kahramanmaraş: ["Afşin", "Andırın", "Dulkadiroğlu", "Onikişubat", "Elbistan", "Göksun", "Merkez", "Pazarcık", "Türkoğlu", "Çağlayancerit", "Ekinözü", "Nurhak"],
        Mardin: ["Derik", "Kızıltepe", "Artuklu", "Merkez", "Mazıdağı", "Midyat", "Nusaybin", "Ömerli", "Savur", "Dargeçit", "Yeşilli"],
        Muğla: ["Bodrum", "Datça", "Fethiye", "Köyceğiz", "Marmaris", "Menteşe", "Milas", "Ula", "Yatağan", "Dalaman", "Seydikemer", "Ortaca", "Kavaklıdere"],
        Muş: ["Bulanık", "Malazgirt", "Merkez", "Varto", "Hasköy", "Korkut"],
        Nevşehir: ["Avanos", "Derinkuyu", "Gülşehir", "Hacıbektaş", "Kozaklı", "Merkez", "Ürgüp", "Acıgöl"],
        Niğde: ["Bor", "Çamardı", "Merkez", "Ulukışla", "Altunhisar", "Çiftlik"],
        Ordu: ["Akkuş", "Altınordu", "Aybastı", "Fatsa", "Gölköy", "Korgan", "Kumru", "Mesudiye", "Perşembe", "Ulubey", "Ünye", "Gülyalı", "Gürgentepe", "Çamaş", "Çatalpınar", "Çaybaşı", "İkizce", "Kabadüz", "Kabataş"],
        Rize: ["Ardeşen", "Çamlıhemşin", "Çayeli", "Fındıklı", "İkizdere", "Kalkandere", "Pazar", "Merkez", "Güneysu", "Derepazarı", "Hemşin", "İyidere"],
        Sakarya: ["Akyazı", "Geyve", "Hendek", "Karasu", "Kaynarca", "Sapanca", "Kocaali", "Pamukova", "Taraklı", "Ferizli", "Karapürçek", "Söğütlü", "Adapazarı", "Arifiye", "Erenler", "Serdivan"],
        Samsun: ["Alaçam", "Bafra", "Çarşamba", "Havza", "Kavak", "Ladik", "Terme", "Vezirköprü", "Asarcık", "Ondokuzmayıs", "Salıpazarı", "Tekkeköy", "Ayvacık", "Yakakent", "Atakum", "Canik", "İlkadım"],
        Siirt: ["Baykan", "Eruh", "Kurtalan", "Pervari", "Merkez", "Şirvan", "Tillo"],
        Sinop: ["Ayancık", "Boyabat", "Durağan", "Erfelek", "Gerze", "Merkez", "Türkeli", "Dikmen", "Saraydüzü"],
        Sivas: ["Divriği", "Gemerek", "Gürün", "Hafik", "İmranlı", "Kangal", "Koyulhisar", "Merkez", "Suşehri", "Şarkışla", "Yıldızeli", "Zara", "Akıncılar", "Altınyayla", "Doğanşar", "Gölova", "Ulaş"],
        Tekirdağ: ["Çerkezköy", "Çorlu", "Ergene", "Hayrabolu", "Malkara", "Muratlı", "Saray", "Süleymanpaşa", "Kapaklı", "Şarköy", "Marmaraereğlisi"],
        Tokat: ["Almus", "Artova", "Erbaa", "Niksar", "Reşadiye", "Merkez", "Turhal", "Zile", "Pazar", "Yeşilyurt", "Başçiftlik", "Sulusaray"],
        Trabzon: ["Akçaabat", "Araklı", "Arsin", "Çaykara", "Maçka", "Of", "Ortahisar", "Sürmene", "Tonya", "Vakfıkebir", "Yomra", "Beşikdüzü", "Şalpazarı", "Çarşıbaşı", "Dernekpazarı", "Düzköy", "Hayrat", "Köprübaşı"],
        Tunceli: ["Çemişgezek", "Hozat", "Mazgirt", "Nazımiye", "Ovacık", "Pertek", "Pülümür", "Merkez"],
        Şanlıurfa: ["Akçakale", "Birecik", "Bozova", "Ceylanpınar", "Eyyübiye", "Halfeti", "Haliliye", "Hilvan", "Karaköprü", "Siverek", "Suruç", "Viranşehir", "Harran"],
        Uşak: ["Banaz", "Eşme", "Karahallı", "Sivaslı", "Ulubey", "Merkez"],
        Van: ["Başkale", "Çatak", "Erciş", "Gevaş", "Gürpınar", "İpekyolu", "Muradiye", "Özalp", "Tuşba", "Bahçesaray", "Çaldıran", "Edremit", "Saray"],
        Yozgat: ["Akdağmadeni", "Boğazlıyan", "Çayıralan", "Çekerek", "Sarıkaya", "Sorgun", "Şefaatli", "Yerköy", "Merkez", "Aydıncık", "Çandır", "Kadışehri", "Saraykent", "Yenifakılı"],
        Zonguldak: ["Çaycuma", "Devrek", "Ereğli", "Merkez", "Alaplı", "Gökçebey"],
        Aksaray: ["Ağaçören", "Eskil", "Gülağaç", "Güzelyurt", "Merkez", "Ortaköy", "Sarıyahşi"],
        Bayburt: ["Merkez", "Aydıntepe", "Demirözü"],
        Karaman: ["Ermenek", "Merkez", "Ayrancı", "Kazımkarabekir", "Başyayla", "Sarıveliler"],
        Kırıkkale: ["Delice", "Keskin", "Merkez", "Sulakyurt", "Bahşili", "Balışeyh", "Çelebi", "Karakeçili", "Yahşihan"],
        Batman: ["Merkez", "Beşiri", "Gercüş", "Kozluk", "Sason", "Hasankeyf"],
        Şırnak: ["Beytüşşebap", "Cizre", "İdil", "Silopi", "Merkez", "Uludere", "Güçlükonak"],
        Bartın: ["Merkez", "Kurucaşile", "Ulus", "Amasra"],
        Ardahan: ["Merkez", "Çıldır", "Göle", "Hanak", "Posof", "Damal"],
        Iğdır: ["Aralık", "Merkez", "Tuzluca", "Karakoyunlu"],
        Yalova: ["Merkez", "Altınova", "Armutlu", "Çınarcık", "Çiftlikköy", "Termal"],
        Karabük: ["Eflani", "Eskipazar", "Merkez", "Ovacık", "Safranbolu", "Yenice"],
        Kilis: ["Merkez", "Elbeyli", "Musabeyli", "Polateli"],
        Osmaniye: ["Bahçe", "Kadirli", "Merkez", "Düziçi", "Hasanbeyli", "Sumbas", "Toprakkale"],
        Düzce: ["Akçakoca", "Merkez", "Yığılca", "Cumayeri", "Gölyaka", "Çilimli", "Gümüşova", "Kaynaşlı"]
      }

      function makeSubmenu(value) {
        if (value.length == 0) document.getElementById("citySelect").innerHTML = "<option></option>";
        else {
          var citiesOptions = "";
          for (cityId in citiesByState[value]) {
            citiesOptions += "<option>" + citiesByState[value][cityId] + "</option>";
          }
          document.getElementById("citySelect").innerHTML = citiesOptions;
        }
      }

      function displaySelected() {
        var country = document.getElementById("countrySelect").value;
        var city = document.getElementById("citySelect").value;
        alert(country + "\n" + city);
      }

      function resetSelection() {
        document.getElementById("countrySelect").selectedIndex = 0;
        document.getElementById("citySelect").selectedIndex = 0;
      }
    </script>
    <script type="text/javascript">
      if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
      }
    </script>
    <script>

      $('#next1').click(function () {
        	if($('#comp_type').val() && $('#name').val() && $('#contract_date').val()&& $('#address').val()
          && $('#email').val()  && $('#phone').val()  && $('#citySelect').val()
           && $('#countrySelect').val() && $('#is_veren').val()) {
         		$('.nav-tabs > .nav-item > .active').parent().next('li').find('a').trigger('click');
      	}else {
          window.alert("Lütfen zorunlu(*) alanları doldurun");
        }
        });
      $('#kaydet').click(function () {
        if(!$('#comp_type').val() && !$('#name').val() && !$('#contract_date').val()
        && !$('#address').val() && !$('#email').val()  && !$('#phone').val()
        && !$('#citySelect').val() && !$('#countrySelect').val() && !$('#is_veren').val()) {
          window.alert("Lütfen zorunlu(*) alanları doldurun");
          return false;
        }
      });
        $('#next2').click(function () {
           		$('.nav-tabs > .nav-item > .active').parent().next('li').find('a').trigger('click');
          });
        $('.previous').click(function () {
          $('.nav-tabs > .nav-item > .active').parent().prev('li').find('a').trigger('click');
        });

        $('#link2-tab').click(function () {
          if(!$('#comp_type').val() && !$('#name').val() && !$('#contract_date').val()
          && !$('#address').val() && !$('#email').val()  && !$('#phone').val()
          && !$('#citySelect').val() && !$('#countrySelect').val() && !$('#is_veren').val()) {
            window.alert("Lütfen zorunlu(*) alanları doldurun");
            return false;
          }
        });
        $('#link3-tab').click(function () {
          if(!$('#comp_type').val() && !$('#name').val() && !$('#contract_date').val()
          && !$('#address').val() && !$('#email').val()  && !$('#phone').val()
           && !$('#citySelect').val() && !$('#countrySelect').val() && !$('#is_veren').val()) {
            window.alert("Lütfen zorunlu(*) alanları doldurun");
            return false;
          }
        });

    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </div>
</body>

</html>
