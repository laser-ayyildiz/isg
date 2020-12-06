<?php session_start();
$isim = 'deneme2';
require '/home/laser/vendor/autoload.php';
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
$sql_event = "SELECT * FROM `events` WHERE `company_id` = '$company_id'";

$req = $auth->prepare($sql_event);
$req->execute();

$events = $req->fetchAll();


if (isset($_POST['add_worker'])) {
  $worker_name = !empty($_POST['worker_name']) ? trim($_POST['worker_name']) : null;
  $worker_tc = !empty($_POST['worker_tc']) ? trim($_POST['worker_tc']) : null;
  $worker_position= !empty($_POST['worker_position']) ? trim($_POST['worker_position']) : null;
  $worker_sex = !empty($_POST['worker_sex']) ? trim($_POST['worker_sex']) : null;
  $worker_mail = !empty($_POST['worker_mail']) ? trim($_POST['worker_mail']) : null;
  $worker_phone = !empty($_POST['worker_phone']) ? trim($_POST['worker_phone']) : null;
  $worker_contract_date = !empty($_POST['worker_contract_date']) ? trim($_POST['worker_contract_date']) : null;
  $worker_contract_date = date('Y-m-d');

  $sql = "SELECT COUNT(tc) AS num FROM coop_workers WHERE tc = :tc";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':tc', $worker_tc);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row['num'] > 0) {
      ?>
    <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
    <strong>Bu T.C Kimlik Numarası ile daha önce kayıt yapıldı!</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
      <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <?php
      }
  else {
      $sql = "INSERT INTO `coop_workers`(`name`, `tc`, `position`, `sex`, `mail`, `phone`, `contract_date`,`company_id`)
      VALUES('$worker_name', '$worker_tc', '$worker_position', '$worker_sex', '$worker_mail', '$worker_phone', '$worker_contract_date','$company_id')";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute();
      if ($result) {
        mkdir("$worker_tc", 0777);
          ?>
        <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
        <strong>Yeni çalışan başarıyla eklendi!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <?php
      }
  }
}

if (isset($_FILES['ziyaret_dosyası']) && isset($_POST['ziyaret_dosyası_yukle'])) {
  echo "string";
  $rapor_adi = $_POST['visit_report_name'];
  $rapor_tarihi = $_POST['visit_report_date'];
  $dosya_id = rand(0, 9999999999);
  $file= $_FILES['ziyaret_dosyası'];
  $fileName = $_FILES['ziyaret_dosyası']['name'];
  $fileTmp = $_FILES['ziyaret_dosyası']['tmp_name'];
  $fileSize = $_FILES['ziyaret_dosyası']['size'];
  $filesError = $_FILES['ziyaret_dosyası']['error'];
  $fileType = $_FILES['ziyaret_dosyası']['type'];

  $fileExt = explode('.',$_FILES['ziyaret_dosyası']['name']);
  $fileActualExt = strtolower(end($fileExt));
  $allowed = array('xlsx','xls','odt','odf','ods','pdf','docx','doc','txt','jpg','jpeg','png','ppt','pptx');
  if(in_array($fileActualExt,$allowed)){
      if($_FILES['ziyaret_dosyası']['error'] ===  0){
        $fileNameNew = $rapor_tarihi."_".$rapor_adi."_".$dosya_id;
        $fileDestination = 'ziyaret_raporlari';
        while (file_exists("ziyaret_raporlari/".$fileNameNew.".".$fileActualExt)) {
          $dosya_id = rand(0, 9999999999);
          $fileNameNew = $rapor_tarihi."_".$rapor_adi."_".$dosya_id;
        }
        move_uploaded_file($_FILES['ziyaret_dosyası']['tmp_name'],$fileDestination);

        ?>
        <div class='alert alert-primary alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
          <strong>Dosyanız yüklendi</strong>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
            <span aria-hidden='true'>&times;</span>
          </button>
        </div>
        <?php
      }else{
        ?>
        <div class='alert alert-danger alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
          <strong>Dosya yüklenirken bir hata ile karşılaşıldı!Lütfen daha sonra tekrar deneiyiniz! Sorun devam ederse sistem yöneticinizle görüşün.</strong>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
            <span aria-hidden='true'>&times;</span>
          </button>
        </div>
        <?php
      }
  }else{
    ?>
    <div class='alert alert-danger alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
      Dosya türü uygun değil. Lütfen <b>'xlsx','xls','odt','odf','ods','pdf','docx','doc','txt','jpg','jpeg','png','ppt','pptx'</b> türündeki dosyaları yükleyin!
      <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
        <span aria-hidden='true'>&times;</span>
      </button>
    </div>
    <?php
  }
}

