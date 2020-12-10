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
if (isset($_POST['sil'])) {
    $name = !empty($_POST['name']) ? trim($_POST['name']) : null;
    $sqlque = "UPDATE coop_companies SET `deleted` = 1,`change`='1' WHERE `coop_companies`.`name` = '$name' AND `change` = '2'";
    $stmt = $pdo->prepare($sqlque);
    $result = $stmt->execute();

    $sqlque2 = "DELETE FROM coop_companies WHERE `coop_companies`.`name` = '$name' AND `deleted` = 0";
    $stmt2 = $pdo->prepare($sqlque2);
    $result2 = $stmt2->execute();

    if ($result & $result2) {
        ?>
<div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
  <strong>İşletmeniz SİLİNDİ!</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php
    }
}
if (isset($_POST['düzenleme_iptal'])) {
    $name = !empty($_POST['name']) ? trim($_POST['name']) : null;
    $sqldel = "DELETE FROM `coop_companies` WHERE `change` = '0' OR `change` = '2' AND `name` = '$name'";
    $stmt = $pdo->prepare($sqldel);
    $result = $stmt->execute();

    if ($result) {
        ?>
<div class="alert alert-warning alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
  <strong>Değişiklik İsteği Reddeildi!</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php
    }
}
if (isset($_POST['onay'])) {
    $comp_type = !empty($_POST['comp_type']) ? trim($_POST['comp_type']) : null;
    $name = !empty($_POST['name']) ? trim($_POST['name']) : null;
    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : null;
    $city = !empty($_POST['city']) ? trim($_POST['city']) : null;
    $town = !empty($_POST['town']) ? trim($_POST['town']) : null;
    $address = !empty($_POST['address']) ? trim($_POST['address']) : null;
    $uzman = !empty($_POST['uzman']) ? trim($_POST['uzman']) : null;
    $doktor = !empty($_POST['doktor']) ? trim($_POST['doktor']) : null;
    $contract_date = !empty($_POST['contract_date']) ? trim($_POST['contract_date']) : null;

    $sor=$pdo->prepare("SELECT `id` FROM `coop_companies` WHERE `coop_companies`.`name` = '$name' AND `change` = '1'");
    $sor->execute();
    $companies=$sor-> fetchAll(PDO::FETCH_OBJ);
    foreach ($companies as $company) {
        $id = $company->id;
    }
    $sqldel = "DELETE FROM `coop_companies` WHERE `coop_companies`.`id` = $id";
    $stmt = $pdo->prepare($sqldel);
    $result = $stmt->execute();

    $sorr=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `coop_companies`.`name` = '$name' AND `change` = '0'");
    $sorr->execute();
    $ss=$sorr->fetchAll(PDO::FETCH_OBJ);
    foreach ($ss as $s) {
        $date=$s->reg_date;
        break;
    }
    $sqlque = "UPDATE `coop_companies` SET `change` = '1' WHERE `coop_companies`.`name` = '$name' AND `reg_date` = '$date'";
    $stmt = $pdo->prepare($sqlque);
    $result = $stmt->execute();
    if ($result) {

    $sorgu=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `coop_companies`.`name` = '$name' AND `change` = 1");
    $sorgu->execute();
    $companies=$sorgu-> fetchAll(PDO::FETCH_OBJ);
    foreach ($companies as $company) {
      $changer = $company->changer;
    }
    $sorgu=$pdo->prepare("SELECT * FROM `users` WHERE `username` = '$changer'");
    $sorgu->execute();
    $users=$sorgu-> fetchAll(PDO::FETCH_OBJ);
    foreach ($users as $user) {
      $user_id = $user->id;
    }
    $sql = "INSERT INTO `notifications`(`notif_text`, `user_id`)
    VALUES('$company->name işletmesi üzerinde yaptığınız değişiklikler onaylandı', '$user_id')";
    $stmt2 = $pdo->prepare($sql);
    $result2 = $stmt2->execute();
    if ($result2) {
    ?>
    <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
      <strong>Değişiklikleriniz ONAYLANDI!</strong>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <?php
  }
}

}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title>Onaya gönderilenler</title>
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
      </ul>
    </div>
  </nav>
    <div class="container-fluid">
          <div class="card shadow-lg">
            <div class="card-header bg-light">
              <h1 class="text-dark mb-1" style="text-align: center;"><b>Onay Bekleyen Değişiklikler</b></h1>
            </div>
            <div class="card-body">
              <div class="form-group col-md-4">
                <input type="text" class="form-control" id="myInput" onkeyup="myFunction()" placeholder="İşletme Adı ile ara...">
              </div>
              <div>
                <div id="dataTable_filter">
                  <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                    <table class="table table-striped table-bordered" id="dataTable">
                      <thead class="thead-dark">
                        <tr>
                          <th>İşletme Adı</th>
                          <th>Sektör</th>
                          <th>Telefon</th>
                          <th>E-mail</th>
                          <th>Şehir</th>
                          <th>İlçe</th>
                          <th>Anlaşma Tarihi</th>
                          <th>Düzenleyen</th>
                          <th>Düzenlenme Tarihi</th>
                          <th>Düzenle/Sil</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          $sorgu=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `change` = '0' OR `change` = '2' ORDER BY `reg_date` ASC");
                          $sorgu->execute();
                          $companies=$sorgu-> fetchAll(PDO::FETCH_OBJ);
                          foreach ($companies as $key=>$company) {
                              $change = $company->change; ?>
                        <tr>
                          <td><a href="companies/<?= $company->name ?>/index.php?tab=genel_bilgiler"><?= $company->name ?></a></td>
                          <td><?= $company->comp_type ?></td>
                          <td><?= $company->phone ?></td>
                          <td><?= $company->mail ?></td>
                          <td><?= $company->city ?></td>
                          <td><?= $company->town ?></td>
                          <td><?= $company->contract_date ?></td>
                          <td><?= $company->changer ?></td>
                          <td><?= $company->reg_date ?></td>
                          <?php if ($change == "0") {
                                  ?>
                          <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#a<?php echo $key; ?>" data-whatever="@getbootstrap">Düzenle</button></td>
                          <?php
                              }
                              if ($change == "2") {
                                  ?>
                          <td><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#a<?php echo $key; ?>" data-whatever="@getbootstrap">Sil</button></td>
                          <?php
                              } ?>
                          <div class="modal fade" id="a<?php echo $key; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <?php if ($change == "0") {
                                  ?>
                                  <h5 class="modal-title" id="exampleModalLabel">Düzenle</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                  <?php
                              }
                              if ($change == "2") {
                                  ?>
                                  <h5 class="modal-title" id="exampleModalLabel">Sil</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                  <?php
                              } ?>
                                </div>
                                <div class="modal-body">
                                  <form action="change_validate.php" method="POST">
                              <?php
                              $uzman_fn = " ";
                              $uzman_ln = " ";
                              $hekim_fn = " ";
                              $hekim_ln = " ";
                              if ($company->uzman_id != null) {
                                  $sorgu2=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$company->uzman_id'");
                                  $sorgu2->execute();
                                  $çalışanlar=$sorgu2-> fetchAll(PDO::FETCH_OBJ);
                                  foreach ($çalışanlar as $çalışan) {
                                      $uzman_fn = $çalışan->firstname;
                                      $uzman_ln = $çalışan->lastname;
                                  }
                              }
                              if ($company->hekim_id != null) {
                                  $sorgu3=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$company->hekim_id'");
                                  $sorgu3->execute();
                                  $çalışanlar2=$sorgu3-> fetchAll(PDO::FETCH_OBJ);
                                  foreach ($çalışanlar2 as $çalışan2) {
                                      $hekim_fn = $çalışan2->firstname;
                                      $hekim_ln = $çalışan2->lastname;
                                  }
                              } ?>
                                    <p><b>Düzenlenme tarihi:&emsp;</b> <?= $company->reg_date ?></p>
                                    <p><b>Düzenlemeye gönderen kullanıcı:&emsp;</b> <?= $company->changer ?></p>
                                    <p><b>İsg Uzmanı</b>:&emsp;<?= $uzman_fn." ".$uzman_ln ?></p>
                                    <p><b>İş Yeri Hekimi</b>:&emsp;<?= $hekim_fn." ".$hekim_ln ?></p>
                                    <?php
                              $eski_sorgu=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `change` = '1' AND `name` = '$company->name'");
                              $eski_sorgu->execute();
                              $eskiler=$eski_sorgu-> fetchAll(PDO::FETCH_OBJ);
                              foreach ($eskiler as $eski) {
                                  $eski_type = $eski->comp_type;
                                  $eski_mail = $eski->mail;
                                  $eski_phone = $eski->phone;
                                  $eski_address = $eski->address;
                                  $eski_city = $eski->city;
                                  $eski_town = $eski->town;
                                  $eski_contract_date = $eski->contract_date;
                                  $eski_uzman_id = $eski->uzman_id;
                                  $eski_hekim_id = $eski->hekim_id;
                                  $eski_mersis_no = $eski->mersis_no;
                                  $eski_sgk_sicil = $eski->sgk_sicil;
                                  $eski_vergi_no = $eski->vergi_no;
                                  $eski_vergi_dairesi = $eski->vergi_dairesi;
                                  $eski_katip_is_yeri_id = $eski->katip_is_yeri_id;
                                  $eski_katip_kurum_id = $eski->katip_kurum_id;
                                  $eski_remi_freq = $eski->remi_freq;
                              } ?>

                                    <br>
                                    <div class="row">
                                      <div class="col-sm-12">
                                        <label for="name"><strong>İşletme Adı:&emsp;</strong></label>
                                        <input class="form-control" type="text" placeholder="Adı" name="name" value="<?= $company->name ?>" readonly required>
                                      </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                      <?php
                                        if ($eski_mail != $company->mail) {
                                            ?>
                                          <div class="col-sm-4">
                                            <label for="eski_mail"><strong>Eski Mail Adresi</strong></label>
                                            <input class="form-control" type="email" name="eski_mail" value="<?= $eski_mail ?>" readonly>
                                            <br>
                                            <label for="email" style="color:red"><strong>Yeni Mail Adresi:&emsp;</strong></label>
                                            <input class="form-control" style="border: 2px solid red;" type="email" placeholder="E-mail" name="email" value="<?= $company->mail ?>" required>
                                            <br>
                                          </div>
                                      <?php
                                        } ?>
                                    </div>
                                    <div class="row">
                                      <?php
                                        if ($eski_type != $company->comp_type) { ?>
                                          <div class="col-sm-6">
                                            <label for="eski_type"><strong>Eski Sektör</strong></label>
                                            <input class="form-control" type="text" placeholder="E-mail" name="eski_type" value="<?= $eski_type ?>" readonly>
                                            <br>
                                            <label for="comp_type" style="color:red"><strong>Yeni Sektör</strong></label>
                                            <input class="form-control" style="border: 2px solid red;" placeholder="İşletmenin çalıştığı sektör:" list="comp_type" name="comp_type" autocomplete="off" value="<?= $company->comp_type ?>" reqiured />
                                        </div>
                                        <?php } ?>
                                      <div class="col-sm-6">
                                        <?php
                                          if ($eski_phone != $company->phone) { ?>
                                            <label for="eski_phone"><strong>Eski Telefon No</strong></label>
                                            <input class="form-control" type="tel" name="eski_phone" value="<?= $eski_phone ?>" readonly>
                                            <br>
                                            <label for="phone" style="color:red"><strong>Yeni Telefon No</strong></label>
                                            <input class="form-control" style="border: 2px solid red;" type="tel" name="phone" placeholder="Tel: 0XXXXXXXXXX" pattern="(\d{4})(\d{3})(\d{2})(\d{2})" value="<?= $company->phone ?>" maxlength="11" required>
                                        <?php } ?>
                                      </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                      <?php
                                        if ($eski_city != $company->city) { ?>
                                      <div class="col-sm-4">
                                        <br>
                                            <label for="eski_city"><strong>Eski Şehir</strong></label>
                                            <input class="form-control" type="text" name="eski_town" value="<?= $eski_town ?>" readonly>
                                            <br>
                                            <label for="city" style="color:red"><strong>Yeni Şehir</strong></label>
                                            <input class="form-control" style="border: 2px solid red;" type="text" placeholder="Şehir" name="city" value="<?= $company->city ?>" required>
                                      </div>
                                      <?php }
                                      if ($eski_town != $company->town) { ?>
                                          <div class="col-sm-4">
                                            <br>
                                            <label for="eski_town"><strong>Eski İlçe</strong></label>
                                            <input class="form-control" type="text" placeholder="E-mail" name="eski_city" value="<?= $eski_city ?>" readonly>
                                            <br>
                                            <label for="town" style="color:red"><strong>Yeni İlçe</strong></label>
                                            <input class="form-control" style="border: 2px solid red;" type="text" placeholder="İlçe" name="town" value="<?= $company->town ?>" required>
                                          </div>
                                    <?php }
                                    if ($eski_contract_date != $company->contract_date) { ?>
                                      <div class="col-sm-4">
                                        <br>
                                        <label for="eski_contract_date"><strong>Eski Anlaşma Tarihi</strong></label>
                                        <input class="form-control" type="date" name="eski_contract_date" value="<?= $eski_contract_date?>" readonly>
                                        <br>
                                        <label for="contract_date" style="color:red"><strong>Yeni Anlaşma Tarihi:&emsp;</strong></label>
                                        <input class="form-control" style="border: 2px solid red;" type="date" placeholder="Anlaşma Tarihi" name="contract_date" value="<?= $company->contract_date ?>" required>
                                      </div>
                                    <?php }
                                    if ($eski_address != $company->address) { ?>
                                      <div class="col-sm-4">
                                        <br>
                                        <label for="eski_address"><strong>Eski Adres</strong></label>
                                        <input class="form-control" type="text" name="eski_address" value="<?= $eski_address ?>" readonly>
                                        <br>
                                        <label for="address" style="color:red"><strong>Yeni Adres:&emsp;</strong></label>
                                        <input class="form-control" style="border: 2px solid red;" type="text" placeholder="Adres" name="address" value="<?= $company->address ?>" required>
                                      </div>
                                    <?php }
                                    if ($eski_remi_freq != $company->remi_freq) { ?>
                                      <div class="col-sm-4">
                                        <br>
                                        <label for="eski_remi_freq"><strong>Eski Ziyaret Sıklığı(Ay)</strong></label>
                                        <input class="form-control" type="text" name="eski_remi_freq" value="<?= $eski_remi_freq ?>" readonly>
                                        <br>
                                        <label for="remi_freq" style="color:red"><strong>Yeni Ziyaret Sıklığı(Ay):&emsp;</strong></label>
                                        <input class="form-control" style="border: 2px solid red;" type="text" placeholder="Ziyaret Sıklığı" name="remi_freq" value="<?= $company->remi_freq ?>" required>
                                      </div>
                                    <?php }
                                    if ($eski_mersis_no != $company->mersis_no) { ?>
                                      <div class="col-sm-4">
                                        <br>
                                        <label for="eski_mersis_no"><strong>Eski Mersis No</strong></label>
                                        <input class="form-control" type="text" name="eski_mersis_no" value="<?= $eski_mersis_no ?>" readonly>
                                        <br>
                                        <label for="mersis_no" style="color:red"><strong>Yeni Mersis No:&emsp;</strong></label>
                                        <input class="form-control" style="border: 2px solid red;" type="text" placeholder="Mersis No" name="mersis_no" value="<?= $company->mersis_no ?>" required>
                                      </div>
                                    <?php }
                                    if ($eski_sgk_sicil != $company->sgk_sicil) { ?>
                                      <div class="col-sm-4">
                                        <br>
                                        <label for="eski_sgk_sicil"><strong>Eski SGK Sicil No</strong></label>
                                        <input class="form-control" type="text" name="eski_contract_date" value="<?= $eski_sgk_sicil ?>" readonly>
                                        <br>
                                        <label for="sgk_sicil" style="color:red"><strong>Yeni SGK Sicil No:&emsp;</strong></label>
                                        <input class="form-control" style="border: 2px solid red;" type="text" name="sgk_sicil" value="<?= $company->sgk_sicil ?>" required>
                                      </div>
                                    <?php }
                                    if ($eski_vergi_no != $company->vergi_no) { ?>
                                      <div class="col-sm-4">
                                        <br>
                                        <label for="eski_vergi_no"><strong>Eski Vergi No</strong></label>
                                        <input class="form-control" type="text" name="eski_vergi_no" value="<?= $eski_vergi_no ?>" readonly>
                                        <br>
                                        <label for="vergi_no" style="color:red"><strong>Yeni Vergi No:&emsp;</strong></label>
                                        <input class="form-control" style="border: 2px solid red;" type="text" name="vergi_no" value="<?= $company->vergi_no ?>" required>
                                      </div>
                                    <?php }
                                    if ($eski_vergi_dairesi != $company->vergi_dairesi) { ?>
                                      <div class="col-sm-4">
                                        <br>
                                        <label for="eski_vergi_dairesi"><strong>Eski Vergi Dairesi</strong></label>
                                        <input class="form-control" type="text" name="eski_vergi_dairesi" value="<?= $eski_vergi_dairesi ?>" readonly>
                                        <br>
                                        <label for="vergi_dairesi" style="color:red"><strong>Yeni Vergi Dairesi:&emsp;</strong></label>
                                        <input class="form-control" style="border: 2px solid red;" type="text" name="vergi_dairesi" value="<?= $company->vergi_dairesi ?>" required>
                                      </div>
                                    <?php }
                                    if ($eski_katip_is_yeri_id != $company->katip_is_yeri_id) { ?>
                                      <div class="col-sm-4">
                                        <br>
                                        <label for="eski_katip_is_yeri_id"><strong>Eski Katip İş Yeri ID</strong></label>
                                        <input class="form-control" type="text" name="eski_katip_is_yeri_id" value="<?= $eski_katip_is_yeri_id ?>" readonly>
                                        <br>
                                        <label for="katip_is_yeri_id" style="color:red"><strong>Yeni Katip İş Yeri ID:&emsp;</strong></label>
                                        <input class="form-control" style="border: 2px solid red;" type="text" name="katip_is_yeri_id" value="<?= $company->katip_is_yeri_id ?>" required>
                                      </div>
                                    <?php }
                                    if ($eski_katip_kurum_id != $company->katip_kurum_id) { ?>
                                      <div class="col-sm-4">
                                        <br>
                                        <label for="eski_katip_kurum_id"><strong>Eski Katip Kurum ID</strong></label>
                                        <input class="form-control" type="text" name="eski_katip_kurum_id" value="<?= $eski_katip_kurum_id ?>" readonly>
                                        <br>
                                        <label for="katip_kurum_id" style="color:red"><strong>Yeni Katip Kurum ID:&emsp;</strong></label>
                                        <input class="form-control" style="border: 2px solid red;" type="text" name="katip_kurum_id" value="<?= $company->katip_kurum_id ?>" required>
                                      </div>
                                    <?php } ?>
                                    </div>
                                    <br>
                                    <div style="float: right;">
                                      <?php if ($change == "0") {
                                  ?>
                                      <button name="onay" id="onay" type="submit" class="btn btn-success">Düzenlemeyi Onayla</button>
                                      <button name="düzenleme_iptal" id="düzenleme_iptal" type="submit" class="btn btn-danger">Düzenlemeleri İptal Et</button>
                                      <?php
                              }
                              if ($change == "2") {
                                  ?>
                                      <button type="submit" class="btn btn-danger" name="sil" id="sil">İşletmeyi Sil</button>
                                      <button name="düzenleme_iptal" id="düzenleme_iptal" type="submit" class="btn btn-danger">Düzenlemeleri İptal Et</button>
                                      <?php
                              } ?>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>
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
                          <td><strong>Düzenleyen</strong></td>
                          <td><strong>Düzenlenme Tarihi</strong></td>
                          <td><strong>Düzenle/Sil</strong></td>
                        </tr>
                      </tfoot>
                    </table>
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
  <script>
    $('#exampleModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var recipient = button.data('whatever') // Extract info from data-* attributes
      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      var modal = $(this)
      modal.find('.modal-title').text('New message to ' + recipient)
      modal.find('.modal-body input').val(recipient)
    })

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
