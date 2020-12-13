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
    $username = $giriş->username;
    $un = $giriş->username;
    $ume = $giriş->username;
    $picture = $giriş->picture;
    $auth = $giriş->auth;
}
if ($auth == 7) {
  $sorgu=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `isletme_id` = '$id' OR `isletme_id_2` = '$id' OR `isletme_id_3` = '$id'");
  $sorgu->execute();
  $comps=$sorgu-> fetchAll(PDO::FETCH_OBJ);
  foreach ($comps as $comp) {
    $company_name = $comp->name;
  }
}
if (isset($_POST['gönder'])) {
    $kime = !empty($_POST['kime']) ? trim($_POST['kime']) : null;
    $konu = !empty($_POST['konu']) ? trim($_POST['konu']) : null;
    $mesaj = !empty($_POST['mesaj']) ? trim($_POST['mesaj']) : null;
    $kimden = $username;
    $sql = "INSERT INTO `message` (`kimden`, `kime`, `mesaj`, `konu`)
  VALUES ('$kimden','$kime','$mesaj','$konu')";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute();
    if ($result) {
        ?>
<div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
  <strong>Mesaj Gönderildi</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php
    }
}
if (isset($_POST['sil'])) {
    $kimden = !empty($_POST['kimden']) ? trim($_POST['kimden']) : null;
    $konu = !empty($_POST['konu']) ? trim($_POST['konu']) : null;
    $mesaj = !empty($_POST['mesaj']) ? trim($_POST['mesaj']) : null;
    $kime = $username;
    $sql2 = "DELETE FROM `message` WHERE `kime` = '$kime' AND `kimden` = '$kimden' AND `konu` = '$konu' AND `mesaj` = '$mesaj'";
    $stmt = $pdo->prepare($sql2);
    $result = $stmt->execute();
    if ($result) {
        ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Mesaj Silindi</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php
    }
}
if (isset($_POST['hepsini_sil'])) {
    $sql3 = "DELETE FROM `message` WHERE `kime` = '$username'";
    $stmt = $pdo->prepare($sql3);
    $result = $stmt->execute();
    if ($result) {
        ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Bütün Mesajlarınız Silindi</strong>
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
  <title>Mesajlar</title>
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
    img.a {
      border-radius: 4px;
      /* Rounded border */
      padding: auto;
      /* Some padding */
      width: 100px;
      /* Set a small width */
    }

  </style>
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

.navbar a.active {
  background-color: red;
  color: white;
}
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
          <h3 class="text-dark mb-4">Mesajlar</h3>
          <div class="card shadow-lg">
            <div class="card-body">
              <form method="POST" action="messages.php">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@getbootstrap">Yeni Mesaj</button>
                <button type="submit" class="btn btn-danger" name="hepsini_sil">Hepsini Sil</button>
                <div class="form-group col-md-4" style="float:right;">
                  <input type="text" class="form-control" id="myInput" onkeyup="myFunction()" placeholder="Mesajlarda Ara...">
                </div>
              </form>
              <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Mesaj Oluştur</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form action="messages.php" method="POST">
                        <label><strong>Kime:</strong>
                          <select class="form-control" id="kime" name="kime" size="1" required>
                            <?php
                      $msg1=$pdo->prepare("SELECT username FROM `users` WHERE `username` != '$username' ORDER BY username");
                      $msg1->execute();
                      $kims=$msg1-> fetchAll(PDO::FETCH_OBJ);
                      foreach ($kims as $kim) {
                          ?>
                            <option value=<?= $kim->username ?>><?= $kim->username ?></option>
                            <?php
                      } ?>
                          </select>
                          <br>
                          <label><strong>Mesajınızın Konusu:</strong>
                            <input type="text" class="form-control" placeholder="Konu" name="konu" required></label>
                          <br>
                          <label for="mesaj"><b>Mesajınız:</b></label>
                          <textarea class="form-control" id="mesaj" name="mesaj" rows="5" cols="120" style="max-width: 100%;"></textarea>
                          <div class="modal-footer">
                            <button id="gönder" name="gönder" type="submit" class="btn btn-success">Gönder</button>
                          </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

              <div class="table-responsive table table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                <table class="table-bordered table-striped table-hover" style="width:99%" id="dataTable">
                  <thead class="thead-dark">
                    <tr>
                      <div style="text-align:center">
                        <h3><b>Gelen Kutusu</b>
                          <h3>
                      </div>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                          $msg=$pdo->prepare("SELECT * FROM `message` WHERE `kime` = '$username' ORDER BY tarih");
                          $msg->execute();
                          $messages=$msg-> fetchAll(PDO::FETCH_OBJ);
                          $i = 0;
                          foreach ($messages as $key=>$message) {
                              ?>
                    <tr>
                      <td data-toggle="modal" data-target="#a<?php echo $key; ?>" data-whatever="@getbootstrap">
                        <div class="media">
                          <?php
                                        $username = $message->kimden;
                              $id = $pdo->prepare("SELECT picture FROM `users` WHERE `username` = '$username'");
                              $id->execute();
                              $ssss=$id-> fetchAll(PDO::FETCH_OBJ);
                              foreach ($ssss as $sss) {
                              ?>
                          <img class="a" src="assets/users/<?= $sss->picture; ?>" class="media-object">
                          <?php
                              } ?>
                          <div class="media-body">
                            <h4 class="text-primary"><b>&emsp;Konu: </b><?= $message->konu?></h4>
                            <h5 class="text-success"><span class="media-meta"><b>&emsp;&emsp;<b>Kimden: </b><?= $message->kimden?></span></h5>
                            <h6 class="text-secondary">&emsp;&emsp;</h6> &emsp;<b>Tarih: <?= $message->tarih ?></b>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <div class="modal fade" id="a<?php echo $key; ?>" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel2">Gelen Mesaj</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <form id="<?=$i?>" action="messages.php" method="POST">
                              <label for"kimden"><strong>Kimden:</strong>
                                <input readonly name="kimden" class="form-control" id="kimden" value="<?= $message->kimden?>"></label>
                              <br>
                              <label for="konu"><strong>Konu:</strong>
                                <input readonly class="form-control" name="konu" id="konu" value="<?= $message->konu?>"></label>
                              <br>
                              <label for="texta"><b>Mesaj:</b></label>
                              <textarea readonly form="<?=$i?>" class="form-control" id="mesaj" name="mesaj" rows="5" cols="120" style="max-width: 100%;"><?= $message->mesaj?></textarea>
                          </div>
                          <div class="modal-footer">
                            <button id="sil" name="sil" type="submit" class="btn btn-danger">Sil</button>
                          </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    <?php $i++;
                          } ?>

                  </tbody>
                  <tfoot>
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