if (isset($_FILES['calisan_dosya']) && isset($_POST['calisan_dosya_yukle'])) {
  $cdir_name = !empty($_POST['cdir_name']) ? trim($_POST['cdir_name']) : null;
  $current_dir = getcwd();
  $current_dir = $current_dir.'/'.$cdir_name;
  $file= $_FILES['calisan_dosya'];
  $fileName = $_FILES['calisan_dosya']['name'];
  $fileTmp = $_FILES['calisan_dosya']['tmp_name'];
  $fileSize = $_FILES['calisan_dosya']['size'];
  $filesError = $_FILES['calisan_dosya']['error'];
  $fileType = $_FILES['calisan_dosya']['type'];

  $fileExt = explode('.',$_FILES['calisan_dosya']['name']);
  $fileActualExt = strtolower(end($fileExt));
  $allowed = array('xlsx','xls','odt','odf','ods','pdf','docx','doc','txt','jpg','jpeg','png','ppt','pptx');
  if(in_array($fileActualExt,$allowed)){
      if($_FILES['calisan_dosya']['error'] ===  0){
        $fileNameNew = date("Y-m-d_h:i:sa").".".$fileName;
        $fileDestination = $current_dir.'/'.$fileNameNew;
        move_uploaded_file($_FILES['calisan_dosya']['tmp_name'],$fileDestination);

        ?>
        <div class='alert alert-primary alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
          <strong>Dosyanız yüklendi</strong>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
            <span aria-hidden='true'>&times;</span>
          </button>
        </div>
        <?php
      }else{
        ?>
        <div class='alert alert-danger alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
          <strong>Dosya yüklenirken bir hata ile karşılaşıldı!Lütfen daha sonra tekrar deneiyiniz! Sorun devam ederse sistem yöneticinizle görüşün.</strong>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
            <span aria-hidden='true'>&times;</span>
          </button>
        </div>
        <?php
      }
  }else{
    ?>
    <div class='alert alert-danger alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
      Dosya türü uygun değil. Lütfen <b>'xlsx','xls','odt','odf','ods','pdf','docx','doc','txt','jpg','jpeg','png','ppt','pptx'</b> türündeki dosyaları yükleyin!
      <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
        <span aria-hidden='true'>&times;</span>
      </button>
    </div>
    <?php
  }
}

if(isset($_FILES['calisan_list']) && isset($_POST['calisan_yukle'])){
    $current_dir = getcwd();
    $file= $_FILES['calisan_list'];
    $fileName = $_FILES['calisan_list']['name'];
    $fileTmp = $_FILES['calisan_list']['tmp_name'];
    $fileSize = $_FILES['calisan_list']['size'];
    $filesError = $_FILES['calisan_list']['error'];
    $fileType = $_FILES['calisan_list']['type'];

    $fileExt = explode('.',$_FILES['calisan_list']['name']);
    $fileActualExt = strtolower(end($fileExt));
    $allowed = array('xlsx','xls','ods');
    if(in_array($fileActualExt,$allowed)){
        if($_FILES['calisan_list']['error'] ===  0){
          $fileNameNew = $isim."_calisanlar".".".$fileActualExt;
          $fileDestination = $current_dir.'/'.$fileNameNew;
          $basarili = move_uploaded_file($_FILES['calisan_list']['tmp_name'],$fileDestination);
          if ($basarili) {
            $ext = mb_convert_case($fileActualExt, MB_CASE_TITLE, "UTF-8");

            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ext);
            $reader->setReadDataOnly(TRUE);
            $spreadsheet = $reader->load($fileNameNew);

            $worksheet = $spreadsheet->getActiveSheet();
            // Get the highest row and column numbers referenced in the worksheet
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

            for ($row = 2; $row <= $highestRow; ++$row) {
                    $value1 = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $value2 = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $value3 = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $value4 = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $value5 = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $value6 = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $value7 = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $value7 = date('Y-m-d');
                $sql = "INSERT INTO `coop_workers`(`name`, `position`, `sex`, `tc`, `phone`, `mail`, `contract_date`,`company_id`)
                VALUES('$value1', '$value2', '$value3', '$value4', '$value5', '$value6', '$value7','$company_id')";
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute();
                if ($result) {
                  mkdir("$value4", 0777);
            }
          }
        }

          ?>
          <div class='alert alert-primary alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
            <strong>Dosyanız yüklendi ve çalışanlarınız kaydedildi</strong>
            <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>
          <?php
        }else{
          ?>
          <div class='alert alert-danger alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
            <strong>Dosya yüklenirken bir hatayla karşılaşıldı!Lütfen daha sonra tekra deneiyiniz! Sorun devam ederse sistem yöneticinizle görüşün.</strong>
            <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>
          <?php
        }
    }else{
      ?>
      <div class='alert alert-danger alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
        Dosya türü uygun değil. Lütfen <b>'xlsx' , 'xls' , 'ods'</b> türündeki dosyaları yükleyin!
        <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>
      <?php
    }
}

