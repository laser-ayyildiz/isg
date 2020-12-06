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
    $auth = $giriş->auth;
}
if ($auth != 1) {
    header('Location: 403.php');
}
if (isset($_POST["tamamen_sil"])) {
    $tc = !empty($_POST['tc']) ? trim($_POST['tc']) : null;

    $sor=$pdo->prepare("SELECT * FROM `osgb_workers` WHERE `tc` = '$tc'");
    $sor->execute();
    $workers=$sor-> fetchAll(PDO::FETCH_OBJ);
    foreach ($workers as $worker) {
        $id = $worker->id;
    }
    $sql = "DELETE FROM `osgb_workers` WHERE `osgb_workers`.`id` = '$id'";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute();
    if ($result) {
        ?>
<div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
  <strong>Çalışan tamamen silinmiştir!</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<?php
    }
}
if (isset($_POST["tekrar"])) {
    $tc = !empty($_POST['tc']) ? trim($_POST['tc']) : null;

    $sor=$pdo->prepare("SELECT * FROM `osgb_workers` WHERE `tc` = '$tc'");
    $sor->execute();
    $workers=$sor-> fetchAll(PDO::FETCH_OBJ);
    foreach ($workers as $worker) {
        $id = $worker->id;
    }
    $sql = "UPDATE `osgb_workers` SET `deleted` = '0' WHERE `osgb_workers`.`id` = '$id'";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute();
    if ($result) {
        ?>
<div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
  <strong>Çalışan tekrar aktifleştirildi!</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<?php
    }
}

if (isset($_POST["kaydet"])) {
    $not = !empty($_POST['not']) ? trim($_POST['not']) : null;
    $tc = !empty($_POST['tc']) ? trim($_POST['tc']) : null;

    $sor=$pdo->prepare("SELECT * FROM `osgb_workers` WHERE `tc` = '$tc'");
    $sor->execute();
    $workers=$sor-> fetchAll(PDO::FETCH_OBJ);
    foreach ($workers as $worker) {
        $id = $worker->id;
    }
    $sql = "UPDATE `osgb_workers` SET `worker_text` = '$not' WHERE `osgb_workers`.`id` = '$id'";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute();
    if ($result) {
        ?>
<div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
  <strong>Değişiklikler onaylandı!</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
    <span aria-hidden="true">&times;</span>
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
  <title>Arşiv</title>
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
  <div>
    <nav class="navbar shadow navbar-expand mb-3 bg-warning topbar static-top">
      <img width="55" height="40" class="rounded-circle img-profile" src="assets/img/nav_brand.jpg" />
      <a class="navbar-brand" title="Anasayfa" style="color: black;" href="index.php"><b>Özgür OSGB</b></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span></button>

      <ul class="navbar-nav mr-auto">
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
          <h3 class="text-dark mb-4">Arşiv</h3>
          <div class="card shadow">
            <div class="card-body">
              <div class="form-group col-md-4">
                <input type="text" class="form-control" id="myInput" onkeyup="myFunction()" placeholder="Çalışan ismi ile ara...">
              </div>
              <div id="dataTable_filter">
              </div>
              <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                <table class="table table-striped table-bordered" id="dataTable">
                  <thead class="thead-dark">
                    <tr>
                      <th>Ad</th>
                      <th>Soyad</th>
                      <th>Çalışma Alanı</th>
                      <th>T.C Kimlik No</th>
                      <th>İşe Giriş Tarihi</th>
                      <th>Bilgiler</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                              $sorgu=$pdo->prepare("SELECT * FROM `osgb_workers` WHERE `deleted` = 1");
                              $sorgu->execute();
                              $workers=$sorgu-> fetchAll(PDO::FETCH_OBJ);
                              foreach ($workers as $key=>$worker) {?>
                    <tr>
                      <td><?= $worker->firstname ?></td>
                      <td><?= $worker->lastname ?></td>
                      <td><?= $worker->user_type ?></td>
                      <td><?= $worker->tc ?></td>
                      <td><?= $worker->start_date ?></td>
                      <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#a<?php echo $key; ?>" data-whatever="@getbootstrap">Bilgiler</button></td>
                    </tr>
                    <div class="modal fade" id="a<?php echo $key; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Bilgiler</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <form action="deleted_workers.php" method="POST">
                              <div class="col-sm-6">
                                <label><strong>Kullanıcı türü</strong>
                                  <select class="form-control" name="user_type" readonly id="user_type">
                                    <option value="<?= $worker->user_type ?>" disabled selected><?= $worker->user_type ?></option>
                                  </select>
                              </div>
                              <br>
                              <div class="row">
                                <div class="col-sm-6">
                                  <label for="firstname"><strong>Adı</strong>
                                    <input type="text" class="form-control" placeholder="Adı" name="firstname" value="<?= $worker->firstname ?>" readonly></label>
                                </div>
                                <div class="col-sm-6">
                                  <label for="lastname"><strong>Soy Adı </strong>
                                    <input type="text" class="form-control" placeholder="Soy Adı" name="lastname" value="<?= $worker->lastname ?>" readonly></label>
                                </div>
                              </div>
                              <br>
                              <div class="col-sm-12">
                                <label for "email"><strong>E-mail </strong>
                                  <input type="email" class="form-control" placeholder="E-mail" name="email" value="<?= $worker->mail ?>" readonly></label>
                              </div>
                              <br>
                              <div class="row">
                                <div class="col-sm-6">
                                  <label for="phone"><strong>Telefon No </strong>
                                    <input type="tel" class="form-control" name="phone" placeholder="Tel: 05XXXXXXXXX" pattern="(\d{4})(\d{3})(\d{2})(\d{2})" maxlength="11" value="<?= $worker->phone ?>" readonly></label>
                                </div>
                                <div class="col-sm-6">
                                  <label for="start_date"><strong>İşe Giriş Tarihi </strong>
                                    <input type="date" class="form-control" placeholder="İşe giriş" name="start_date" value="<?= $worker->start_date ?>" readonly></label>
                                </div>
                              </div>
                              <br>
                              <label for="tc"><strong>T.C Kimlik No </strong>
                                <input type="number" class="form-control" placeholder="T.C Kimlik No" name="tc" min="11" maxlength="11" value="<?= $worker->tc ?>" readonly></label>
                              <br>
                              <label for="not"><strong>Çalışan hakkında not </strong>
                                <textarea class="form-control" id="not" name="not" rows="5" cols="120" style="max-width: 100%;"><?= $worker->worker_text?></textarea></label>
                              <br>
                              <div style="float: right;">
                                <button id="kaydet" name="kaydet" type="submit" class="btn btn-success">Notu Kaydet</button>
                                <button id="tamamen_sil" name="tamamen_sil" type="submit" class="btn btn-danger">Tamamen Sil</button>
                                <button id="tekrar" name="tekrar" type="submit" class="btn btn-warning">Tekrar Aktifleştir</button>
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
                      <td><strong>Bilgiler</strong></td>
                    </tr>
                  </tfoot>
                </table>
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
