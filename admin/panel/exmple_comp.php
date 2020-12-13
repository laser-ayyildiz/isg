require_once('../../calendar/utils/auth.php');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header('Location: ../../../login.php');
    exit;
}
$id=$_SESSION['user_id'];
require '../../../connect.php';
require '../core/baslangic.php';
require '../core/scripts.html';
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title><?= $isim ?></title>
  <link rel="shortcut icon" href="../../../images/osgb_amblem.ico">
  </link>
  <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
  <link rel="stylesheet" href="../../assets/fonts/fontawesome-all.min.css">
  <link rel="stylesheet" href="../../assets/fonts/font-awesome.min.css">
  <link rel="stylesheet" href="../../assets/fonts/ionicons.min.css">
  <link rel="stylesheet" href="../../assets/fonts/fontawesome5-overrides.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
  <link href='../../calendar/css/fullcalendar.min.css' rel='stylesheet' />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
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
      box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
      z-index: 1;
    }

    .dropdown-content a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }

    .dropdown-content a:hover {
      background-color: #ddd;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }

    .dropdown:hover .dropbtn {
      background-color: #3e8e41;
    }

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
      color: #fff;
      /* bootstrap default styles make it black. undo */
      background-color: #0065A6;
    }
  </style>
</head>

<body id="page-top">
  <nav class="navbar shadow navbar-expand mb-3 bg-warning topbar static-top">
    <img width="55" height="40" class="rounded-circle img-profile" src="../../assets/img/nav_brand.jpg" />
    <?php if ($giriş_auth == 7){ ?>
      <a class="navbar-brand" title="Anasayfa" style="color: black;" href="index.php?tab=genel_bilgiler"><b><?= mb_convert_case($isim, MB_CASE_TITLE, "UTF-8") ?></b></a>
    <?php }
    else{ ?>
    <a class="navbar-brand" title="Anasayfa" style="color: black;" href="../../index.php"><b>Özgür OSGB</b></a>
  <?php } ?>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span></button>
    <ul class="nav navbar-nav navbar-expand flex-nowrap ml-auto">
      <li class="nav-item dropdown no-arrow mx-1">
        <div class="nav-item dropdown no-arrow">
          <?php
            $bildirim_say = $pdo->query("SELECT COUNT(*) FROM `notifications` WHERE `user_id` = '$id' ORDER BY reg_date")->fetchColumn();
                  ?>
        <a href="../../notifications.php" title="Bildirimler" class="nav-link"
          data-bs-hover-animate="rubberBand">
          <i style="color: black;" class="fas fa-bell fa-fw"></i>
          <span class="badge badge-danger badge-counter"><?= $bildirim_say ?></span></a>
        </div>
      </li>
      <li class="nav-item dropdown no-arrow mx-1">
        <div class="nav-item dropdown no-arrow">
          <a style="color: black;" title="Mesajlar" href="../../messages.php" class="dropdown-toggle nav-link"
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
          <a href="../../profile.php" class="nav-link" title="Profil">
            <span style="color:black;" class="d-none d-lg-inline mr-2 text-600"><?=$fn?> <?=$ln?></span><img
              class="rounded-circle img-profile" src="../../assets/users/<?=$picture?>"></a>
      </li>
      <div class="d-none d-sm-block topbar-divider"></div>
        <li class="nav-item"><a style="color: black;" title="Çıkış" class="nav-link" href="../../logout.php"><i class="fas fa-sign-out-alt"></i><span>&nbsp;Çıkış</span></a></li>
        </div>
    </ul>
  </nav>
  <div class="container-fluid">
    <div class="card border">
      <div class="card-header tab-card-header text-center bg-light text-dark border">
        <h1><b><?= mb_convert_case($isim, MB_CASE_TITLE, "UTF-8")?></b></h1>
        <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link <?php echo $_GET['tab'] === 'genel_bilgiler' ? 'active' : ''; ?>" id="gb-tab" data-toggle="tab" href="#genel_bilgiler" role="tab" aria-controls="Genel Bilgiler" aria-selected="true"><b>Bilgiler</b></a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $_GET['tab'] === 'osgb_calisanlar' ? 'active' : ''; ?>" id="oc-tab" data-toggle="tab" href="#osgb_calisanlar" role="tab" aria-controls="OSGB Çalışanları" aria-selected="false"><b>OSGB Çalışanları</b></a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $_GET['tab'] === 'devlet_bilgileri' ? 'active' : ''; ?>" id="db-tab" data-toggle="tab" href="#devlet_bilgileri" role="tab" aria-controls="Devlet Bilgileri" aria-selected="false"><b>Devlet Bilgileri</b></a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $_GET['tab'] === 'isletme_calisanlar' ? 'active' : ''; ?>" id="ic-tab" data-toggle="tab" href="#isletme_calisanlar" role="tab" aria-controls="İşletme Çalışanları" aria-selected="false"><b>Çalışanlar</b></a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?php echo $_GET['tab'] === 'isletme_takvim' ? 'active' : ''; ?>" id="it-tab" data-toggle="tab" href="#isletme_takvim" role="tab" aria-controls="İşletme Takvimi" aria-selected="false"><b>Takvim</b></a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $_GET['tab'] === 'isletme_ekipman' ? 'active' : ''; ?>" id="ie-tab" data-toggle="tab" href="#isletme_ekipman" role="tab" aria-controls="İşletme Ekipmanları" aria-selected="false"><b>Ekipmanlar</b></a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $_GET['tab'] === 'isletme_rapor' ? 'active' : ''; ?>" id="ir-tab" data-toggle="tab" href="#isletme_rapor" role="tab" aria-controls="İşletme Raporları" aria-selected="false"><b>Raporlar</b></a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $_GET['tab'] === 'ziyaret_rapor' ? 'active' : ''; ?>" id="zr-tab" data-toggle="tab" href="#ziyaret_rapor" role="tab" aria-controls="Ziyaret Raporları" aria-selected="false"><b>Ziyaret Raporları</b></a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo $_GET['tab'] === 'silinen_calisanlar' ? 'active' : ''; ?>" id="sc-tab" data-toggle="tab" href="#silinen_calisanlar" role="tab" aria-controls="Silinen Çalışanlar"><b>Arşiv</b></a>
          </li>
        </ul>
      </div>
      <div class="card-body">
        <?php
            $sorgu=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `name` = '$isim' AND `change` = 1");
            $sorgu->execute();
            $companies=$sorgu-> fetchAll(PDO::FETCH_OBJ);
            foreach ($companies as $company) {}
            ?>
        <div class="tab-content" id="myTabContent">
          <!--Genel Bilgiler -->
          <div class="tab-pane fade show <?php echo $_GET['tab'] === 'genel_bilgiler' ? 'active' : ''; ?>" id="genel_bilgiler" role="tabpanel" aria-labelledby="gb-tab">
            <fieldset id="gb_form">
              <button style="float:right;" class="btn btn-success ml-1" data-toggle="modal" data-target="#addUser" id="addUserBtn" data-whatever="@getbootstrap">Kullanıcı Oluştur</button>
              <button style="float:right;" class="btn btn-danger" data-toggle="modal" data-target="#changeCompany" id="changeCompanyBtn" data-whatever="@getbootstrap">İşletme Bilgilerini Değiştir / İşletmeyi Sil</button>
              <div class="form-row">
                <div class="form-group col-lg-3">
                  <label for="comp_type_show">
                    <h5><b>Sektör</b></h5>
                  </label>
                  <input class="form-control" id="comp_type_show" name="comp_type_show" required value="<?= $company->comp_type ?>"</input>
                  </select>
                </div>
                <div class="form-group col-lg-4">
                  <label for="is_veren_show">
                    <h5><b>İşveren Ad Soyad</b></h5>
                  </label>
                  <input class="form-control" id="is_veren_show" name="is_veren_show" required value="<?= $company->is_veren ?>"</input>
                  </select>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-lg-4">
                  <label for="mail">
                    <h5><b>Mail Adresi</b></h5>
                  </label>
                  <input type="text" class="form-control" name="mail" id="mail" value="<?= $company->mail ?>" required>
                </div>
                <div class="form-group col-lg-4">
                  <label for="phone">
                    <h5><b>Telefon No</b></h5>
                  </label>
                  <input type="tel" name="phone" id="phone" class="form-control" placeholder="Tel: 0XXXXXXXXXX" pattern="(\d{4})(\d{3})(\d{2})(\d{2})" maxlength="11" required value="<?= $company->phone ?>"></label>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-lg-12">
                  <label for="address">
                    <h5><b>Adres</b></h5>
                  </label>
                  <textarea class="form-control" id="address" name="address" rows="3" style="max-width: 100%;" maxlength="2500" required><?= $company->address?></textarea>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-lg-4">
                  <label for="contract_date">
                    <h5><b>Anlaşma Tarihi</b></h5>
                  </label>
                  <input type="date" class="form-control" name="contract_date" id="contract_date" value="<?= $company->contract_date ?>" required>
                </div>
                <div class="form-group col-lg-4">
                  <label for="countrySelect">
                    <h5><b>Şehir</b></h5>
                  </label>
                  <input class="form-control" required value="<?= $company->city ?>"</input>
                </div>
                <div class="form-group col-lg-4">
                  <label for="citySelect">
                    <h5><b>İlçe</b></h5>
                  </label>
                  <input class="form-control" required value="<?= $company->town ?>"</input>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-lg-4">
                  <label for="remi_freq">
                    <h5><b>Ziyaret Sıklığı</b></h5>
                  </label>
                  <select class="form-control" id="remi_freq" name="remi_freq" size="1" required>
                    <option value="" disabled>Ziyaret Sıklığı Ayarla</option>
                    <?php
                          if ($company->remi_freq != 0) {
                          ?>
                    <option value=<?=$company->remi_freq ?> selected><?=$company->remi_freq ?> Ay</option>
                    <?php
                            } ?>
                    <option value=1>1 Ay</option>
                    <option value=2>2 Ay</option>
                    <option value=3>3 Ay</option>
                    <option value=4>4 Ay</option>
                    <option value=5>5 Ay</option>
                    <option value=6>6 Ay</option>
                    <option value=7>7 Ay</option>
                    <option value=8>8 Ay</option>
                    <option value=9>9 Ay</option>
                    <option value=10>10 Ay</option>
                    <option value=11>11 Ay</option>
                    <option value=12>12 Ay</option>
                    <option value=18>18 Ay</option>
                    <option value=24>24 Ay</option>
                    <option value=36>36 Ay</option>
                  </select>
                </div>
              </div>
            </fieldset>
          </div>

          <!--OSGB Çalışanları -->
          <div class="tab-pane fade show <?php echo $_GET['tab'] === 'osgb_calisanlar' ? 'active' : ''; ?>" id="osgb_calisanlar" role="tabpanel" aria-labelledby="oc-tab">
            <fieldset id="oc_form">
              <!--İsg Uzmanları -->
              <div class="row">
                <!--1. İsg Uzmanı -->
                <div class="col-lg-4">
                  <label for="uzman">
                    <h5><b>1. İsg Uzmanı</b></h5>
                  </label>
                  <?php
                          $user_id = $company->uzman_id;
                          $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                          $sor->execute();
                          $uzmans=$sor->fetchAll(PDO::FETCH_OBJ);
                          foreach ($uzmans as $uzman) {
                              $first_ = $uzman->firstname;
                              $last_ = $uzman->lastname;
                          } ?>
                  <select class="form-control" id="uzman" name="uzman" size="1">
                    <?php
                          if ($company->uzman_id != 0) {
                              ?>
                    <option value="<?= $company->uzman_id ?>" selected><?= $first_." ".$last_ ?></option>
                    <?php
                          } else {
                              ?>
                    <option disabled selected>İsg Uzmanı Seç</option>
                    <?php
                          }
                            $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 0");
                            $sor->execute();
                            $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                            foreach ($workers as $worker) {
                                $mail_ = $worker->username;
                                $sor2=$pdo->prepare("SELECT * FROM `osgb_workers` WHERE `mail` = '$mail_'");
                                $sor2->execute();
                                $workers2=$sor2->fetchAll(PDO::FETCH_OBJ);
                                foreach ($workers2 as $worker2) {
                                    $type = $worker2->user_type;
                                } ?>
                    <option value="<?= $worker->id ?>"><?= $type ?> <?= $worker->firstname ?> <?= $worker->lastname ?></option>
                    <?php
                              } ?>
                  </select>
                </div>

                <!--2. İsg Uzmanı -->
                <div class="col-lg-4">
                  <label for="uzman_2">
                    <h5><b>2.İsg Uzmanı</b></h5>
                  </label>
                  <?php
                          $user_id = $company->uzman_id_2;
                          $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                          $sor->execute();
                          $uzmans=$sor->fetchAll(PDO::FETCH_OBJ);
                          foreach ($uzmans as $uzman) {
                              $first_ = $uzman->firstname;
                              $last_ = $uzman->lastname;
                          } ?>
                  <select class="form-control" id="uzman_2" name="uzman_2" size="1">
                    <?php
                          if ($company->uzman_id_2 != 0) {
                              ?>
                    <option value="<?= $company->uzman_id_2 ?>" selected><?= $first_." ".$last_ ?></option>
                    <?php
                          } else {
                              ?>
                    <option disabled selected>İsg Uzmanı Seç</option>
                    <?php
                          }
                            $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 0");
                            $sor->execute();
                            $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                            foreach ($workers as $worker) {
                                $mail_ = $worker->username;
                                $sor2=$pdo->prepare("SELECT * FROM `osgb_workers` WHERE `mail` = '$mail_'");
                                $sor2->execute();
                                $workers2=$sor2->fetchAll(PDO::FETCH_OBJ);
                                foreach ($workers2 as $worker2) {
                                    $type = $worker2->user_type;
                                } ?>
                    <option value="<?= $worker->id ?>"><?= $type ?> <?= $worker->firstname ?> <?= $worker->lastname ?></option>
                    <?php
                              } ?>
                  </select>
                </div>

                <!--3. İsg Uzmanı -->
                <div class="col-lg-4">
                  <label for="uzman_3">
                    <h5><b>3.İsg Uzmanı</b></h5>
                  </label>
                  <?php
                          $user_id = $company->uzman_id_3;
                          $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                          $sor->execute();
                          $uzmans=$sor->fetchAll(PDO::FETCH_OBJ);
                          foreach ($uzmans as $uzman) {
                              $first_ = $uzman->firstname;
                              $last_ = $uzman->lastname;
                          } ?>
                  <select class="form-control" id="uzman_3" name="uzman_3" size="1">
                    <?php
                          if ($company->uzman_id_3 != 0) {
                              ?>
                    <option value="<?= $company->uzman_id_3 ?>" selected><?= $first_." ".$last_ ?></option>
                    <?php
                          } else {
                              ?>
                    <option disabled selected>İsg Uzmanı Seç</option>
                    <?php
                          }
                            $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 0");
                            $sor->execute();
                            $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                            foreach ($workers as $worker) {
                                $mail_ = $worker->username;
                                $sor2=$pdo->prepare("SELECT * FROM `osgb_workers` WHERE `mail` = '$mail_'");
                                $sor2->execute();
                                $workers2=$sor2->fetchAll(PDO::FETCH_OBJ);
                                foreach ($workers2 as $worker2) {
                                    $type = $worker2->user_type;
                                } ?>
                    <option value="<?= $worker->id ?>"><?= $type ?> <?= $worker->firstname ?> <?= $worker->lastname ?></option>
                    <?php
                              } ?>
                  </select>
                </div>
              </div>
              <br>
              <hr style="border-top: 1px dashed red;">
              <br>

              <!--İş Yeri Hekimleri-->
              <div class="row">

                <!--1 İş Yeri Hekimi -->
                <div class="col-lg-4">
                  <label for="hekim">
                    <h5><b>1. İş Yeri Hekimi</b></h5>
                  </label>
                  <?php
                        $user_id = $company->hekim_id;
                            $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                            $sor->execute();
                            $hekimler=$sor->fetchAll(PDO::FETCH_OBJ);
                            foreach ($hekimler as $hekim) {
                                $first_ = $hekim->firstname;
                                $last_ = $hekim->lastname;
                            } ?>
                  <select class="form-control" id="hekim" name="hekim" size="1">
                    <?php
                          if ($company->hekim_id != 0) {
                              ?>
                    <option value="<?= $company->hekim_id ?>" selected><?= $first_." ".$last_ ?></option>
                    <?php
                          } else {
                              ?>
                    <option disabled selected>İş Yeri Hekimi Seç</option>
                    <?php
                            }
                              $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 2");
                              $sor->execute();
                              $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                              foreach ($workers as $worker) {
                                  ?>
                    <option value=<?= $worker->id ?>><?= $worker->firstname ?> <?= $worker->lastname ?></option>
                    <?php
                              } ?>
                  </select>
                </div>

                <!--2. İş Yeri Hekimi -->
                <div class="col-lg-4">
                  <label for="hekim_2">
                    <h5><b>2. İş Yeri Hekimi</b></h5>
                  </label>
                  <?php
                        $user_id = $company->hekim_id_2;
                            $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                            $sor->execute();
                            $hekimler=$sor->fetchAll(PDO::FETCH_OBJ);
                            foreach ($hekimler as $hekim) {
                                $first_ = $hekim->firstname;
                                $last_ = $hekim->lastname;
                            } ?>
                  <select class="form-control" id="hekim_2" name="hekim_2" size="1">
                    <?php
                          if ($company->hekim_id_2 != 0) {
                              ?>
                    <option value="<?= $company->hekim_id_2 ?>" selected><?= $first_." ".$last_ ?></option>
                    <?php
                          } else {
                              ?>
                    <option disabled selected>İş Yeri Hekimi Seç</option>
                    <?php
                            }
                              $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 2");
                              $sor->execute();
                              $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                              foreach ($workers as $worker) {
                                  ?>
                    <option value=<?= $worker->id ?>><?= $worker->firstname ?> <?= $worker->lastname ?></option>
                    <?php
                              } ?>
                  </select>
                </div>

                <!--3. İş Yeri Hekimi -->
                <div class="col-lg-4">
                  <label for="hekim_3">
                    <h5><b>3. İş Yeri Hekimi</b></h5>
                  </label>
                  <?php
                        $user_id = $company->hekim_id_3;
                            $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                            $sor->execute();
                            $hekimler=$sor->fetchAll(PDO::FETCH_OBJ);
                            foreach ($hekimler as $hekim) {
                                $first_ = $hekim->firstname;
                                $last_ = $hekim->lastname;
                            } ?>
                  <select class="form-control" id="hekim_3" name="hekim_3" size="1">
                    <?php
                          if ($company->hekim_id_3 != 0) {
                              ?>
                    <option value="<?= $company->hekim_id_3 ?>" selected><?= $first_." ".$last_ ?></option>
                    <?php
                          } else {
                              ?>
                    <option disabled selected>İş Yeri Hekimi Seç</option>
                    <?php
                            }
                              $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 2");
                              $sor->execute();
                              $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                              foreach ($workers as $worker) {
                                  ?>
                    <option value=<?= $worker->id ?>><?= $worker->firstname ?> <?= $worker->lastname ?></option>
                    <?php
                              } ?>
                  </select>
                </div>
              </div>
              <br>
              <hr style="border-top: 1px dashed red;">
              <br>

              <!--1. Personeller -->
              <div class="row">
                <div class="col-lg-4">
                  <label for="saglık">
                    <h5><b>Sağlık Personeli</b></h5>
                  </label>
                  <?php
                        $user_id = $company->saglık_p_id;
                        $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                        $sor->execute();
                        $sağlıkçılar=$sor->fetchAll(PDO::FETCH_OBJ);
                        foreach ($sağlıkçılar as $sağlıkçı) {
                            $first_ = $sağlıkçı->firstname;
                            $last_ = $sağlıkçı->lastname;
                        } ?>
                  <select class="form-control" id="saglık" name="saglık" size="1">
                    <?php
                          if ($company->saglık_p_id != 0) {
                              ?>
                    <option value="<?= $company->saglık_p_id ?>" selected><?= $first_." ".$last_ ?></option>
                    <?php
                          } else {
                              ?>
                    <option disabled selected>İş Yeri Hekimi Seç</option>
                    <?php
                          }
                          $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 3");
                          $sor->execute();
                          $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                          foreach ($workers as $worker) {
                              ?>
                    <option value=<?= $worker->id ?>><?= $worker->firstname ?> <?= $worker->lastname ?></option>
                    <?php
                          } ?>
                  </select>
                </div>

                <div class="col-lg-4">
                  <label for="ofis">
                    <h5><b>Ofis Personeli</b></h5>
                  </label>
                  <?php
                        $user_id = $company->ofis_p_id;
                        $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                        $sor->execute();
                        $ofisler=$sor->fetchAll(PDO::FETCH_OBJ);
                        foreach ($ofisler as $ofis) {
                            $first_ = $ofis->firstname;
                            $last_ = $ofis->lastname;
                        } ?>
                  <select class="form-control" id="ofis" name="ofis" size="1">
                    <?php
                          if ($company->ofis_p_id != 0) {
                              ?>
                    <option value="<?= $company->ofis_p_id ?>" selected><?= $first_." ".$last_ ?></option>
                    <?php
                          } else {
                              ?>
                    <option disabled selected>Ofis Personeli Seç</option>
                    <?php
                          }
                        $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 4");
                        $sor->execute();
                        $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                        foreach ($workers as $worker) {
                            ?>
                    <option value=<?= $worker->id ?>><?= $worker->firstname ?> <?= $worker->lastname ?></option>
                    <?php
                          } ?>
                  </select>
                </div>

                <div class="col-lg-4">
                  <label for="muhasebe">
                    <h5><b>Muhasebe Personeli</b></h5>
                  </label>
                  <?php
                        $user_id = $company->muhasebe_p_id;
                        $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                        $sor->execute();
                        $muhasebeler=$sor->fetchAll(PDO::FETCH_OBJ);
                        foreach ($muhasebeler as $muhasebe) {
                            $first_ = $muhasebe->firstname;
                            $last_ = $muhasebe->lastname;
                        } ?>
                  <select class="form-control" id="muhasebe" name="muhasebe" size="1">
                    <?php
                          if ($company->muhasebe_p_id != 0) {
                              ?>
                    <option value="<?= $company->muhasebe_p_id ?>" selected><?= $first_." ".$last_ ?></option>
                    <?php
                          } else {
                              ?>
                    <option disabled selected>Muhasebe Personeli Seç</option>
                    <?php
                                      }
                            $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 6");
                            $sor->execute();
                            $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                            foreach ($workers as $worker) {
                                ?>
                    <option value=<?= $worker->id ?>><?= $worker->firstname ?> <?= $worker->lastname ?></option>
                    <?php
                            } ?>
                  </select>
                </div>
              </div>
              <br>
              <br>

              <!--2. Personeller -->
              <div class="row">
                <!--2. Sağlık Personeli -->
                <div class="col-lg-4">
                  <label for="saglık_2">
                    <h5><b>2.Sağlık Personeli</b></h5>
                  </label>
                  <?php
                        $user_id = $company->saglık_p_id_2;
                        $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                        $sor->execute();
                        $sağlıkçılar=$sor->fetchAll(PDO::FETCH_OBJ);
                        foreach ($sağlıkçılar as $sağlıkçı) {
                            $first_ = $sağlıkçı->firstname;
                            $last_ = $sağlıkçı->lastname;
                        } ?>
                  <select class="form-control" id="saglık_2" name="saglık_2" size="1">
                    <?php
                          if ($company->saglık_p_id_2 != 0) {
                              ?>
                    <option value="<?= $company->saglık_p_id_2 ?>" selected><?= $first_." ".$last_ ?></option>
                    <?php
                          } else {
                              ?>
                    <option disabled selected>İş Yeri Hekimi Seç</option>
                    <?php
                          }
                          $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 3");
                          $sor->execute();
                          $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                          foreach ($workers as $worker) {
                              ?>
                    <option value=<?= $worker->id ?>><?= $worker->firstname ?> <?= $worker->lastname ?></option>
                    <?php
                          } ?>
                  </select>
                </div>

                <!--2. Ofis Personeli -->
                <div class="col-lg-4">
                  <label for="ofis_2">
                    <h5><b>2.Ofis Personeli</b></h5>
                  </label>
                  <?php
                        $user_id = $company->ofis_p_id_2;
                        $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                        $sor->execute();
                        $ofisler=$sor->fetchAll(PDO::FETCH_OBJ);
                        foreach ($ofisler as $ofis) {
                            $first_ = $ofis->firstname;
                            $last_ = $ofis->lastname;
                        } ?>
                  <select class="form-control" id="ofis_2" name="ofis_2" size="1">
                    <?php
                          if ($company->ofis_p_id_2 != 0) {
                              ?>
                    <option value="<?= $company->ofis_p_id_2 ?>" selected><?= $first_." ".$last_ ?></option>
                    <?php
                          } else {
                              ?>
                    <option disabled selected>Ofis Personeli Seç</option>
                    <?php
                          }
                        $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 4");
                        $sor->execute();
                        $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                        foreach ($workers as $worker) {
                            ?>
                    <option value=<?= $worker->id ?>><?= $worker->firstname ?> <?= $worker->lastname ?></option>
                    <?php
                          } ?>
                  </select>
                </div>

                <!--2. Muhasebe Personeli -->
                <div class="col-lg-4">
                  <label for="muhasebe_2">
                    <h5><b>2.Muhasebe Personeli</b></h5>
                  </label>
                  <?php
                        $user_id = $company->muhasebe_p_id_2;
                        $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                        $sor->execute();
                        $muhasebeler=$sor->fetchAll(PDO::FETCH_OBJ);
                        foreach ($muhasebeler as $muhasebe) {
                            $first_ = $muhasebe->firstname;
                            $last_ = $muhasebe->lastname;
                        } ?>
                  <select class="form-control" id="muhasebe_2" name="muhasebe_2" size="1">
                    <?php
                          if ($company->muhasebe_p_id_2 != 0) {
                              ?>
                    <option value="<?= $company->muhasebe_p_id_2 ?>" selected><?= $first_." ".$last_ ?></option>
                    <?php
                          } else {
                              ?>
                    <option disabled selected>Muhasebe Personeli Seç</option>
                    <?php
                                      }
                            $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 6");
                            $sor->execute();
                            $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                            foreach ($workers as $worker) {
                                ?>
                    <option value=<?= $worker->id ?>><?= $worker->firstname ?> <?= $worker->lastname ?></option>
                    <?php
                            } ?>
                  </select>
                </div>

              </div>
              <br>
            </fieldset>
            </form>
          </div>

          <!--Devlet Bilgileri -->
          <div class="tab-pane fade show <?php echo $_GET['tab'] === 'devlet_bilgileri' ? 'active' : ''; ?>" id="devlet_bilgileri" role="tabpanel" aria-labelledby="db-tab">
            <form action="index.php" method="POST">
              <fieldset id="db_form">
                <div class="row col-lg-12">
                  <label for="nace_kodu">
                    <h4><b>NACE Kodu</b></h4>
                  </label>
                  <select name="nace_kodu" id="nace_kodu" class="form-control" required>
                    <option value="<?= $company->nace_kodu ?>" selected><?= $company->nace_kodu ?></option>
                    <option value="81.22.03">81.22.03,Nesne veya binaların (ameliyathaneler vb.) sterilizasyonu faaliyetleri.Binalar ile ilgili hizmetler ve çevre düzenlemesi faaliyetleri 3</option>
                    <option value="82.20.01">82.20.01,Çağrı merkezlerinin faaliyetleri 2</option>
                    <option value="86.90.17">86.90.17,İnsan sağlığı hizmetleri 3</option>
                    <option value="85.59.16">85.59.16,Çocuk kulüplerinin faaliyetleri (6 yaş ve üzeri çocuklar için) 1</option>
                    <option value="71.12.14">71.12.14,Yapı denetim kuruluşları 1</option>
                    <option value="56.10.21">56.10.21,Oturacak yeri olmayan fast-food (hamburger, sandviç, tost vb.) satış yerleri (büfeler dahil), al götür tesisleri (içli pide ve lahmacun fırınları hariç) ve benzerleri tarafından sağlanan diğer
                      yemek hazırlama ve sunum faaliyetleri 1</option>
                    <option value="56.10.20">56.10.20,Oturacak yeri olmayan içli pide ve lahmacun fırınlarının faaliyetleri (al götür tesisi olarak hizmet verenler) 1</option>
                    <option value="47.89.19">47.89.19,Seyyar olarak ve motorlu araçlarla diğer malların perakende ticareti 1</option>
                    <option value="47.82.03">47.82.03,Seyyar olarak ve motorlu araçlarla tekstil, giyim eşyası ve ayakkabı perakende ticareti 1</option>
                    <option value="47.81.12">47.81.12,Seyyar olarak ve motorlu araçlarla gıda ürünleri ve içeceklerin (alkollü içecekler hariç) perakende ticareti 1</option>
                    <option value="47.79.06">47.79.06,Belirli bir mala tahsis edilmiş mağazalarda kullanılmış giysiler ve aksesuarlarının perakende ticareti 1</option>
                    <option value="45.20.09">45.20.09,Motorlu kara taşıtlarının sadece boyanması faaliyetleri 3</option>
                    <option value="25.99.90">25.99.90,Başka yerde sınıflandırılmamış diğer fabrikasyon metal ürünlerin imalatı 2</option>
                    <option value="08.99.01">08.99.01,Aşındırıcı (törpüleyici) materyaller (zımpara), amyant, silisli fosil artıklar, arsenik cevherleri, sabuntaşı (talk) ve feldispat madenciliği (kuartz, mika, şist, talk, silis, sünger taşı, asbest,
                      doğal korindon vb.) 3</option>
                    <option value="08.93.02">08.93.02,Deniz, göl ve kaynak tuzu üretimi (tuzun yemeklik tuza dönüştürülmesi hariç) 2</option>
                    <option value="23.99.07">23.99.07,Amyantlı kağıt imalatı 3</option>
                  </select>
                </div>
                <br>
                <div class="row">
                  <div class="col-6">
                    <label for="mersis_no">
                      <h4><b>Kurum Mersis No</b></h4>
                    </label>
                    <input class="form-control" id="mersis_no" name="mersis_no" type="tel" min="16" maxlength="16" placeholder="Mersis No" value="<?= $company->mersis_no ?>">
                  </div>
                  <div class="col-6">
                    <label for="sgk_sicil">
                      <h4><b>SGK Sicil No</b></h4>
                    </label>
                    <input class="form-control" id="sgk_sicil" name="sgk_sicil" type="tel" min="12" maxlength="12" placeholder="SGK Sicil No" value="<?= $company->sgk_sicil ?>">
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-6">
                    <label for="vergi_no">
                      <h4><b>Vergi No</b></h4>
                    </label>
                    <input class="form-control" id="vergi_no" name="vergi_no" type="tel" min="10" maxlength="10" placeholder="Vergi No" value="<?= $company->vergi_no ?>">
                  </div>
                  <div class="col-6">
                    <label for="vergi_dairesi">
                      <h4><b>Vergi Dairesi</b></h4>
                    </label>
                    <input class="form-control" id="vergi_dairesi" name="vergi_dairesi" type="text" maxlength="500" placeholder="Vergi Dairesi" value="<?= $company->vergi_dairesi ?>">
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-6">
                    <label for="katip_is_yeri_id">
                      <h4><b>İSG-KATİP İş Yeri ID</b></h4>
                    </label>
                    <input class="form-control" id="katip_is_yeri_id" name="katip_is_yeri_id" type="tel" maxlength="30" placeholder="İSG-KATİP İş Yeri ID" value="<?= $company->katip_is_yeri_id ?>">
                  </div>
                  <div class="col-6">
                    <label for="katip_kurum_id">
                      <h4><b>İSG-KATİP Kurum ID</b></h4>
                    </label>
                    <input class="form-control" id="katip_kurum_id" name="katip_kurum_id" type="tel" maxlength="30" placeholder="İSG-KATİP Kurum ID" value="<?= $company->katip_kurum_id ?>">
                  </div>
                </div>
              </fieldset>
            </form>
          </div>

          <!--İşletme Çalışanları -->
          <div class="tab-pane fade show <?php echo $_GET['tab'] === 'isletme_calisanlar' ? 'active' : ''; ?>" id="isletme_calisanlar" role="tabpanel" aria-labelledby="ic-tab">
            <?php
                if (file_exists($isim."_calisanlar.xlsx") || file_exists($isim."_calisanlar.xls") || file_exists($isim."_calisanlar.ods")){
                  ?>
            <button class="btn btn-primary" id="ic_form2" data-toggle="modal" data-target="#addWorker" data-whatever="@getbootstrap">Yeni Çalışan Ekle</button>
            <br><b>Çalışana ait dosyalara erişmek için çalışanın isminin yazılı olduğu kutucuğa tıklayabilirsiniz</b>

            <?php
                }
                else {
                  ?>
            <form method="POST" action="../core/addWorkerList.php" enctype="multipart/form-data">
              <fieldset id="ic_form1">
                <label for="calisan_list"><b>Çalışan Listesi Yükle-></b></label>
                <input type="number" name="company_id" value="<?= $company_id ?>" hidden>
                <input type="text" name="company_name" value="<?= $isim ?>" hidden>
                <input type="file" class="btn btn-light btn-sm" name="calisan_list" />
                <input type="submit" class="btn btn-primary" name="calisan_yukle" value="Yükle" />
              </fieldset>
            </form>
            <?php
                  }
                   ?>
            <input type="text" class="form-control" style="float:right;max-width:600px; margin-bottom:15px;" id="myInput" onkeyup="myFunction()" placeholder="Çalışan Adı ile ara...">
            <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
              <table class="table table-striped table-bordered table-hover" id="dataTable">
                <thead class="thead-dark">
                  <tr>
                    <th>Çalışan Adı Soyadı</th>
                    <th>Pozisyonu</th>
                    <th>Cinsiyeti</th>
                    <th>T.C Kimlik No</th>
                    <th>Telefon No</th>
                    <th>E-mail</th>
                    <th>İşe Giriş Tarihi</th>
                    <th id="delete_header">Sil</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                            $sorgu=$pdo->prepare("SELECT * FROM `coop_workers` WHERE `deleted` = 0 AND `company_id` = '$company_id' ORDER BY `name`");
                            $sorgu->execute();
                            $coworkers=$sorgu-> fetchAll(PDO::FETCH_OBJ);
                            foreach ($coworkers as $key=>$coworker) {
                                ?>
                  <tr>
                    <td data-toggle="modal" data-target="#b<?= $key ?>" data-whatever="@getbootstrap" style="cursor: pointer;"><?= $coworker->name ?></td>
                    <td><?= $coworker->position ?></td>
                    <td><?= $coworker->sex ?></td>
                    <td><?= $coworker->tc ?></td>
                    <td><?= $coworker->phone ?></td>
                    <td><?= $coworker->mail ?></td>
                    <td><?= $coworker->contract_date ?></td>
                    <form action="../core/deleteWorker.php" method="POST">
                      <input type="number" name="company_id" value="<?= $company_id ?>" hidden>
                      <input type="text" name="company_name" value="<?= $isim ?>" hidden>
                      <input type="number" name="TCWillDelete" value="<?= $coworker->tc ?>" hidden readonly>
                      <td><button class="btn btn-danger" type="submit" name="deleteWorkerButton" id="deleteWorkerButton">Sil</button></td>
                    </form>
                  </tr>
                  <!-- Çalışan Dosyaları -->
                  <div class="modal fade" id="b<?= $key ?>" tabindex="-1" aria-labelledby="label" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <div class="modal-header bg-light">
                          <h5 class="modal-title" id="label"><b><?= $coworker->name." Dosyaları"?></b></h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <?php
                                      foreach (new DirectoryIterator(__DIR__.'/'.$coworker->tc) as $file) {
                                        if ($file->isFile()) {
                                          $name = $file->getFilename();
                                          ?>
                          <a href="<?= $coworker->tc.'/'.$file ?>" target="blank"><?= $name ?></a><br>
                          <?php
                                          }
                                        }
                                          ?>
                        </div>
                        <div class="modal-footer bg-light">
                          <form method="POST" action="../core/addWorkerFile.php" enctype="multipart/form-data">
                            <fieldset id="ic_form3">
                              <label for="calisan_dosya"><b>Yeni Dosya Yükle-></b></label>
                              <input name="coworker_tc" type="tel" value="<?= $coworker->tc ?>" hidden>
                              <input type="number" name="company_id" value="<?= $company_id ?>" hidden>
                              <input type="text" name="company_name" value="<?= $isim ?>" hidden>
                              <input type="file" class="btn btn-light btn-sm" name="calisan_dosya" required />
                              <input type="submit" class="btn btn-primary" name="calisan_dosya_yukle" value="Yükle" />
                            </fieldset>
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
                    <td><strong>Çalışan Adı Soyadı</strong></td>
                    <td><strong>Pozisyonu</strong></td>
                    <td><strong>Cinsiyeti</strong></td>
                    <td><strong>T.C Kimlik No</strong></td>
                    <td><strong>Telefon No</strong></td>
                    <td><strong>E-mail</strong></td>
                    <td><strong>İşe Giriş Tarihi</strong></td>
                    <td id="delete_footer"><strong>Sil</strong></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>

          <!--İşletme Takvimi -->
          <div class="tab-pane fade show <?php echo $_GET['tab'] === 'isletme_takvim' ? 'active' : ''; ?>" id="isletme_takvim" role="tabpanel" aria-labelledby="it-tab">
            <div id="calendar" style="margin: auto;" class="col-centered"></div>
          </div>

          <!--İşletme Ekipmanları -->
          <div class="tab-pane fade show <?php echo $_GET['tab'] === 'isletme_ekipman' ? 'active' : ''; ?>" id="isletme_ekipman" role="tabpanel" aria-labelledby="ie-tab">
            <button class="btn btn-primary" id="ie_button" data-toggle="modal" data-target="#addEquipment" data-whatever="@getbootstrap">Yeni Ekipman Ekle</button>
            <input type="text" class="form-control" style="float:right;max-width:600px;" id="myInput" onkeyup="myFunction()" placeholder="Ekipman Adı ile ara...">
            <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
              <table class="table table-striped table-bordered table-hover" id="dataTable">
                <thead class="thead-dark">
                  <tr>
                    <th>Ekipman Adı</th>
                    <th>Ekipmanın Kullanım Amacı</th>
                    <th>Ekipman Kontrol Sıklığı</th>
                    <th>Kayıt Tarihi</th>
                  </tr>
                </thead>
                <tbody>

                  <?php
                      $sorgu=$pdo->prepare("SELECT * FROM `equipment` WHERE `company_id` = '$company_id'");
                      $sorgu->execute();
                      $equipments=$sorgu->fetchAll(PDO::FETCH_OBJ);
                      foreach ($equipments as $equipment) {
                        ?>
                  <tr>
                    <td><?= $equipment->name ?></td>
                    <td><?= $equipment->purpose ?></td>
                    <td><?= $equipment->maintenance_freq ?> Ay</td>
                    <td><?= $equipment->reg_date ?></td>
                  </tr>
                  <?php
                      }
                      ?>

                </tbody>
                <tfoot>
                  <tr>
                    <td><strong>Ekipman Adı</strong></td>
                    <td><strong>Ekipmanın Kullanım Amacı</strong></td>
                    <td><strong>Ekipmaın Kontrol Sıklığı</strong></td>
                    <td><strong>Kayıt Tarihi</strong></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>

          <!--İşletme Raporları -->
          <div class="tab-pane fade show <?php echo $_GET['tab'] === 'isletme_rapor' ? 'active' : ''; ?>" id="isletme_rapor" role="tabpanel" aria-labelledby="ir-tab">
            <button style="float: left;" class="btn btn-primary" id="ir_form" data-toggle="modal" data-target="#addReport" data-whatever="@getbootstrap">Yeni Rapor Hazırla</button>
            <button style="float: right;" class="btn btn-primary" id="ir_form2" data-toggle="modal" data-target="#uploadReport" data-whatever="@getbootstrap">Rapor Yükle</button>
            <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
              <table class="table table-striped table-bordered table-hover table-sm mt-2" id="dataTable">
                <thead class="thead-dark">
                  <tr>
                    <th>Dosya Adı</th>
                    <th>Dosya Tarihi</th>
                    <th>İndir</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                        foreach (new DirectoryIterator(__DIR__.'/isletme_raporlari') as $file) {
                          if ($file->isFile()) {
                            $name = $file->getFilename();
                            $file_name = explode("_", $name);
                            ?>
                  <tr>
                    <td><b><?= $file_name[0] ?></b></td>
                    <td><b><?= $file_name[1].', '.$file_name[2] ?></b></td>
                    <td><input class="btn btn-success btn-sm" style="width:80px" type="button" value="İndir" onclick="window.location.href='isletme_raporlari/<?=$file?>';" /></td>
                  </tr>
                  <?php
                            }
                          }
                          ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td><strong>Dosya Adı</strong></td>
                    <td><strong>Dosya Tarihi</strong></td>
                    <td><strong>İndir</strong></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>

          <!--Ziyaret Raporları -->
          <div class="tab-pane fade show <?php echo $_GET['tab'] === 'ziyaret_rapor' ? 'active' : ''; ?>" id="ziyaret_rapor" role="tabpanel" aria-labelledby="zr-tab">
            <button class="btn btn-primary" id="zr_form" data-toggle="modal" data-target="#addVisitReport" data-whatever="@getbootstrap">Yeni Ziyaret Raporu Ekle</button>
            <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
              <table class="table table-striped table-bordered table-hover table-sm" id="dataTable">
                <thead class="thead-dark">
                  <tr>
                    <th>Dosya Adı</th>
                    <th>Dosya Tarihi</th>
                    <th>İndir</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                        foreach (new DirectoryIterator(__DIR__.'/ziyaret_raporlari') as $file) {
                          if ($file->isFile()) {
                            $name = $file->getFilename();
                            $file_name = explode("_", $name);
                            ?>
                  <tr>
                    <td><b><?= $file_name[0] ?></b></td>
                    <td><b><?= $file_name[1] ?></b></td>
                    <td><input class="btn btn-success btn-sm" style="width:80px" type="button" value="İndir" onclick="window.location.href='ziyaret_raporlari/<?=$file?>';" /></td>
                  </tr>
                  <?php
                            }
                          }
                          ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td><strong>Dosya Adı</strong></td>
                    <td><strong>Dosya Tarihi</strong></td>
                    <td><strong>İndir</strong></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>

          <!--Silinen Çalışanlar -->
          <div class="tab-pane fade show <?php echo $_GET['tab'] === 'silinen_calisanlar' ? 'active' : ''; ?>" id="silinen_calisanlar" role="tabpanel" aria-labelledby="tr-tab">
            <b>Çalışana ait dosyalara erişmek için çalışanın isminin yazılı olduğu kutucuğa tıklayabilirsiniz</b>
            <input type="text" class="form-control" style="float:right;max-width:600px; margin-bottom:15px;" id="myInput" onkeyup="myFunction()" placeholder="Çalışan Adı ile ara...">
            <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
              <table class="table table-striped table-bordered table-hover" id="dataTable">
                <thead class="thead-dark">
                  <tr>
                    <th>Çalışan Adı Soyadı</th>
                    <th>Pozisyonu</th>
                    <th>Cinsiyeti</th>
                    <th>T.C Kimlik No</th>
                    <th>Telefon No</th>
                    <th>E-mail</th>
                    <th>İşe Giriş Tarihi</th>
                    <th>Geri Al</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                        $sorgu=$pdo->prepare("SELECT * FROM `coop_workers` WHERE `deleted` = 1 AND `company_id` = '$company_id' ORDER BY `name`");
                        $sorgu->execute();
                        $coworkers=$sorgu-> fetchAll(PDO::FETCH_OBJ);
                        foreach ($coworkers as $key=>$coworker) {
                            ?>
                  <tr>
                    <td data-toggle="modal" data-target="#c<?= $key ?>" data-whatever="@getbootstrap" style="cursor: pointer;"><?= $coworker->name ?></td>
                    <td><?= $coworker->position ?></td>
                    <td><?= $coworker->sex ?></td>
                    <td><?= $coworker->tc ?></td>
                    <td><?= $coworker->phone ?></td>
                    <td><?= $coworker->mail ?></td>
                    <td><?= $coworker->contract_date ?></td>
                    <form action="../core/recruitAgain.php" method="POST">
                      <td><button class="btn btn-success" type="submit" name="recruitAgain">Geri Al</button></td>
                      <input type="number" name="company_id" value="<?= $company_id ?>" hidden>
                      <input type="text" name="company_name" value="<?= $isim ?>" hidden>
                      <input type="number" name="TCWillRecruit" value="<?= $coworker->tc ?>" hidden readonly>
                    </form>
                  </tr>
                  <!-- Çalışan Dosyaları -->
                  <div class="modal fade" id="c<?= $key ?>" tabindex="-1" aria-labelledby="label" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content modal-lg">
                        <div class="modal-header bg-light">
                          <h5 class="modal-title" id="label"><b><?= $coworker->name." Dosyaları"?></b></h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <?php
                                  foreach (new DirectoryIterator(__DIR__.'/'.$coworker->tc) as $file) {
                                    if ($file->isFile()) {
                                      $name = $file->getFilename();
                                      ?>
                          <a href="<?= $coworker->tc.'/'.$file ?>" target="blank"><?= $name ?></a><br>
                          <?php
                                      }
                                    }
                                      ?>
                          <form method="POST" action="index.php" enctype="multipart/form-data">
                            <fieldset id="ic_form3">
                              <label for="calisan_dosya"><b>Yeni Dosya Yükle-></b></label>
                              <input name="cdir_name" type="tel" value="<?= $coworker->tc ?>" hidden>
                              <input type="file" class="btn btn-light btn-sm" name="calisan_dosya" />
                              <input type="submit" class="btn btn-primary" name="calisan_dosya_yukle" value="Yükle" />
                            </fieldset>
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
                    <td><strong>Çalışan Adı Soyadı</strong></td>
                    <td><strong>Pozisyonu</strong></td>
                    <td><strong>Cinsiyeti</strong></td>
                    <td><strong>T.C Kimlik No</strong></td>
                    <td><strong>Telefon No</strong></td>
                    <td><strong>E-mail</strong></td>
                    <td><strong>İşe Giriş Tarihi</strong></td>
                    <td><strong>Geri Al</strong></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>

        </div><!-- tab content end -->
      </div><!-- card body end -->

      <!-- Yeni çalışan ekleme modal -->
      <div class="modal fade" id="addWorker" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header bg-light">
              <h5 class="modal-title" id="exampleModalLabel"><b>Yeni Çalışan Ekle</b></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="../core/addWorker.php" method="POST">
                <div class="row">
                  <div class="col-6">
                    <label for="worker_name">
                      <h5><b>Çalışanın Adını ve Soyadını Giriniz</b></h5>
                    </label>
                    <input name="worker_name" id="worker_name" class="form-control" type="text" maxlength="100" placeholder="Ad Soyad" required>
                  </div>
                  <div class="col-6">
                    <label for="worker_tc">
                      <h5><b>Çalışanın T.C Kimlik Numarasını Giriniz</b></h5>
                    </label>
                    <input name="worker_tc" id="worker_tc" class="form-control" type="tel" maxlength="11" minlength="11" placeholder="T.C Kimlik No" required>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-6">
                    <label for="worker_mail">
                      <h5><b>Çalışanın E-mail adresini Giriniz</b></h5>
                    </label>
                    <input name="worker_mail" id="worker_mail" class="form-control" type="email" maxlength="100" placeholder="E-mail" required>
                  </div>
                  <div class="col-6">
                    <label for="worker_phone">
                      <h5><b>Çalışanın Telefon Numarasını Giriniz</b></h5>
                    </label>
                    <input name="worker_phone" id="worker_phone" class="form-control" type="tel" maxlength="11" minlength="11" placeholder="05XXXXXXXXX" required>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-6">
                    <label for="worker_position">
                      <h5><b>Çalışanın Posizyonu Giriniz</b></h5>
                    </label>
                    <input name="worker_position" id="worker_position" class="form-control" type="text" maxlength="100" placeholder="Pozisyon" required>
                  </div>
                  <div class="col-6">
                    <label for="worker_sex">
                      <h5><b>Çalışanının Cinsiyetini Giriniz</b></h5>
                    </label>
                    <select name="worker_sex" id="worker_sex" class="form-control" required>
                      <option value="" disabled selected>Çalışanın Cinsiyeti</option>
                      <option value="Erkek">Erkek</option>
                      <option value="Kadın">Kadın</option>
                    </select>
                  </div>
                </div>
                <br>
                <div class="row col-6">
                  <label for="worker_contract_date">
                    <h5><b>Çalışanın İşe Giriş Tarihi</b></h5>
                  </label>
                  <input name="worker_contract_date" id="worker_contract_date" class="form-control" type="date" required>
                </div>
            </div>
            <div class="modal-footer bg-light">
              <input type="number" name="company_id" value="<?= $company_id ?>" hidden>
              <input type="text" name="company_name" value="<?= $isim ?>" hidden>
              <button id="add_worker" name="add_worker" type="submit" style="float: right; width:150px;" class="btn btn-primary btn-lg">Ekle</button>
            </div>
            </form>
          </div>
        </div>
      </div> <!-- addWorker end-->

      <!-- Yeni etkinlik ekleme modal-->
      <div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form class="form-horizontal" method="POST" action="../../calendar/core/add-event.php">
              <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Etkinlik Ekle</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="title" class="col-sm-2 control-label">Başlık</label>
                  <div class="col-sm-10">
                    <input type="text" name="title" class="form-control" id="title" placeholder="Başlık">
                  </div>
                </div>
                <div class="form-group">
                  <label for="description" class="col-sm-4 control-label">Açıklama</label>
                  <div class="col-sm-10">
                    <input type="text" name="description" class="form-control" id="description" placeholder="Açıklama">
                  </div>
                </div>
                <div class="form-group">
                  <label for="color" class="col-sm-2 control-label">Renk</label>
                  <div class="col-sm-10">
                    <select name="color" class="form-control" id="color">
                      <option style="color:#0071c5;" value="#0071c5">Lacivert</option>
                      <option style="color:#40E0D0;" value="#40E0D0">Turkuaz</option>
                      <option style="color:#008000;" value="#008000">Yeşil</option>
                      <option style="color:#FFD700;" value="#FFD700">Sarı</option>
                      <option style="color:#FF8C00;" value="#FF8C00">Turuncu</option>
                      <option style="color:#FF0000;" value="#FF0000">Kırmızı</option>
                      <option style="color:#000;" value="#000">Siyah</option>
                    </select>
                  </div>
                </div>
                <div class="container">
                  <div class="row">
                    <div class="form-group">
                      <label for="start" class="col-sm-12 control-label">Başlangıç Tarihi</label>
                      <div class="col-sm-12">
                        <input type="text" name="start" class="form-control" id="start" readonly>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="end" class="col-sm-12 control-label">Sonlanma Tarihi</label>
                      <div class="col-sm-12">
                        <input type="text" name="end" class="form-control" id="end" readonly>
                        <input type="number" name="company_id" id="company_id" value="<?= $company_id ?>" hidden readonly>
                        <input type="number" name="user_id" id="user_id" value="<?= $id ?>" hidden readonly>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                <button type="submit" class="btn btn-primary">Kaydet</button>
              </div>
            </form>
          </div>
        </div>
      </div><!-- ModalAdd end -->

      <!-- Kayıtlı etkinliği düzenle modal -->
      <div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form class="form-horizontal" method="POST" action="../../calendar/core/editEventTitle.php">
              <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Etkinliği Düzenle</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
                <input name="event_company_name" id="event_company_name" value="<?= $isim ?>" hidden readonly>
                <div class="form-group">
                  <label for="title" class="col-sm-2 control-label">Başlık</label>
                  <div class="col-sm-10">
                    <input type="text" name="title" class="form-control" id="title" placeholder="Başlık">
                  </div>
                </div>
                <div class="form-group">
                  <label for="description" class="col-sm-4 control-label">Açıklama</label>
                  <div class="col-sm-10">
                    <input type="text" name="description" class="form-control" id="description" placeholder="Açıklama">
                  </div>
                </div>
                <div class="form-group">
                  <label for="color" class="col-sm-2 control-label">Renk</label>
                  <div class="col-sm-10">
                    <select name="color" class="form-control" id="color">
                      <option style="color:#0071c5;" value="#0071c5">Lacivert</option>
                      <option style="color:#40E0D0;" value="#40E0D0">Turkuaz</option>
                      <option style="color:#008000;" value="#008000">Yeşil</option>
                      <option style="color:#FFD700;" value="#FFD700">Sarı</option>
                      <option style="color:#FF8C00;" value="#FF8C00">Turuncu</option>
                      <option style="color:#FF0000;" value="#FF0000">Kırmızı</option>
                      <option style="color:#000;" value="#000">Siyah</option>

                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-2">
                    <label onclick="toggleCheck('check1');" class="label-off" for="check1" id="check1_label">
                      Sil
                    </label>
                    <input class="nocheckbox" type="checkbox" id="check1" name="delete">
                  </div>
                </div>
                <script>
                  function toggleCheck(check) {
                    if ($('#' + check).is(':checked')) {
                      $('#' + check + '_label').removeClass('label-on');
                      $('#' + check + '_label').addClass('label-off');
                    } else {
                      $('#' + check + '_label').addClass('label-on');
                      $('#' + check + '_label').removeClass('label-off');
                    }
                  }
                </script>
                <input type="hidden" name="id" class="form-control" id="id">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                <button type="submit" class="btn btn-primary">Kaydet</button>
              </div>
            </form>
          </div>
        </div>
      </div><!-- ModalEdit end-->

      <!-- Yeni Ziyaret Raporu Ekleme modal-->
      <div class="modal fade" id="addVisitReport" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-light">
              <h5 class="modal-title" id="exampleModalLabel"><b>Yeni Ziyaret Raporu Ekle</b></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="../core/addVisitReport.php" method="POST" enctype="multipart/form-data">
                <div class="row col-12">
                  <label for="visit_report_name">
                    <h5><b>Raporun Adını Giriniz</b></h5>
                    <a style="color:red">*Lütfen dosya isminde "_" (alt çizgi) karakteri kullanmayın!</a>
                  </label>
                  <input name="visit_report_name" id="visit_report_name" class="form-control" type="text" maxlength="20" placeholder="Rapor Adı" required>
                </div>
                <br>
                <div class="row col-12">
                  <label for="visit_report_date">
                    <h5><b>Raporun Tarihini Giriniz</b></h5>
                  </label>
                  <input name="visit_report_date" id="visit_report_date" class="form-control" type="date" required>
                </div>
                <br>
                <div class="modal-footer">
                  <input type="number" name="company_id" value="<?= $company_id ?>" hidden>
                  <input type="text" name="company_name" value="<?= $isim ?>" hidden>
                  <input type="file" class="btn btn-sm" name="ziyaret_dosyası" id="ziyaret_dosyası" style="margin-right:auto;" required>
                  <button type="submit" class="btn btn-primary" name="ziyaret_dosyası_yukle" id="ziyaret_dosyası_yukle">Yükle</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div><!-- addVisitReport end-->

      <!-- Yeni İşletme Raporu Yükleme modal-->
      <div class="modal fade" id="uploadReport" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-light">
              <h5 class="modal-title" id="exampleModalLabel"><b>İşletmeye Ait Rapor Ekle</b></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="../core/uploadReport.php" method="POST" enctype="multipart/form-data">
                <div class="row col-12">
                  <label for="upload_report_name">
                    <h5><b>Raporun Adını Giriniz</b></h5>
                    <a style="color:red">*Lütfen dosya isminde "_" (alt çizgi) karakteri kullanmayın!</a>
                  </label>
                  <input name="upload_report_name" id="upload_report_name" class="form-control" type="text" maxlength="40" placeholder="Rapor Adı" required>
                </div>
                <br>
                <div class="row col-12">
                  <label for="visit_report_date">
                    <h5><b>Raporun Tarihini Giriniz</b></h5>
                  </label>
                  <input name="upload_report_date" id="upload_report_date" class="form-control" type="date" required>
                </div>
                <br>
                <div class="modal-footer">
                  <input type="number" name="company_id" value="<?= $company_id ?>" hidden>
                  <input type="text" name="company_name" value="<?= $isim ?>" hidden>
                  <input type="file" class="btn btn-sm" name="uploadReportFile" id="uploadReportFile" style="margin-right:auto;" required>
                  <button type="submit" class="btn btn-primary" name="uploadReportBtn" id="uploadReportBtn">Yükle</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div><!-- addVisitReport end-->

      <!-- Yeni Ekipman Ekle-->
      <div class="modal fade" id="addEquipment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header bg-light">
              <h5 class="modal-title" id="exampleModalLabel"><b>Yeni Ekipman Ekle</b></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="../core/eqSave.php" method="POST">
              <div class="modal-body">
                <div class="row col-12">
                  <label for="eq_name">
                    <h5><b>Ekipmanın Adını Giriniz</b></h5>
                  </label>
                  <input name="eq_name" id="eq_name" class="form-control" type="text" maxlength="100" placeholder="Ekipman Adı" required>
                </div>
                <br>
                <div class="row col-12">
                  <label for="eq_purpose">
                    <h5><b>Ekipmanın Kullanım Amacını Giriniz</b></h5>
                  </label>
                  <input name="eq_purpose" id="eq_purpose" class="form-control" type="text" placeholder="Kullanım Amacı" required>
                </div>
                <br>
                <div class="row col-12">
                  <label for="eq_freq">
                    <h5><b>Ekipmanın Düzenli Kontrol Aralığını Giriniz</b></h5>
                  </label>
                  <select name="eq_freq" id="eq_freq" class="form-control" required>
                    <option value="" selected disabled>Kontrol Aralığı</option>
                    <option value="1">1 Ay</option>
                    <option value="2">2 Ay</option>
                    <option value="3">3 Ay</option>
                    <option value="4">4 Ay</option>
                    <option value="5">5 Ay</option>
                    <option value="6">6 Ay</option>
                    <option value="7">7 Ay</option>
                    <option value="8">8 Ay</option>
                    <option value="9">9 Ay</option>
                    <option value="10">10 Ay</option>
                    <option value="11">11 Ay</option>
                    <option value="12">12 Ay</option>
                    <option value="18">18 Ay</option>
                    <option value="24">24 Ay</option>
                    <option value="36">36 Ay</option>
                  </select>
                </div>
                <br>
              </div>
              <div class="modal-footer">
                <input type="number" name="company_id" value="<?= $company_id ?>" hidden>
                <button type="submit" class="btn btn-primary" name="eq_save" id="eq_save">Kaydet</button>
              </div>
            </form>
          </div>
        </div>
      </div><!-- addEquipment end-->

      <!-- Yeni Rapor Hazırla-->
      <div class="modal fade" id="addReport" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header bg-light">
              <h5 class="modal-title" id="exampleModalLabel"><b>Yeni Rapor Hazırla</b></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="../core/addReport.php" method="POST">
              <div class="modal-body">
                <div class="row col-12">
                  <label for="new_report">
                    <h5><b>Rapor Seç</b></h5>
                  </label>
                  <select name="new_report" id="new_report" class="form-control" required>
                    <option value="" selected disabled>Rapor Seç</option>
                    <option value="katip_sozlesme">İSG Katip Sözleşmeleri</option>
                    <option value="yangın">Yangın Tatbikat Raporu</option>
                    <option value="egitim_plan">Yıllık Eğitim Planı</option>
                    <option value="calisma_plan">Yıllık Çalışma Planı(50 üstü)</option>
                    <option value="takip_liste">Klasör Takip Listesi</option>
                    <option value="tabela_liste">Tabela Listeleri</option>
                    <optgroup label="Risk Analizi">
                      <option value="ekip_atamasi">Ekip Ataması</option>
                      <option value="risk_basla">Risk Değerlendirme Başlanması</option>
                      <option value="ust_yonetim">Üst Yönetime Sunum</option>
                      <option value="risk_tablo">Risk Analiz Tablosu</option>
                      <option value="covid19">Covid 19 Risk Değerlendirme Raporu</option>
                      <option value="sonuc">Sonuç Raporu</option>
                    </optgroup>
                    <optgroup label="Acil Durum Planı">
                      <option value="acil_plan_kapak">Acil Durum Eylem Planı Kapak</option>
                      <option value="acil_plan">Acil Durum Eylem Planı</option>
                      <option value="acil_plan_sema">Acil Durum Şemları</option>
                    </optgroup>
                    <optgroup label="Sağlık Güvenlik Planı">
                      <option value="sgp_kapak">SGP Kapak</option>
                      <option value="sg_plan">Sağlık Güvenlik Planı</option>
                      <option value="hazırlık">Hazırlık Koordinatörü</option>
                      <option value="uygulama">Uygulama Koordinatörü</option>
                    </optgroup>
                    <option value="acil_ekip">Acil Durum Müdahale Ekipleri</option>
                  </select>
                </div>
                <br>
              </div>
              <div class="modal-footer">
                <input type="number" name="company_id" id="company_id" value="<?= $company_id ?>" hidden>
                <input type="text" name="company_name" value="<?= $isim ?>" hidden>
                <button type="submit" name="addReport_sub" class="btn btn-primary">Hazırla</button>
              </div>
            </form>
          </div>
        </div>
      </div><!-- addReport end-->

      <!-- İşletme bilgilerini değiştir-->
      <div class="modal fade" id="changeCompany" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="../core/saveChanges.php" method="POST" >
          <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
              <div class="modal-c-tabs">
                <ul class="nav nav-tabs justify-content-center bg-light" style="padding-top: 10px" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active text-dark" id="link1-tab" data-toggle="tab" href="#link1" role="tab" aria-selected="true" aria-controls="link1">
                      <h5><b>İşletme Genel Bilgileri</b></h5>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link text-dark" id="link2-tab" data-toggle="tab" role="tab" href="#link2" aria-selected="false" aria-controls="link2">
                      <h5><b>Çalışan Ata</b></h5>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link text-dark" id="link3-tab" data-toggle="tab" role="tab" href="#link3" aria-selected="false" aria-controls="link3">
                      <h5><b>Devlet Bilgileri</b></h5>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" type="nav-link" class="nav-link" data-dismiss="modal" aria-label="Close" style="color:red;">
                      <h5><b>Kapat</b></h5>
                    </a>
                  </li>

                </ul>

                <div class="tab-content">

                  <div class="tab-pane fade in show active" id="link1" role="tabpanel" aria-labelledby="link1-tab">
                    <div class="modal-body">
                      <div class="row">
                        <div class="form-group col-lg-4">
                          <label for="comp_type">
                            <h5><b>Sektör</b></h5>
                          </label>
                          <select class="form-control" id="comp_type" name="comp_type" required>
                            <option value="<?= $company->comp_type ?>" selected><?= $company->comp_type ?></option>
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
                          <label for="name">
                            <h5><strong>İşletme Adı</strong></h5>
                          </label>
                          <input class="form-control" type="text" placeholder="Adı" name="name" id="name" maxlength="250" readonly required value="<?= $company->name?>">
                        </div>
                        <div class="col-sm-6">
                          <label for="mail">
                            <h5><strong>İşletme Mail Adresi</strong></h5>
                          </label>
                          <input class="form-control" type="email" placeholder="E-mail" name="mail" id="mail" maxlength="125" required value="<?= $company->mail?>">
                        </div>
                      </div>
                      <br>
                      <div class="row">
                        <div class="col-sm-6">
                          <label for="phone">
                            <h5><strong>İşletme Telefon No</strong></h5>
                          </label>
                          <input class="form-control" type="tel" name="phone" id="phone" placeholder="Tel: 0XXXXXXXXXX" pattern="(\d{4})(\d{3})(\d{2})(\d{2})" maxlength="11" required value="<?= $company->phone?>">
                        </div>
                        <div class="col-sm-6">
                          <label for="is_veren">
                            <h5><strong>İşveren Ad Soyad</strong></h5>
                          </label>
                          <input class="form-control" type="tel" name="is_veren" id="is_veren" maxlength="50" required value="<?= $company->is_veren?>">
                        </div>
                      </div>
                      <br>
                      <div class="row">
                        <div class="col-sm-4">
                          <label for="countrySelect">
                            <h5><strong>Bulunduğu Şehir<a style="color:red">*</a></strong></h5>
                          </label>
                          <select class="form-control" id="countrySelect" name="countrySelect" size="1" onchange="makeSubmenu(this.value)" required>
                            <option value="<?= $company->city ?>" selected><?= $company->city ?></option>
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
                          <label for="citySelect">
                            <h5><strong>Bulunduğu İlçe<a style="color:red">*</a></strong></h5>
                          </label>
                          <select class="form-control" id="citySelect" name="citySelect" size="1" required>
                            <option value="<?= $company->town ?>" selected><?= $company->town ?></option>
                            <option></option>
                          </select>
                        </div>
                        <div class="col-sm-4">
                          <label for="contract_date">
                            <h5><strong>İşletme Anlaşma Tarihi<a style="color:red">*</a></strong></h5>
                          </label>
                          <input class="form-control" type="date" placeholder="Anlaşma Tarihi" name="contract_date" id="contract_date" required value="<?= $company->contract_date ?>">
                        </div>
                      </div>
                      <br>
                      <div class="row">
                        <div class="col-sm-12">
                          <label for="address">
                            <h5><b>Adres<a style="color:red">*</a></b></h5>
                          </label>
                          <textarea class="form-control" id="address" name="address" rows="3" style="max-width: 100%;" maxlength="2500" required><?= $company->address?></textarea>
                        </div>
                      </div>
                      <br>
                      <div class="row">
                        <div class="form-group col-lg-4">
                          <label for="remi_freq">
                            <h5><b>Ziyaret Sıklığı</b></h5>
                          </label>
                          <select class="form-control" id="remi_freq" name="remi_freq" size="1" required>
                            <option value="" disabled>Ziyaret Sıklığı Ayarla</option>
                            <?php
                                          if ($company->remi_freq != 0) {
                                          ?>
                            <option value=<?=$company->remi_freq ?> selected><?=$company->remi_freq ?> Ay</option>
                            <?php
                                            } ?>
                            <option value=1>1 Ay</option>
                            <option value=2>2 Ay</option>
                            <option value=3>3 Ay</option>
                            <option value=4>4 Ay</option>
                            <option value=5>5 Ay</option>
                            <option value=6>6 Ay</option>
                            <option value=7>7 Ay</option>
                            <option value=8>8 Ay</option>
                            <option value=9>9 Ay</option>
                            <option value=10>10 Ay</option>
                            <option value=11>11 Ay</option>
                            <option value=12>12 Ay</option>
                            <option value=18>18 Ay</option>
                            <option value=24>24 Ay</option>
                            <option value=36>36 Ay</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane fade" id="link2" role="tabpanel" aria-labelledby="link2-tab">
                    <div class="modal-body">
                      <div class="row">
                        <!--1. İsg Uzmanı -->
                        <div class="col-lg-4">
                          <label for="uzman">
                            <h5><b>1. İsg Uzmanı</b></h5>
                          </label>
                          <?php
                                        $user_id = $company->uzman_id;
                                        $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                                        $sor->execute();
                                        $uzmans=$sor->fetchAll(PDO::FETCH_OBJ);
                                        foreach ($uzmans as $uzman) {
                                            $first_ = $uzman->firstname;
                                            $last_ = $uzman->lastname;
                                        } ?>
                          <select class="form-control" id="uzman" name="uzman" size="1">
                            <?php
                                        if ($company->uzman_id != 0) {
                                            ?>
                            <option value="<?= $company->uzman_id ?>" selected><?= $first_." ".$last_ ?></option>
                            <?php
                                        } else {
                                            ?>
                            <option disabled selected>İsg Uzmanı Seç</option>
                            <?php
                                        }
                                          $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 0");
                                          $sor->execute();
                                          $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                                          foreach ($workers as $worker) {
                                              $mail_ = $worker->username;
                                              $sor2=$pdo->prepare("SELECT * FROM `osgb_workers` WHERE `mail` = '$mail_'");
                                              $sor2->execute();
                                              $workers2=$sor2->fetchAll(PDO::FETCH_OBJ);
                                              foreach ($workers2 as $worker2) {
                                                  $type = $worker2->user_type;
                                              } ?>
                            <option value="<?= $worker->id ?>"><?= $type ?> <?= $worker->firstname ?> <?= $worker->lastname ?></option>
                            <?php
                                            } ?>
                          </select>
                        </div>

                        <!--2. İsg Uzmanı -->
                        <div class="col-lg-4">
                          <label for="uzman_2">
                            <h5><b>2.İsg Uzmanı</b></h5>
                          </label>
                          <?php
                                        $user_id = $company->uzman_id_2;
                                        $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                                        $sor->execute();
                                        $uzmans=$sor->fetchAll(PDO::FETCH_OBJ);
                                        foreach ($uzmans as $uzman) {
                                            $first_ = $uzman->firstname;
                                            $last_ = $uzman->lastname;
                                        } ?>
                          <select class="form-control" id="uzman_2" name="uzman_2" size="1">
                            <?php
                                        if ($company->uzman_id_2 != 0) {
                                            ?>
                            <option value="<?= $company->uzman_id_2 ?>" selected><?= $first_." ".$last_ ?></option>
                            <?php
                                        } else {
                                            ?>
                            <option disabled selected>İsg Uzmanı Seç</option>
                            <?php
                                        }
                                          $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 0");
                                          $sor->execute();
                                          $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                                          foreach ($workers as $worker) {
                                              $mail_ = $worker->username;
                                              $sor2=$pdo->prepare("SELECT * FROM `osgb_workers` WHERE `mail` = '$mail_'");
                                              $sor2->execute();
                                              $workers2=$sor2->fetchAll(PDO::FETCH_OBJ);
                                              foreach ($workers2 as $worker2) {
                                                  $type = $worker2->user_type;
                                              } ?>
                            <option value="<?= $worker->id ?>"><?= $type ?> <?= $worker->firstname ?> <?= $worker->lastname ?></option>
                            <?php
                                            } ?>
                          </select>
                        </div>

                        <!--3. İsg Uzmanı -->
                        <div class="col-lg-4">
                          <label for="uzman_3">
                            <h5><b>3.İsg Uzmanı</b></h5>
                          </label>
                          <?php
                                        $user_id = $company->uzman_id_3;
                                        $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                                        $sor->execute();
                                        $uzmans=$sor->fetchAll(PDO::FETCH_OBJ);
                                        foreach ($uzmans as $uzman) {
                                            $first_ = $uzman->firstname;
                                            $last_ = $uzman->lastname;
                                        } ?>
                          <select class="form-control" id="uzman_3" name="uzman_3" size="1">
                            <?php
                                        if ($company->uzman_id_3 != 0) {
                                            ?>
                            <option value="<?= $company->uzman_id_3 ?>" selected><?= $first_." ".$last_ ?></option>
                            <?php
                                        } else {
                                            ?>
                            <option disabled selected>İsg Uzmanı Seç</option>
                            <?php
                                        }
                                          $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 0");
                                          $sor->execute();
                                          $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                                          foreach ($workers as $worker) {
                                              $mail_ = $worker->username;
                                              $sor2=$pdo->prepare("SELECT * FROM `osgb_workers` WHERE `mail` = '$mail_'");
                                              $sor2->execute();
                                              $workers2=$sor2->fetchAll(PDO::FETCH_OBJ);
                                              foreach ($workers2 as $worker2) {
                                                  $type = $worker2->user_type;
                                              } ?>
                            <option value="<?= $worker->id ?>"><?= $type ?> <?= $worker->firstname ?> <?= $worker->lastname ?></option>
                            <?php
                                            } ?>
                          </select>
                        </div>
                      </div>
                      <br>
                      <hr style="border-top: 1px dashed red;">
                      <br>

                      <!--İş Yeri Hekimleri-->
                      <div class="row">

                        <!--1 İş Yeri Hekimi -->
                        <div class="col-lg-4">
                          <label for="hekim">
                            <h5><b>1. İş Yeri Hekimi</b></h5>
                          </label>
                          <?php
                                      $user_id = $company->hekim_id;
                                          $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                                          $sor->execute();
                                          $hekimler=$sor->fetchAll(PDO::FETCH_OBJ);
                                          foreach ($hekimler as $hekim) {
                                              $first_ = $hekim->firstname;
                                              $last_ = $hekim->lastname;
                                          } ?>
                          <select class="form-control" id="hekim" name="hekim" size="1">
                            <?php
                                        if ($company->hekim_id != 0) {
                                            ?>
                            <option value="<?= $company->hekim_id ?>" selected><?= $first_." ".$last_ ?></option>
                            <?php
                                        } else {
                                            ?>
                            <option disabled selected>İş Yeri Hekimi Seç</option>
                            <?php
                                          }
                                            $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 2");
                                            $sor->execute();
                                            $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                                            foreach ($workers as $worker) {
                                                ?>
                            <option value=<?= $worker->id ?>><?= $worker->firstname ?> <?= $worker->lastname ?></option>
                            <?php
                                            } ?>
                          </select>
                        </div>

                        <!--2. İş Yeri Hekimi -->
                        <div class="col-lg-4">
                          <label for="hekim_2">
                            <h5><b>2. İş Yeri Hekimi</b></h5>
                          </label>
                          <?php
                                      $user_id = $company->hekim_id_2;
                                          $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                                          $sor->execute();
                                          $hekimler=$sor->fetchAll(PDO::FETCH_OBJ);
                                          foreach ($hekimler as $hekim) {
                                              $first_ = $hekim->firstname;
                                              $last_ = $hekim->lastname;
                                          } ?>
                          <select class="form-control" id="hekim_2" name="hekim_2" size="1">
                            <?php
                                        if ($company->hekim_id_2 != 0) {
                                            ?>
                            <option value="<?= $company->hekim_id_2 ?>" selected><?= $first_." ".$last_ ?></option>
                            <?php
                                        } else {
                                            ?>
                            <option disabled selected>İş Yeri Hekimi Seç</option>
                            <?php
                                          }
                                            $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 2");
                                            $sor->execute();
                                            $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                                            foreach ($workers as $worker) {
                                                ?>
                            <option value=<?= $worker->id ?>><?= $worker->firstname ?> <?= $worker->lastname ?></option>
                            <?php
                                            } ?>
                          </select>
                        </div>

                        <!--3. İş Yeri Hekimi -->
                        <div class="col-lg-4">
                          <label for="hekim_3">
                            <h5><b>3. İş Yeri Hekimi</b></h5>
                          </label>
                          <?php
                                      $user_id = $company->hekim_id_3;
                                          $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                                          $sor->execute();
                                          $hekimler=$sor->fetchAll(PDO::FETCH_OBJ);
                                          foreach ($hekimler as $hekim) {
                                              $first_ = $hekim->firstname;
                                              $last_ = $hekim->lastname;
                                          } ?>
                          <select class="form-control" id="hekim_3" name="hekim_3" size="1">
                            <?php
                                        if ($company->hekim_id_3 != 0) {
                                            ?>
                            <option value="<?= $company->hekim_id_3 ?>" selected><?= $first_." ".$last_ ?></option>
                            <?php
                                        } else {
                                            ?>
                            <option disabled selected>İş Yeri Hekimi Seç</option>
                            <?php
                                          }
                                            $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 2");
                                            $sor->execute();
                                            $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                                            foreach ($workers as $worker) {
                                                ?>
                            <option value=<?= $worker->id ?>><?= $worker->firstname ?> <?= $worker->lastname ?></option>
                            <?php
                                            } ?>
                          </select>
                        </div>
                      </div>
                      <br>
                      <hr style="border-top: 1px dashed red;">
                      <br>

                      <!--1. Personeller -->
                      <div class="row">
                        <div class="col-lg-4">
                          <label for="saglık">
                            <h5><b>Sağlık Personeli</b></h5>
                          </label>
                          <?php
                                      $user_id = $company->saglık_p_id;
                                      $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                                      $sor->execute();
                                      $sağlıkçılar=$sor->fetchAll(PDO::FETCH_OBJ);
                                      foreach ($sağlıkçılar as $sağlıkçı) {
                                          $first_ = $sağlıkçı->firstname;
                                          $last_ = $sağlıkçı->lastname;
                                      } ?>
                          <select class="form-control" id="saglık" name="saglık" size="1">
                            <?php
                                        if ($company->saglık_p_id != 0) {
                                            ?>
                            <option value="<?= $company->saglık_p_id ?>" selected><?= $first_." ".$last_ ?></option>
                            <?php
                                        } else {
                                            ?>
                            <option disabled selected>İş Yeri Hekimi Seç</option>
                            <?php
                                        }
                                        $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 3");
                                        $sor->execute();
                                        $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                                        foreach ($workers as $worker) {
                                            ?>
                            <option value=<?= $worker->id ?>><?= $worker->firstname ?> <?= $worker->lastname ?></option>
                            <?php
                                        } ?>
                          </select>
                        </div>

                        <div class="col-lg-4">
                          <label for="ofis">
                            <h5><b>Ofis Personeli</b></h5>
                          </label>
                          <?php
                                      $user_id = $company->ofis_p_id;
                                      $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                                      $sor->execute();
                                      $ofisler=$sor->fetchAll(PDO::FETCH_OBJ);
                                      foreach ($ofisler as $ofis) {
                                          $first_ = $ofis->firstname;
                                          $last_ = $ofis->lastname;
                                      } ?>
                          <select class="form-control" id="ofis" name="ofis" size="1">
                            <?php
                                        if ($company->ofis_p_id != 0) {
                                            ?>
                            <option value="<?= $company->ofis_p_id ?>" selected><?= $first_." ".$last_ ?></option>
                            <?php
                                        } else {
                                            ?>
                            <option disabled selected>Ofis Personeli Seç</option>
                            <?php
                                        }
                                      $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 4");
                                      $sor->execute();
                                      $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                                      foreach ($workers as $worker) {
                                          ?>
                            <option value=<?= $worker->id ?>><?= $worker->firstname ?> <?= $worker->lastname ?></option>
                            <?php
                                        } ?>
                          </select>
                        </div>

                        <div class="col-lg-4">
                          <label for="muhasebe">
                            <h5><b>Muhasebe Personeli</b></h5>
                          </label>
                          <?php
                                      $user_id = $company->muhasebe_p_id;
                                      $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                                      $sor->execute();
                                      $muhasebeler=$sor->fetchAll(PDO::FETCH_OBJ);
                                      foreach ($muhasebeler as $muhasebe) {
                                          $first_ = $muhasebe->firstname;
                                          $last_ = $muhasebe->lastname;
                                      } ?>
                          <select class="form-control" id="muhasebe" name="muhasebe" size="1">
                            <?php
                                        if ($company->muhasebe_p_id != 0) {
                                            ?>
                            <option value="<?= $company->muhasebe_p_id ?>" selected><?= $first_." ".$last_ ?></option>
                            <?php
                                        } else {
                                            ?>
                            <option disabled selected>Muhasebe Personeli Seç</option>
                            <?php
                                                    }
                                          $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 6");
                                          $sor->execute();
                                          $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                                          foreach ($workers as $worker) {
                                              ?>
                            <option value=<?= $worker->id ?>><?= $worker->firstname ?> <?= $worker->lastname ?></option>
                            <?php
                                          } ?>
                          </select>
                        </div>
                      </div>
                      <br>
                      <br>

                      <!--2. Personeller -->
                      <div class="row">
                        <!--2. Sağlık Personeli -->
                        <div class="col-lg-4">
                          <label for="saglık_2">
                            <h5><b>2.Sağlık Personeli</b></h5>
                          </label>
                          <?php
                                      $user_id = $company->saglık_p_id_2;
                                      $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                                      $sor->execute();
                                      $sağlıkçılar=$sor->fetchAll(PDO::FETCH_OBJ);
                                      foreach ($sağlıkçılar as $sağlıkçı) {
                                          $first_ = $sağlıkçı->firstname;
                                          $last_ = $sağlıkçı->lastname;
                                      } ?>
                          <select class="form-control" id="saglık_2" name="saglık_2" size="1">
                            <?php
                                        if ($company->saglık_p_id_2 != 0) {
                                            ?>
                            <option value="<?= $company->saglık_p_id_2 ?>" selected><?= $first_." ".$last_ ?></option>
                            <?php
                                        } else {
                                            ?>
                            <option disabled selected>İş Yeri Hekimi Seç</option>
                            <?php
                                        }
                                        $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 3");
                                        $sor->execute();
                                        $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                                        foreach ($workers as $worker) {
                                            ?>
                            <option value=<?= $worker->id ?>><?= $worker->firstname ?> <?= $worker->lastname ?></option>
                            <?php
                                        } ?>
                          </select>
                        </div>

                        <!--2. Ofis Personeli -->
                        <div class="col-lg-4">
                          <label for="ofis_2">
                            <h5><b>2.Ofis Personeli</b></h5>
                          </label>
                          <?php
                                      $user_id = $company->ofis_p_id_2;
                                      $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                                      $sor->execute();
                                      $ofisler=$sor->fetchAll(PDO::FETCH_OBJ);
                                      foreach ($ofisler as $ofis) {
                                          $first_ = $ofis->firstname;
                                          $last_ = $ofis->lastname;
                                      } ?>
                          <select class="form-control" id="ofis_2" name="ofis_2" size="1">
                            <?php
                                        if ($company->ofis_p_id_2 != 0) {
                                            ?>
                            <option value="<?= $company->ofis_p_id_2 ?>" selected><?= $first_." ".$last_ ?></option>
                            <?php
                                        } else {
                                            ?>
                            <option disabled selected>Ofis Personeli Seç</option>
                            <?php
                                        }
                                      $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 4");
                                      $sor->execute();
                                      $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                                      foreach ($workers as $worker) {
                                          ?>
                            <option value=<?= $worker->id ?>><?= $worker->firstname ?> <?= $worker->lastname ?></option>
                            <?php
                                        } ?>
                          </select>
                        </div>

                        <!--2. Muhasebe Personeli -->
                        <div class="col-lg-4">
                          <label for="muhasebe_2">
                            <h5><b>2.Muhasebe Personeli</b></h5>
                          </label>
                          <?php
                                      $user_id = $company->muhasebe_p_id_2;
                                      $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                                      $sor->execute();
                                      $muhasebeler=$sor->fetchAll(PDO::FETCH_OBJ);
                                      foreach ($muhasebeler as $muhasebe) {
                                          $first_ = $muhasebe->firstname;
                                          $last_ = $muhasebe->lastname;
                                      } ?>
                          <select class="form-control" id="muhasebe_2" name="muhasebe_2" size="1">
                            <?php
                                        if ($company->muhasebe_p_id_2 != 0) {
                                            ?>
                            <option value="<?= $company->muhasebe_p_id_2 ?>" selected><?= $first_." ".$last_ ?></option>
                            <?php
                                        } else {
                                            ?>
                            <option disabled selected>Muhasebe Personeli Seç</option>
                            <?php
                                                    }
                                          $sor=$pdo->prepare("SELECT * FROM `users` WHERE `auth` = 6");
                                          $sor->execute();
                                          $workers=$sor->fetchAll(PDO::FETCH_OBJ);
                                          foreach ($workers as $worker) {
                                              ?>
                            <option value=<?= $worker->id ?>><?= $worker->firstname ?> <?= $worker->lastname ?></option>
                            <?php
                                          } ?>
                          </select>
                        </div>

                      </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                  </div>

                  <div class="tab-pane fade" id="link3" role="tabpanel" aria-labelledby="home-tab">
                    <div class="modal-body">
                      <div class="row col-3">
                        <label for="nace_kodu">
                          <h4><b>Kurum NACE Kodu</b></h4>
                        </label>
                        <input class="form-control" type="text" name="nace_kodu" required value="<?= $company->nace_kodu ?>">
                      </div>
                      <br>
                      <div class="row">
                        <div class="col-6">
                          <label for="mersis_no">
                            <h4><b>Kurum Mersis No Giriniz</b></h4>
                          </label>
                          <input class="form-control" id="mersis_no" name="mersis_no" type="tel" min="16" maxlength="16" required value="<?= $company->mersis_no ?>">
                        </div>
                        <div class="col-6">
                          <label for="sgk_sicil">
                            <h4><b>SGK Sicil No Giriniz</b></h4>
                          </label>
                          <input class="form-control" id="sgk_sicil" name="sgk_sicil" type="tel" min="12" maxlength="12" required value="<?= $company->sgk_sicil?>">
                        </div>
                      </div>
                      <br>
                      <div class="row">
                        <div class="col-6">
                          <label for="vergi_no">
                            <h4><b>Vergi No Giriniz</b></h4>
                          </label>
                          <input class="form-control" id="vergi_no" name="vergi_no" type="tel" min="10" maxlength="10" required value="<?= $company->vergi_no?>">
                        </div>
                        <div class="col-6">
                          <label for="vergi_dairesi">
                            <h4><b>Vergi Dairesi Giriniz</b></h4>
                          </label>
                          <input class="form-control" id="vergi_dairesi" name="vergi_dairesi" type="text" required value="<?= $company->vergi_dairesi?>">
                        </div>
                      </div>
                      <br>
                      <div class="row">
                        <div class="col-6">
                          <label for="katip_is_yeri_id">
                            <h4><b>İSG-KATİP İş Yeri ID</b></h4>
                          </label>
                          <input class="form-control" id="katip_is_yeri_id" name="katip_is_yeri_id" type="tel" maxlength="30" required value="<?= $company->katip_is_yeri_id?>">
                        </div>
                        <div class="col-6">
                          <label for="katip_kurum_id">
                            <h4><b>İSG-KATİP Kurum ID</b></h4>
                          </label>
                          <input class="form-control" id="katip_kurum_id" name="katip_kurum_id" type="tel" maxlength="30" required value="<?= $company->katip_kurum_id?>">
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <input type="text" name="changer" value="<?= $un ?>" hidden>
                      <input type="number" name="company_id" value="<?= $company_id ?>" hidden>
                      <input type="text" name="company_name" value="<?= $isim ?>" hidden>
                      <button class="btn btn-danger btn-lg" name="sil" onClick='return confirmSubmit()' style="margin-right: auto;">İşletmeyi Sil</button>
                      <button  class="btn btn-primary btn-lg" type='submit' name='kaydet' onClick='return confirmSubmit()'>Kaydet</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>

      <!-- Yeni Kullanıcı Oluştur-->
      <div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header bg-light">
              <h5 class="modal-title" id="exampleModalLabel"><b>Yeni Kullanıcı Oluştur</b></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="../core/addCompanyUser.php" method="POST">
                <div class="row">
                  <div class="col-sm">
                    <label for="firstname"><strong>Adı</strong></label>
                    <input type="text" class="form-control" placeholder="Adı" name="cu_firstname" required>
                  </div>
                  <div class="col-sm">
                    <label for="lastname"><strong>Soy Adı </strong></label>
                    <input type="text" class="form-control" placeholder="Soy Adı" name="cu_lastname" required>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-sm-10">
                    <label for="email"><strong>E-mail </strong></label>
                    <input type="email" class="form-control" placeholder="E-mail" name="cu_username" required>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-sm-6">
                    <label for="phone"><strong>Telefon No </strong></label>
                    <input type="tel" class="form-control" name="cu_phone" placeholder="Tel: 05XXXXXXXXX" pattern="(\d{4})(\d{3})(\d{2})(\d{2})" maxlength="11" required>
                  </div>
                </div>
                <br>
                <div style="float: right;">
                  <input type="number" name="company_id" value="<?= $company_id ?>" hidden>
                  <input type="text" name="company_name" value="<?= $isim ?>" hidden>
                  <button id="kayıt" name="cu_kayıt" type="submit" class="btn btn-success">Kaydet</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div><!-- addUser end-->
    </div>
    <!--card end-->
  </div>
  <!--container end-->

  <footer class="bg-white sticky-footer">
    <div class="container my-auto">
      <div class="text-center my-auto copyright"><span>Copyright © ÖzgürOSGB 2020</span></div>
    </div>
  </footer>

  <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
  <script LANGUAGE="JavaScript">
    <!--
    function confirmSubmit()
    {
    var agree=confirm("Lütfen yaptığınız değişiklikler onaylanana kadar başka bir değişiklik yapmayınız!\nDeğişiklikler onaylanmadan yaptığınız yeni değişiklikler kaydedilmeyecektir!\nDeğişiklikleri işletme yöneticinize göndermek istediğinize emin misiniz?");
    if (agree)
     return true ;
    else
     return false ;
    }
    // -->
  </script>
  <script src="../../assets/js/jquery.min.js"></script>
  <script src="../../assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="../../assets/js/chart.min.js"></script>
  <script src="../../assets/js/bs-init.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
  <script src="../../assets/js/theme.js"></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js'></script>
  <script src='../../calendar/js/moment.min.js'></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js" integrity="sha256-wS9gmOZBqsqWxgIVgA8Y9WcQOa7PgSIX+rPA0VL2rbQ=" crossorigin="anonymous"></script>
  <script src='../../calendar/js/fullcalendar.min.js'></script>
  <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js'></script>
  <?php
    if ($giriş_auth != 0 && $giriş_auth != 1 && $giriş_auth != 2 && $giriş_auth != 3 && $giriş_auth != 4) {
   ?>
    <script>
      $('#ic_form1').prop('disabled', true);
      $('#ic_form2').prop('disabled', true);
      $('#ir_form').prop('disabled', true);
      $('#ir_form2').prop('disabled', true);
      $('#addUserBtn').prop('disabled', true);
      $('#changeCompanyBtn').prop('disabled', true);
      $('#gb_form').prop('disabled', true);
      $('#oc_form').prop('disabled', true);
      $('#db_form').prop('disabled', true);
      $('#zr_form').prop('disabled', true);
      $('#ie_button').prop('disabled', true);
      $('#sc-tab').prop('hidden', true);
    </script>
    <?php
      }
     ?>
  <script>
          $(function() {
          $('#calendar').fullCalendar({
           monthNames: ['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'],
           monthNamesShort: ['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'],
           dayNames: ['Pazar','Pazartesi','Salı','Çarşamba','Perşembe','Cuma','Cumartesi'],
           dayNamesShort: ['Pazar','Pazartesi','Salı','Çarşamba','Perşembe','Cuma','Cumartesi'],
           editable:true,
           buttonText: {
                 today:    'Bugün',
                 month:    'Ay',
                 week:     'Hafta',
                 day:      'Gün',
                 list:     'Liste',
                 listMonth: 'Aylık Liste',
                 listYear: 'Yıllık Liste',
                 listWeek: 'Haftalık Liste',
                 listDay: 'Günlük Liste'
          },
           header: {
             left: 'prev,next,today',
             center: 'title',
             right: 'month,agendaWeek,agendaDay,listWeek'
           },
           height: 590,
           businessHours: {
             dow: [ 1, 2, 3, 4, 5 ],

             start: '8:00',
             end: '17:00',
           },
           nowIndicator: true,
           scrollTime: '08:00:00',
           editable: true,
           navLinks: true,
           eventLimit: true, // allow "more" link when there are too many events
           selectable: true,
           selectHelper: true,
           select: function(start, end) {

             $('#ModalAdd #start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
             $('#ModalAdd #end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
             $('#ModalAdd').modal('show');
           },
           eventAfterRender: function(eventObj, $el) {
             var request = new XMLHttpRequest();
             request.open('GET', '', true);
             request.onload = function () {
               $el.popover({
                 title: eventObj.title,
                 content: eventObj.description,
                 trigger: 'hover',
                 placement: 'top',
                 container: 'body'
               });
             }
           request.send();
           },

           eventRender: function(event, element) {
             element.bind('click', function() {
               $('#ModalEdit #id').val(event.id);
               $('#ModalEdit #title').val(event.title);
               $('#ModalEdit #description').val(event.description);
               $('#ModalEdit #color').val(event.color);
               $('#ModalEdit').modal('show');
             });
           },
           eventDrop: function(event, delta, revertFunc) { // si changement de position

             edit(event);

           },
           eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur

             edit(event);

           },
           events: [
           <?php foreach ($events as $event):

             $start = explode(" ", $event['start']);
             $end = explode(" ", $event['end']);
             if ($start[1] == '00:00:00') {
                 $start = $start[0];
             } else {
                 $start = $event['start'];
             }
             if ($end[1] == '00:00:00') {
                 $end = $end[0];
             } else {
                 $end = $event['end'];
             }
           ?>
             {
               id: '<?php echo $event['id']; ?>',
               title: '<?php echo $event['title']; ?>',
               description: '<?php echo $event['description']; ?>',
               start: '<?php echo $start; ?>',
               end: '<?php echo $end; ?>',
               color: '<?php echo $event['color']; ?>',
             },
           <?php endforeach; ?>
           ]
          });

          function edit(event){
           start = event.start.format('YYYY-MM-DD HH:mm:ss');
           if(event.end){
             end = event.end.format('YYYY-MM-DD HH:mm:ss');
           }else{
             end = start;
           }

           id =  event.id;

           Event = [];
           Event[0] = id;
           Event[1] = start;
           Event[2] = end;

           $.ajax({
            url: '../../calendar/core/edit-date.php',
            type: "POST",
            data: {Event:Event},
            success: function(rep) {
               if(rep == 'OK'){
                 alert('Kaydedildi');
               }else{
                 alert('Kaydedilemedi. Tekrar Deneyin!');
               }
             }
           });
          }
        });
  </script>
</body>

</html>