if (isset($_POST['kaydet'])) {
    $comp_type = !empty($_POST['comp_type']) ? trim($_POST['comp_type']) : null;
    $name = $isim;
    $email = !empty($_POST['mail']) ? trim($_POST['mail']) : null;
    $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : null;
    $city = !empty($_POST['countrySelect']) ? trim($_POST['countrySelect']) : null;
    $town = !empty($_POST['citySelect']) ? trim($_POST['citySelect']) : null;
    $address = !empty($_POST['address']) ? trim($_POST['address']) : null;
    $uzman_id = !empty($_POST['uzman']) ? trim($_POST['uzman']) : 0;
    $hekim_id = !empty($_POST['hekim']) ? trim($_POST['hekim']) : 0;
    $saglık_p_id = !empty($_POST['saglık']) ? trim($_POST['saglık']) : 0;
    $ofis_p_id = !empty($_POST['ofis']) ? trim($_POST['ofis']) : 0;
    $muhasebe_p_id = !empty($_POST['muhasebe']) ? trim($_POST['muhasebe']) : 0;
    $contract_date = !empty($_POST['contract_date']) ? trim($_POST['contract_date']) : null;
    $remi_freq = !empty($_POST['remi_freq']) ? trim($_POST['remi_freq']) : 0;
    $changer = $un;

    $sql = "INSERT INTO `coop_companies` (`comp_type`, `name`, `mail`, `phone`,`address`, `city`, `town`, `contract_date`, `uzman_id`, `hekim_id`,`saglık_p_id`,`ofis_p_id`,`muhasebe_p_id`, `change`, `remi_freq`,`changer`)
   VALUES ('$comp_type', '$name', '$email', '$phone', '$address', '$city', '$town', '$contract_date','$uzman_id', '$hekim_id','$saglık_p_id','$ofis_p_id','$muhasebe_p_id', '0', '$remi_freq','$changer')";
    $stmt = $pdo->prepare($sql);

    $result = $stmt->execute();
    if ($result) {
        ?>
  <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
    <strong>İşletmenizde yaptığınız değişiklikler firma yöneticinize iletilmiştir. İstek onaylandıktan sonra firma değişiklikler uygulanacaktır. Onaylanana kadar yaptığınız işlemler kayıt edilmez!</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <?php
      }
  }

if (isset($_POST['sil'])) {
    $comp_type = !empty($_POST['comp_type']) ? trim($_POST['comp_type']) : null;
    $name = $isim;
    $email = !empty($_POST['mail']) ? trim($_POST['mail']) : null;
    $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : null;
    $city = !empty($_POST['countrySelect']) ? trim($_POST['countrySelect']) : null;
    $town = !empty($_POST['citySelect']) ? trim($_POST['citySelect']) : null;
    $address = !empty($_POST['address']) ? trim($_POST['address']) : null;
    $uzman_id = !empty($_POST['uzman']) ? trim($_POST['uzman']) : 0;
    $hekim_id = !empty($_POST['hekim']) ? trim($_POST['hekim']) : 0;
    $saglık_p_id = !empty($_POST['saglık']) ? trim($_POST['saglık']) : 0;
    $ofis_p_id = !empty($_POST['ofis']) ? trim($_POST['ofis']) : 0;
    $muhasebe_p_id = !empty($_POST['muhasebe']) ? trim($_POST['muhasebe']) : 0;
    $contract_date = !empty($_POST['contract_date']) ? trim($_POST['contract_date']) : null;
    $remi_freq = !empty($_POST['remi_freq']) ? trim($_POST['remi_freq']) : 0;
    $changer = $un;

    $sql = "INSERT INTO `coop_companies` (`comp_type`, `name`, `mail`, `phone`,`address`, `city`, `town`, `contract_date`, `uzman_id`, `hekim_id`, `saglık_p_id`,`ofis_p_id`,`muhasebe_p_id`, `change`, `remi_freq`,`changer`)
   VALUES ('$comp_type', '$name', '$email', '$phone', '$address', '$city', '$town', '$contract_date','$uzman_id', '$hekim_id', '$saglık_p_id', '$ofis_p_id', '$muhasebe_p_id', '2', '$remi_freq','$changer')";
    $stmt = $pdo->prepare($sql);

    $result = $stmt->execute();
    if ($result) {
        ?>
    <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
      <strong>İşletmenizde yaptığınız değişiklikler firma yöneticinize iletilmiştir. İstek onaylandıktan sonra firma değişiklikler uygulanacaktır. Onaylanana kadar yaptığınız işlemler kayıt edilmez!</strong>
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
  <div>
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
    <div class="container-fluid">
      <div class="card border">
        <div class="card-header tab-card-header text-center bg-light text-dark border">
          <h1><b><?= mb_convert_case($isim, MB_CASE_TITLE, "UTF-8")?></b></h1>
          <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="gb-tab" data-toggle="tab" href="#genel_bilgiler" role="tab" aria-controls="Genel Bilgiler" aria-selected="true"><b>Genel Bilgiler</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="oc-tab" data-toggle="tab" href="#osgb_calisanlar" role="tab" aria-controls="OSGB Çalışanları" aria-selected="false"><b>OSGB Çalışanları</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="db-tab" data-toggle="tab" href="#devlet_bilgileri" role="tab" aria-controls="Devlet Bilgileri" aria-selected="false"><b>Devlet Bilgileri</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="ic-tab" data-toggle="tab" href="#isletme_calisanlar" role="tab" aria-controls="İşletme Çalışanları" aria-selected="false"><b>İşletme Çalışanları</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="it-tab" data-toggle="tab" href="#isletme_takvim" role="tab" aria-controls="İşletme Takvimi" aria-selected="false"><b>İşletme Takvimi</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="ie-tab" data-toggle="tab" href="#isletme_ekipman" role="tab" aria-controls="İşletme Ekipmanları" aria-selected="false"><b>İşletme Ekipmanları</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="ir-tab" data-toggle="tab" href="#isletme_rapor" role="tab" aria-controls="İşletme Raporları" aria-selected="false"><b>İşletme Raporları</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="zr-tab" data-toggle="tab" href="#ziyaret_rapor" role="tab" aria-controls="Ziyaret Raporları" aria-selected="false"><b>Ziyaret Raporları</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tr-tab" data-toggle="tab" href="#tamamlanan_rapor" role="tab" aria-controls="Tamamlanan Raporlar" aria-selected="false"><b>Tamamlanan Raporlar</b></a>
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
            <div class="tab-pane fade show active" id="genel_bilgiler" role="tabpanel" aria-labelledby="gb-tab">
                <form action="index.php" method="POST">
                  <fieldset id="gb_form">
                    <div class="form-row">
                      <div class="form-group col-lg-4">
                      <label for="comp_type"><h5><b>Sektör</b></h5></label>
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
                        <input type="text" class="form-control" id="address" name="address" value="<?= $company->address ?>">
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
                        <select class="form-control" name="countrySelect" id="countrySelect" size="1" onchange="makeSubmenu(this.value)" required>
                          <option selected><?= $company->city ?></option>
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
                      <div class="form-group col-lg-4">
                        <label for="citySelect">
                          <h5><b>İlçe</b></h5>
                        </label>
                        <select class="form-control" id="citySelect" name="citySelect" size="1" required>
                          <option selected><?= $company->town ?></option>
                          <option></option>
                        </select>
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
                </form>
            </div><!--işletme_bilgileri end-->

            <!--OSGB Çalışanları -->
            <div class="tab-pane fade" id="osgb_calisanlar" role="tabpanel" aria-labelledby="oc-tab">
              <form action="index.php" method="POST">
                  <fieldset id="oc_form">
                    <!--İsg Uzmanları -->
                    <div class="row">
                      <!--1. İsg Uzmanı -->
                      <div class="col-lg-4">
                        <label for="uzman"><h5><b>1. İsg Uzmanı</b></h5></label>
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
                        <label for="uzman_2"><h5><b>2.İsg Uzmanı</b></h5></label>
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
                        <label for="uzman_3"><h5><b>3.İsg Uzmanı</b></h5></label>
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
                        <label for="hekim"><h5><b>1. İş Yeri Hekimi</b></h5></label>
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
            <div class="tab-pane fade" id="devlet_bilgileri" role="tabpanel" aria-labelledby="db-tab">
              <form action="index.php" method="POST">
                <fieldset id="db_form">
                <div class="row col-lg-12">
                  <label for="nace_kodu"><h4><b>NACE Kodu</b></h4></label>
                  <select name="nace_kodu" id="nace_kodu" class="form-control" required>
                    <option value="<?= $company->nace_kodu ?>" selected><?= $company->nace_kodu ?></option>
                    <option value="81.22.03">81.22.03,Nesne veya binaların (ameliyathaneler vb.) sterilizasyonu faaliyetleri.Binalar ile ilgili hizmetler ve çevre düzenlemesi faaliyetleri 3</option>
                    <option value="82.20.01">82.20.01,Çağrı merkezlerinin faaliyetleri  2</option>
                    <option value="86.90.17">86.90.17,İnsan sağlığı hizmetleri 3</option>
                    <option value="85.59.16">85.59.16,Çocuk kulüplerinin faaliyetleri (6 yaş ve üzeri çocuklar için) 1</option>
                    <option value="71.12.14">71.12.14,Yapı denetim kuruluşları 1</option>
                    <option value="56.10.21">56.10.21,Oturacak yeri olmayan fast-food (hamburger, sandviç, tost vb.) satış yerleri (büfeler dahil), al götür tesisleri (içli pide ve lahmacun fırınları hariç) ve benzerleri tarafından sağlanan diğer yemek hazırlama ve sunum faaliyetleri 1</option>
                    <option value="56.10.20">56.10.20,Oturacak yeri olmayan içli pide ve lahmacun fırınlarının faaliyetleri (al götür tesisi olarak hizmet verenler) 1</option>
                    <option value="47.89.19">47.89.19,Seyyar olarak ve motorlu araçlarla diğer malların perakende ticareti 1</option>
                    <option value="47.82.03">47.82.03,Seyyar olarak ve motorlu araçlarla tekstil, giyim eşyası ve ayakkabı perakende ticareti 1</option>
                    <option value="47.81.12">47.81.12,Seyyar olarak ve motorlu araçlarla gıda ürünleri ve içeceklerin (alkollü içecekler hariç) perakende ticareti 1</option>
                    <option value="47.79.06">47.79.06,Belirli bir mala tahsis edilmiş mağazalarda kullanılmış giysiler ve aksesuarlarının perakende ticareti 1</option>
                    <option value="45.20.09">45.20.09,Motorlu kara taşıtlarının sadece boyanması faaliyetleri 3</option>
                    <option value="25.99.90">25.99.90,Başka yerde sınıflandırılmamış diğer fabrikasyon metal ürünlerin imalatı 2</option>
                    <option value="08.99.01">08.99.01,Aşındırıcı (törpüleyici) materyaller (zımpara), amyant, silisli fosil artıklar, arsenik cevherleri, sabuntaşı (talk) ve feldispat madenciliği (kuartz, mika, şist, talk, silis, sünger taşı, asbest, doğal korindon vb.) 3</option>
                    <option value="08.93.02">08.93.02,Deniz, göl ve kaynak tuzu üretimi (tuzun yemeklik tuza dönüştürülmesi hariç) 2</option>
                    <option value="23.99.07">23.99.07,Amyantlı kağıt imalatı 3</option>
                  </select>
                </div>
                <br>
                <div class="row">
                  <div class="col-6">
                    <label for="mersis_no"><h4><b>Kurum Mersis No</b></h4></label>
                    <input class="form-control" id="mersis_no" name="mersis_no" type="tel" min="16" maxlength="16" placeholder="Mersis No" value="<?= $company->mersis_no ?>">
                  </div>
                  <div class="col-6">
                    <label for="sgk_sicil"><h4><b>SGK Sicil No</b></h4></label>
                    <input class="form-control" id="sgk_sicil" name="sgk_sicil" type="tel" min="12" maxlength="12" placeholder="SGK Sicil No" value="<?= $company->sgk_sicil ?>">
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-6">
                    <label for="vergi_no"><h4><b>Vergi No</b></h4></label>
                    <input class="form-control" id="vergi_no" name="vergi_no" type="tel" min="10" maxlength="10" placeholder="Vergi No" value="<?= $company->vergi_no ?>">
                  </div>
                  <div class="col-6">
                    <label for="vergi_dairesi"><h4><b>Vergi Dairesi</b></h4></label>
                    <input class="form-control" id="vergi_dairesi" name="vergi_dairesi" type="text" maxlength="500" placeholder="Vergi Dairesi" value="<?= $company->vergi_dairesi ?>">
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-6">
                    <label for="katip_is_yeri_id"><h4><b>İSG-KATİP İş Yeri ID</b></h4></label>
                    <input class="form-control" id="katip_is_yeri_id" name="katip_is_yeri_id" type="tel" maxlength="30" placeholder="İSG-KATİP İş Yeri ID" value="<?= $company->katip_is_yeri_id ?>">
                  </div>
                  <div class="col-6">
                    <label for="katip_kurum_id"><h4><b>İSG-KATİP Kurum ID</b></h4></label>
                    <input class="form-control" id="katip_kurum_id" name="katip_kurum_id" type="tel" maxlength="30" placeholder="İSG-KATİP Kurum ID" value="<?= $company->katip_kurum_id ?>">
                  </div>
                </div>
              </fieldset>
              </form>
            </div>

            <!--İşletme Çalışanları -->
            <div class="tab-pane fade" id="isletme_calisanlar" role="tabpanel" aria-labelledby="ic-tab">
              <?php
                if (file_exists($isim."_calisanlar.xlsx") || file_exists($isim."_calisanlar.xls") || file_exists($isim."_calisanlar.ods")){
                  ?>
                  <button class="btn btn-primary" id="ic_form2" data-toggle="modal" data-target="#addWorker" data-whatever="@getbootstrap">Yeni Çalışan Ekle</button>
                  <?php
                }
                else {
                  ?>
                <form method="POST" action="index.php" enctype="multipart/form-data" >
                  <fieldset id="ic_form1">
                    <label for="calisan_list"><b>Çalışan Listesi Yükle-></b></label>
                    <input type="file" class="btn btn-light btn-sm" name="calisan_list" />
                    <input type="submit" class="btn btn-primary" name="calisan_yukle" value="Yükle"/>
                  </fieldset>
                </form>
                <?php
                  }
                   ?>
                  <input type="text" class="form-control" style="float:right;max-width:600px;" id="myInput" onkeyup="myFunction()" placeholder="Çalışan Adı ile ara...">
                  <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                    <table class="table table-striped table-bordered table-hover" id="dataTable">
                      <thead class="thead-dark">
                        <tr>
                          <th >Çalışan Adı Soyadı</th>
                          <th>Pozisyonu</th>
                          <th>Cinsiyeti</th>
                          <th>T.C Kimlik No</th>
                          <th>Telefon No</th>
                          <th>E-mail</th>
                          <th>İşe Giriş Tarihi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                            $sorgu=$pdo->prepare("SELECT * FROM `coop_workers` WHERE `deleted` = 0 AND `company_id` = '$company_id' ORDER BY `name`");
                            $sorgu->execute();
                            $coworkers=$sorgu-> fetchAll(PDO::FETCH_OBJ);
                            foreach ($coworkers as $key=>$coworker) {
                                ?>
                        <tr data-toggle="modal" data-target="#b<?= $key ?>" data-whatever="@getbootstrap" style="cursor: pointer;">
                          <td><?= $coworker->name ?></td>
                          <td><?= $coworker->position ?></td>
                          <td><?= $coworker->sex ?></td>
                          <td><?= $coworker->tc ?></td>
                          <td><?= $coworker->phone ?></td>
                          <td><?= $coworker->mail ?></td>
                          <td><?= $coworker->contract_date ?></td>
                          </tr>
                          <!-- Çalışan Dosyaları -->
                          <div class="modal fade" id="b<?= $key ?>" tabindex="-1" aria-labelledby="label" aria-hidden="true">
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
                                          <form method="POST" action="index.php" enctype="multipart/form-data" >
                                            <fieldset id="ic_form3">
                                              <label for="calisan_dosya"><b>Yeni Dosya Yükle-></b></label>
                                              <input name="cdir_name" type="tel" value="<?= $coworker->tc ?>" hidden>
                                              <input type="file" class="btn btn-light btn-sm" name="calisan_dosya" />
                                              <input type="submit" class="btn btn-primary" name="calisan_dosya_yukle" value="Yükle"/>
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
                        </tr>
                      </tfoot>
                    </table>
                  </div>
            </div>

            <!--İşletme Takvimi -->
            <div class="tab-pane fade" id="isletme_takvim" role="tabpanel" aria-labelledby="it-tab">
              <div id="calendar" style="margin: auto;" class="col-centered"></div>
            </div>

            <!--İşletme Ekipmanları -->
            <div class="tab-pane fade" id="isletme_ekipman" role="tabpanel" aria-labelledby="ie-tab">
              isletme_ekipman
            </div>

            <!--İşletme Raporları -->
            <div class="tab-pane fade" id="isletme_rapor" role="tabpanel" aria-labelledby="ir-tab">
              isletme_rapor
            </div>

            <!--Tamamlanan Raporlar -->
            <div class="tab-pane fade" id="tamamlanan_rapor" role="tabpanel" aria-labelledby="tr-tab">

            </div>

            <!--Ziyaret Raporları -->
            <div class="tab-pane fade" id="ziyaret_rapor" role="tabpanel" aria-labelledby="zr-tab">
                <fieldset id="zr_form">
                  <form action="index.php" method="POST">
                    <div class="row">
                      <div class="col-3">
                        <label for="visit_report_name"><h6><b>Raporun Adını Giriniz</b></h6></label>
                        <input name="visit_report_name" id="visit_report_name" class="form-control" type="text" maxlength="20" placeholder="Rapor Adı" required>
                      </div>
                      <div class="col-2">
                        <label for="visit_report_date"><h6><b>Raporun Tarihini Giriniz</b></h6></label>
                        <input name="visit_report_date" id="visit_report_date" class="form-control" type="date" required>
                      </div>
                    </div>
                    <br>
                    <input type="file" class="btn btn-light btn-sm" name="ziyaret_dosyası" id="ziyaret_dosyası">
                    <button type="submit" class="btn btn-primary" name="ziyaret_dosyası_yukle" id="ziyaret_dosyası_yukle">Yükle</button>
                  </form>
                </fieldset>
            </div>

          </div><!-- tab content end -->
        </div><!-- card body end -->
        <form action="index.php" method="POST">
          <fieldset id="save_changes">
            <div class="card-footer bg-light border">
              <button class="btn btn-primary" name="gb_kaydet" id="gb_kaydet" type="submit" style="float:left; height:60px; width:300px;">Bütün Değişiklikleri Kaydet*</button>
              <button class="btn btn-danger" name="isletme_sil" id="isletme_sil" type="submit" style="float:right; height:60px; width:300px;">İşletmeyi Sil</button>
              <br><br><br>
              <p><b>*Lütfen işletmenizin bütün sayfalarındaki değişikliklerinizi tamamladıktan sonra bu butonu kullanın.Bu buton her sayfa için ayrı ayrı çalışmaz!</b></p>
            </div>
          </fieldset>
        </form>
      </div> <!--card end-->

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
              <form action="index.php" method="POST">
                <div class="row">
                  <div class="col-6">
                    <label for="worker_name"><h5><b>Çalışanın Adını ve Soyadını Giriniz</b></h5></label>
                    <input name="worker_name" id="worker_name" class="form-control" type="text" maxlength="100" placeholder="Ad Soyad" required>
                  </div>
                  <div class="col-6">
                    <label for="worker_tc"><h5><b>Çalışanın T.C Kimlik Numarasını Giriniz</b></h5></label>
                    <input name="worker_tc" id="worker_tc" class="form-control" type="tel" maxlength="11" minlength="11" placeholder="T.C Kimlik No" required>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-6">
                    <label for="worker_mail"><h5><b>Çalışanın E-mail adresini Giriniz</b></h5></label>
                    <input name="worker_mail" id="worker_mail" class="form-control" type="email" maxlength="100" placeholder="E-mail" required>
                  </div>
                  <div class="col-6">
                    <label for="worker_phone"><h5><b>Çalışanın Telefon Numarasını Giriniz</b></h5></label>
                    <input name="worker_phone" id="worker_phone" class="form-control" type="tel" maxlength="11" minlength="11" placeholder="05XXXXXXXXX" required>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-6">
                    <label for="worker_position"><h5><b>Çalışanın Posizyonu Giriniz</b></h5></label>
                    <input name="worker_position" id="worker_position" class="form-control" type="text" maxlength="100" placeholder="Pozisyon" required>
                  </div>
                  <div class="col-6">
                    <label for="worker_sex"><h5><b>Çalışanının Cinsiyetini Giriniz</b></h5></label>
                    <select name="worker_sex" id="worker_sex" class="form-control" required>
                      <option value="" disabled selected>Çalışanın Cinsiyeti</option>
                      <option value="Erkek">Erkek</option>
                      <option value="Kadın">Kadın</option>
                    </select>
                  </div>
                </div>
                <br>
                <div class="row col-6">
                  <label for="worker_contract_date"><h5><b>Çalışanın İşe Giriş Tarihi</b></h5></label>
                  <input name="worker_contract_date" id="worker_contract_date" class="form-control" type="date" required>
                </div>
              </div>
                <div class="modal-footer bg-light">
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
                <label for="description" class="col-sm-2 control-label">Açıklama</label>
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
                <label for="description" class="col-sm-2 control-label">Açıklama</label>
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
                    if ($('#'+check).is(':checked')) {
                      $('#'+check+'_label').removeClass('label-on');
                      $('#'+check+'_label').addClass('label-off');
                    } else {
                      $('#'+check+'_label').addClass('label-on');
                      $('#'+check+'_label').removeClass('label-off');
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

      </div> <!--container end-->
    <footer class="bg-white sticky-footer">
        <div class="container my-auto">
          <div class="text-center my-auto copyright"><span>Copyright © ÖzgürOSGB 2020</span></div>
        </div>
      </footer>
  </div>
  <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
  <script src="../../assets/js/jquery.min.js"></script>
  <script src="../../assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="../../assets/js/chart.min.js"></script>
  <script src="../../assets/js/bs-init.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
  <script src="../../assets/js/theme.js"></script>
  <?php
    if ($giriş_auth != 0 && $giriş_auth != 1 && $giriş_auth != 2 && $giriş_auth != 3) {
   ?>
    <script>
      $('#gb_form').prop('disabled', true);
      $('#oc_form').prop('disabled', true);
      $('#db_form').prop('disabled', true);

      $('#save_changes').prop('hidden', true);
    </script>
    <?php
    }
     ?>
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
    $( "ic_form1" ).click(function( event ) {
      event.preventDefault();
    });
  </script>
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
  <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js'></script>
  <script src='../../calendar/js/moment.min.js'></script>
  <script
    src="https://code.jquery.com/jquery-1.9.1.min.js"
    integrity="sha256-wS9gmOZBqsqWxgIVgA8Y9WcQOa7PgSIX+rPA0VL2rbQ="
    crossorigin="anonymous">
  </script>
  <script src='../../calendar/js/fullcalendar.min.js'></script>
  <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js'></script>
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
       url: './core/edit-date.php',
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

  });</script>
</body>

</html>
