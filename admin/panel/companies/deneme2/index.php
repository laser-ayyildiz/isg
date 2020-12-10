<?php session_start();
$isim = 'deneme2';
require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
require_once('../../calendar/utils/auth.php');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header('Location: ../../../login.php');
    exit;
}

require '../../../connect.php';
$id=$_SESSION['user_id'];

$baÅŸlangÄ±Ã§=$pdo->prepare("SELECT * FROM users WHERE id = '$id'");
$baÅŸlangÄ±Ã§->execute();
$giriÅŸler=$baÅŸlangÄ±Ã§-> fetchAll(PDO::FETCH_OBJ);
foreach ($giriÅŸler as $giriÅŸ) {
    $fn = $giriÅŸ->firstname;
    $ln = $giriÅŸ->lastname;
    $un = $giriÅŸ->username;
    $ume = $giriÅŸ->username;
    $yetkili_auth = $giriÅŸ->auth;
    $picture= $giriÅŸ->picture;
    $giriÅŸ_auth = $giriÅŸ->auth;
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

    $yetkili_saglÄ±k_p_id = $yet->saglÄ±k_p_id;
    $yetkili_saglÄ±k_p_id_2 = $yet->saglÄ±k_p_id_2;

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
    } elseif ($yetkili_saglÄ±k_p_id == $id) {
        echo "";
    }elseif ($yetkili_saglÄ±k_p_id_2 == $id) {
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

if (isset($_POST['eq_save'])) {
  $eq_name = !empty($_POST['eq_name']) ? trim($_POST['eq_name']) : null;
  $eq_purpose = !empty($_POST['eq_purpose']) ? trim($_POST['eq_purpose']) : null;
  $eq_freq = !empty($_POST['eq_freq']) ? trim($_POST['eq_freq']) : null;
  $company_id = !empty($_POST['company_id']) ? trim($_POST['company_id']) : null;
  $sql = "INSERT INTO `equipment`(`company_id`, `name`, `purpose`, `maintenance_freq`)
  VALUES('$company_id', '$eq_name', '$eq_purpose', '$eq_freq')";
  $stmt = $pdo->prepare($sql);
  $result = $stmt->execute();
  if ($result) {
      ?>
    <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
    <strong>Yeni ekipman baÅŸarÄ±yla eklendi!</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
      <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <?php
  }
}

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
    <strong>Bu T.C Kimlik NumarasÄ± ile daha Ã¶nce kayÄ±t yapÄ±ldÄ±!</strong>
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
        <strong>Yeni Ã§alÄ±ÅŸan baÅŸarÄ±yla eklendi!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <?php
      }
  }
}

if (isset($_POST['deleteWorkerButton'])) {
  $worker_tc = $_POST['TCWillDelete'];

    $sql = "UPDATE `coop_workers` SET `deleted` = 1 WHERE `company_id` = '$company_id' AND `tc` = '$worker_tc'";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute();
    if ($result) {
        ?>
      <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
        <strong>Ã‡alÄ±ÅŸan Silindi!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <?php
    }
}

if (isset($_POST['recruitAgain'])) {
  $worker_tc = $_POST['TCWillRecruit'];

    $sql = "UPDATE `coop_workers` SET `deleted` = 0 WHERE `company_id` = '$company_id' AND `tc` = '$worker_tc'";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute();
    if ($result) {
        ?>
      <div class="alert alert-success alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
        <strong>Ã‡alÄ±ÅŸan Tekrak Eklendi!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <?php
    }
}

if (isset($_FILES['ziyaret_dosyasÄ±']) && isset($_POST['ziyaret_dosyasÄ±_yukle'])) {
  $rapor_adi = $_POST['visit_report_name'];
  $rapor_tarihi = $_POST['visit_report_date'];
  $dosya_id = rand(0, 9999);
  $file= $_FILES['ziyaret_dosyasÄ±'];
  $fileName = $_FILES['ziyaret_dosyasÄ±']['name'];
  $fileTmp = $_FILES['ziyaret_dosyasÄ±']['tmp_name'];
  $fileSize = $_FILES['ziyaret_dosyasÄ±']['size'];
  $filesError = $_FILES['ziyaret_dosyasÄ±']['error'];
  $fileType = $_FILES['ziyaret_dosyasÄ±']['type'];

  $fileExt = explode('.',$_FILES['ziyaret_dosyasÄ±']['name']);
  $fileActualExt = strtolower(end($fileExt));
  $allowed = array('xlsx','xls','odt','odf','ods','pdf','docx','doc','txt','jpg','jpeg','png','ppt','pptx');
  if(in_array($fileActualExt,$allowed)){
      if($_FILES['ziyaret_dosyasÄ±']['error'] ===  0){
        $fileNameNew = $rapor_tarihi."_".$rapor_adi."_".$dosya_id;
        $fileDestination = __DIR__.'/ziyaret_raporlari/'.$fileNameNew.".".$fileActualExt;
        while (file_exists("ziyaret_raporlari/".$fileNameNew.".".$fileActualExt)) {
          $dosya_id = rand(0, 9999999999);
          $fileNameNew = $rapor_tarihi."_".$rapor_adi."_".$dosya_id;
        }

        move_uploaded_file($_FILES['ziyaret_dosyasÄ±']['tmp_name'],$fileDestination);

        ?>
        <div class='alert alert-primary alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
          <strong>DosyanÄ±z yÃ¼klendi</strong>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
            <span aria-hidden='true'>&times;</span>
          </button>
        </div>
        <?php
      }else{
        ?>
        <div class='alert alert-danger alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
          <strong>Dosya yÃ¼klenirken bir hata ile karÅŸÄ±laÅŸÄ±ldÄ±!LÃ¼tfen daha sonra tekrar deneiyiniz! Sorun devam ederse sistem yÃ¶neticinizle gÃ¶rÃ¼ÅŸÃ¼n.</strong>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
            <span aria-hidden='true'>&times;</span>
          </button>
        </div>
        <?php
      }
  }else{
    ?>
    <div class='alert alert-danger alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
      Dosya tÃ¼rÃ¼ uygun deÄŸil. LÃ¼tfen <b>'xlsx','xls','odt','odf','ods','pdf','docx','doc','txt','jpg','jpeg','png','ppt','pptx'</b> tÃ¼rÃ¼ndeki dosyalarÄ± yÃ¼kleyin!
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
          <strong>DosyanÄ±z yÃ¼klendi</strong>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
            <span aria-hidden='true'>&times;</span>
          </button>
        </div>
        <?php
      }else{
        ?>
        <div class='alert alert-danger alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
          <strong>Dosya yÃ¼klenirken bir hata ile karÅŸÄ±laÅŸÄ±ldÄ±!LÃ¼tfen daha sonra tekrar deneiyiniz! Sorun devam ederse sistem yÃ¶neticinizle gÃ¶rÃ¼ÅŸÃ¼n.</strong>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
            <span aria-hidden='true'>&times;</span>
          </button>
        </div>
        <?php
      }
  }else{
    ?>
    <div class='alert alert-danger alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
      Dosya tÃ¼rÃ¼ uygun deÄŸil. LÃ¼tfen <b>'xlsx','xls','odt','odf','ods','pdf','docx','doc','txt','jpg','jpeg','png','ppt','pptx'</b> tÃ¼rÃ¼ndeki dosyalarÄ± yÃ¼kleyin!
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
            <strong>DosyanÄ±z yÃ¼klendi ve Ã§alÄ±ÅŸanlarÄ±nÄ±z kaydedildi</strong>
            <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>
          <?php
        }else{
          ?>
          <div class='alert alert-danger alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
            <strong>Dosya yÃ¼klenirken bir hatayla karÅŸÄ±laÅŸÄ±ldÄ±!LÃ¼tfen daha sonra tekra deneiyiniz! Sorun devam ederse sistem yÃ¶neticinizle gÃ¶rÃ¼ÅŸÃ¼n.</strong>
            <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>
          <?php
        }
    }else{
      ?>
      <div class='alert alert-danger alert-dismissible fade show' style='margin-bottom: 0 !important;' role='alert'>
        Dosya tÃ¼rÃ¼ uygun deÄŸil. LÃ¼tfen <b>'xlsx' , 'xls' , 'ods'</b> tÃ¼rÃ¼ndeki dosyalarÄ± yÃ¼kleyin!
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
    $saglÄ±k_p_id = !empty($_POST['saglÄ±k']) ? trim($_POST['saglÄ±k']) : 0;
    $ofis_p_id = !empty($_POST['ofis']) ? trim($_POST['ofis']) : 0;
    $muhasebe_p_id = !empty($_POST['muhasebe']) ? trim($_POST['muhasebe']) : 0;
    $contract_date = !empty($_POST['contract_date']) ? trim($_POST['contract_date']) : null;
    $remi_freq = !empty($_POST['remi_freq']) ? trim($_POST['remi_freq']) : 0;
    $changer = $un;

    $uzman_id_2 = !empty($_POST['uzman_2']) ? trim($_POST['uzman_2']) : 0;
    $uzman_id_3 = !empty($_POST['uzman_3']) ? trim($_POST['uzman_3']) : 0;

    $hekim_id_2 = !empty($_POST['hekim_2']) ? trim($_POST['hekim_2']) : 0;
    $hekim_id_3 = !empty($_POST['hekim_3']) ? trim($_POST['hekim_3']) : 0;

    $saglÄ±k_p_id_2 = !empty($_POST['saglÄ±k_2']) ? trim($_POST['saglÄ±k_2']) : 0;

    $ofis_p_id_2 = !empty($_POST['ofis_2']) ? trim($_POST['ofis_2']) : 0;

    $muhasebe_p_id_2 = !empty($_POST['muhasebe_2']) ? trim($_POST['muhasebe_2']) : 0;

    $nace_kodu = !empty($_POST['nace_kodu']) ? trim($_POST['nace_kodu']) : 0;
    $mersis_no = !empty($_POST['mersis_no']) ? trim($_POST['mersis_no']) : 0;
    $sgk_sicil = !empty($_POST['sgk_sicil']) ? trim($_POST['sgk_sicil']) : 0;
    $vergi_no = !empty($_POST['vergi_no']) ? trim($_POST['vergi_no']) : 0;
    $vergi_dairesi = !empty($_POST['vergi_dairesi']) ? trim($_POST['vergi_dairesi']) : null;
    $katip_is_yeri_id = !empty($_POST['katip_is_yeri_id']) ? trim($_POST['katip_is_yeri_id']) : 0;
    $katip_kurum_id = !empty($_POST['katip_kurum_id']) ? trim($_POST['katip_kurum_id']) : 0;

    $sql = "INSERT INTO `coop_companies`
    (`comp_type`, `name`, `mail`, `phone`,`address`, `city`, `town`, `contract_date`, `uzman_id`, `uzman_id_2`, `uzman_id_3`, `hekim_id`,`hekim_id_2`,
       `hekim_id_3`,`saglÄ±k_p_id`,`saglÄ±k_p_id_2`,`ofis_p_id`,`ofis_p_id_2`,`muhasebe_p_id`,`muhasebe_p_id_2`, `change`, `remi_freq`,`changer`)
   VALUES
   ('$comp_type', '$name', '$email', '$phone', '$address', '$city', '$town', '$contract_date', '$uzman_id', '$uzman_id_2', '$uzman_id_3',
     '$hekim_id', '$hekim_id_2', '$hekim_id_3', '$saglÄ±k_p_id', '$saglÄ±k_p_id_2', '$ofis_p_id', '$ofis_p_id_2',
     '$muhasebe_p_id', '$muhasebe_p_id_2', '0', '$remi_freq','$changer')";
    $stmt = $pdo->prepare($sql);

    $result = $stmt->execute();
    if ($result) {
        ?>
  <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
    <strong>Ä°ÅŸletmenizde yaptÄ±ÄŸÄ±nÄ±z deÄŸiÅŸiklikler firma yÃ¶neticinize iletilmiÅŸtir. Ä°stek onaylandÄ±ktan sonra firma deÄŸiÅŸiklikleri uygulanacaktÄ±r. Onaylanana kadar yaptÄ±ÄŸÄ±nÄ±z iÅŸlemler kayÄ±t edilmez. LÃ¼tfen onaylanmasÄ±nÄ± bekleyiniz!</strong>
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
  $saglÄ±k_p_id = !empty($_POST['saglÄ±k']) ? trim($_POST['saglÄ±k']) : 0;
  $ofis_p_id = !empty($_POST['ofis']) ? trim($_POST['ofis']) : 0;
  $muhasebe_p_id = !empty($_POST['muhasebe']) ? trim($_POST['muhasebe']) : 0;
  $contract_date = !empty($_POST['contract_date']) ? trim($_POST['contract_date']) : null;
  $remi_freq = !empty($_POST['remi_freq']) ? trim($_POST['remi_freq']) : 0;
  $changer = $un;

  $uzman_id_2 = !empty($_POST['uzman_2']) ? trim($_POST['uzman_2']) : 0;
  $uzman_id_3 = !empty($_POST['uzman_3']) ? trim($_POST['uzman_3']) : 0;

  $hekim_id_2 = !empty($_POST['hekim_2']) ? trim($_POST['hekim_2']) : 0;
  $hekim_id_3 = !empty($_POST['hekim_3']) ? trim($_POST['hekim_3']) : 0;

  $saglÄ±k_p_id_2 = !empty($_POST['saglÄ±k_2']) ? trim($_POST['saglÄ±k_2']) : 0;

  $ofis_p_id_2 = !empty($_POST['ofis_2']) ? trim($_POST['ofis_2']) : 0;

  $muhasebe_p_id_2 = !empty($_POST['muhasebe_2']) ? trim($_POST['muhasebe_2']) : 0;

  $nace_kodu = !empty($_POST['nace_kodu']) ? trim($_POST['nace_kodu']) : 0;
  $mersis_no = !empty($_POST['mersis_no']) ? trim($_POST['mersis_no']) : 0;
  $sgk_sicil = !empty($_POST['sgk_sicil']) ? trim($_POST['sgk_sicil']) : 0;
  $vergi_no = !empty($_POST['vergi_no']) ? trim($_POST['vergi_no']) : 0;
  $vergi_dairesi = !empty($_POST['vergi_dairesi']) ? trim($_POST['vergi_dairesi']) : null;
  $katip_is_yeri_id = !empty($_POST['katip_is_yeri_id']) ? trim($_POST['katip_is_yeri_id']) : 0;
  $katip_kurum_id = !empty($_POST['katip_kurum_id']) ? trim($_POST['katip_kurum_id']) : 0;

  $sql = "INSERT INTO `coop_companies`
  (`comp_type`, `name`, `mail`, `phone`,`address`, `city`, `town`, `contract_date`, `uzman_id`, `uzman_id_2`, `uzman_id_3`, `hekim_id`,`hekim_id_2`,
     `hekim_id_3`,`saglÄ±k_p_id`,`saglÄ±k_p_id_2`,`ofis_p_id`,`ofis_p_id_2`,`muhasebe_p_id`,`muhasebe_p_id_2`, `change`, `remi_freq`,`changer`)
 VALUES
 ('$comp_type', '$name', '$email', '$phone', '$address', '$city', '$town', '$contract_date', '$uzman_id', '$uzman_id_2', '$uzman_id_3',
   '$hekim_id', '$hekim_id_2', '$hekim_id_3', '$saglÄ±k_p_id', '$saglÄ±k_p_id_2', '$ofis_p_id', '$ofis_p_id_2',
   '$muhasebe_p_id', '$muhasebe_p_id_2', '2', '$remi_freq','$changer')";
  $stmt = $pdo->prepare($sql);

  $result = $stmt->execute();
  if ($result) {
      ?>
  <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
    <strong>Ä°ÅŸletmenizde yaptÄ±ÄŸÄ±nÄ±z deÄŸiÅŸiklikler firma yÃ¶neticinize iletilmiÅŸtir. Ä°stek onaylandÄ±ktan sonra firma deÄŸiÅŸiklikleri uygulanacaktÄ±r. Onaylanana kadar yaptÄ±ÄŸÄ±nÄ±z iÅŸlemler kayÄ±t edilmez. LÃ¼tfen onaylanmasÄ±nÄ± bekleyiniz!</strong>
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
      <a class="navbar-brand" title="Anasayfa" style="color: black;" href="../../index.php"><b>Ã–zgÃ¼r OSGB</b></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span></button>

      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
        <div class="dropdown no-arrow">
          <a style="color:black;" class="nav-link btn btn-warning dropdown-toggle"type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-building"></i><span>&nbsp;Ä°ÅŸletmeler</span></a>
              <div class="dropdown-content" aria-labelledby="dropdownMenu2">
                <a class="dropdown-item" type="button" href="../../companies.php"><i class="fas fa-stream"></i><span>&nbsp;Ä°ÅŸletme Listesi</span></a>
                <a class="dropdown-item" type="button" href="../../deleted_companies.php"><i class="fas fa-eraser"></i><span>&nbsp;Silinen Ä°ÅŸletmeler</span></a>
                <?php
                if($giriÅŸ_auth == 1){?>
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
            if ($giriÅŸ_auth == 1) {
          ?>
        <li class="nav-item"><a style="color: black;" class="nav-link btn-warning" href="../../settings.php"><i
              class="fas fa-wrench"></i><span>&nbsp;Ayarlar</span></a></li>
        <li class="nav-item">
        <div class="dropdown no-arrow">
          <button style="color:black;" class="nav-link btn btn-warning dropdown-toggle"type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
              class="fas fa-users"></i><span>&nbsp;Ã‡alÄ±ÅŸanlar</span></button>
              <div class="dropdown-content" aria-labelledby="dropdownMenu2">
                <a class="dropdown-item" type="button" href="../../osgb_users.php"><i class="fas fa-stream"></i><span>&nbsp;Ã‡alÄ±ÅŸan Listesi</span></a>
                <a class="dropdown-item" type="button" href="../../deleted_workers.php"><i class="fas fa-eraser"></i><span>&nbsp;Silinen Ã‡alÄ±ÅŸanlar</span></a>
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
          <li class="nav-item"><a style="color: black;" title="Ã‡Ä±kÄ±ÅŸ" class="nav-link" href="../../logout.php"><i class="fas fa-sign-out-alt"></i><span>&nbsp;Ã‡Ä±kÄ±ÅŸ</span></a></li>
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
                <a class="nav-link" id="oc-tab" data-toggle="tab" href="#osgb_calisanlar" role="tab" aria-controls="OSGB Ã‡alÄ±ÅŸanlarÄ±" aria-selected="false"><b>OSGB Ã‡alÄ±ÅŸanlarÄ±</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="db-tab" data-toggle="tab" href="#devlet_bilgileri" role="tab" aria-controls="Devlet Bilgileri" aria-selected="false"><b>Devlet Bilgileri</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="ic-tab" data-toggle="tab" href="#isletme_calisanlar" role="tab" aria-controls="Ä°ÅŸletme Ã‡alÄ±ÅŸanlarÄ±" aria-selected="false"><b>Ä°ÅŸletme Ã‡alÄ±ÅŸanlarÄ±</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="it-tab" data-toggle="tab" href="#isletme_takvim" role="tab" aria-controls="Ä°ÅŸletme Takvimi" aria-selected="false"><b>Ä°ÅŸletme Takvimi</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="ie-tab" data-toggle="tab" href="#isletme_ekipman" role="tab" aria-controls="Ä°ÅŸletme EkipmanlarÄ±" aria-selected="false"><b>Ä°ÅŸletme EkipmanlarÄ±</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="ir-tab" data-toggle="tab" href="#isletme_rapor" role="tab" aria-controls="Ä°ÅŸletme RaporlarÄ±" aria-selected="false"><b>Ä°ÅŸletme RaporlarÄ±</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="zr-tab" data-toggle="tab" href="#ziyaret_rapor" role="tab" aria-controls="Ziyaret RaporlarÄ±" aria-selected="false"><b>Ziyaret RaporlarÄ±</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tr-tab" data-toggle="tab" href="#tamamlanan_rapor" role="tab" aria-controls="Tamamlanan Raporlar" aria-selected="false"><b>Tamamlanan Raporlar</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="sc-tab" data-toggle="tab" href="#silinen_calisanlar" role="tab" aria-controls="Silinen Ã‡alÄ±ÅŸanlar" aria-selected="false"><b>Silinen Ã‡alÄ±ÅŸanlar</b></a>
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
                      <label for="comp_type"><h5><b>SektÃ¶r</b></h5></label>
                      <select class="form-control" id="comp_type" name="comp_type" required>
                        <option value="<?= $company->comp_type ?>" selected><?= $company->comp_type ?></option>
                        <option value="Hizmet">Hizmet</option>
                        <option value="SaÄŸlÄ±k">SaÄŸlÄ±k</option>
                        <option value="Sanayi">Sanayi</option>
                        <option value="TarÄ±m">TarÄ±m</option>
                        <option value="DiÄŸer">DiÄŸer</option>
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
                          <h5><b>AnlaÅŸma Tarihi</b></h5>
                        </label>
                        <input type="date" class="form-control" name="contract_date" id="contract_date" value="<?= $company->contract_date ?>" required>
                      </div>
                      <div class="form-group col-lg-4">
                        <label for="countrySelect">
                          <h5><b>Å�ehir</b></h5>
                        </label>
                        <select class="form-control" name="countrySelect" id="countrySelect" size="1" onchange="makeSubmenu(this.value)" required>
                          <option selected><?= $company->city ?></option>
                          <option>Adana</option>
                          <option>AdÄ±yaman</option>
                          <option>Afyonkarahisar</option>
                          <option>AÄŸrÄ±</option>
                          <option>Amasya</option>
                          <option>Ankara</option>
                          <option>Antalya</option>
                          <option>Artvin</option>
                          <option>AydÄ±n</option>
                          <option>BalÄ±kesir</option>
                          <option>Bilecik</option>
                          <option>BingÃ¶l</option>
                          <option>Bitlis</option>
                          <option>Bolu</option>
                          <option>Burdur</option>
                          <option>Bursa</option>
                          <option>Ã‡anakkale</option>
                          <option>Ã‡ankÄ±rÄ±</option>
                          <option>Ã‡orum</option>
                          <option>Denizli</option>
                          <option>DiyarbakÄ±r</option>
                          <option>Edirne</option>
                          <option>ElazÄ±ÄŸ</option>
                          <option>Erzincan</option>
                          <option>Erzurum</option>
                          <option>EskiÅŸehir</option>
                          <option>Gaziantep</option>
                          <option>Giresun</option>
                          <option>GÃ¼mÃ¼ÅŸhane</option>
                          <option>HakkÃ¢ri</option>
                          <option>Hatay</option>
                          <option>Isparta</option>
                          <option>Mersin</option>
                          <option>Ä°stanbul</option>
                          <option>Ä°zmir</option>
                          <option>Kars</option>
                          <option>Kastamonu</option>
                          <option>Kayseri</option>
                          <option>KÄ±rklareli</option>
                          <option>KÄ±rÅŸehir</option>
                          <option>Kocaeli</option>
                          <option>Konya</option>
                          <option>KÃ¼tahya</option>
                          <option>Malatya</option>
                          <option>Manisa</option>
                          <option>KahramanmaraÅŸ</option>
                          <option>Mardin</option>
                          <option>MuÄŸla</option>
                          <option>MuÅŸ</option>
                          <option>NevÅŸehir</option>
                          <option>NiÄŸde</option>
                          <option>Ordu</option>
                          <option>Rize</option>
                          <option>Sakarya</option>
                          <option>Samsun</option>
                          <option>Siirt</option>
                          <option>Sinop</option>
                          <option>Sivas</option>
                          <option>TekirdaÄŸ</option>
                          <option>Tokat</option>
                          <option>Trabzon</option>
                          <option>Tunceli</option>
                          <option>Å�anlÄ±urfa</option>
                          <option>UÅŸak</option>
                          <option>Van</option>
                          <option>Yozgat</option>
                          <option>Zonguldak</option>
                          <option>Aksaray</option>
                          <option>Bayburt</option>
                          <option>Karaman</option>
                          <option>KÄ±rÄ±kkale</option>
                          <option>Batman</option>
                          <option>Å�Ä±rnak</option>
                          <option>BartÄ±n</option>
                          <option>Ardahan</option>
                          <option>IÄŸdÄ±r</option>
                          <option>Yalova</option>
                          <option>KarabÃ¼k</option>
                          <option>Kilis</option>
                          <option>Osmaniye</option>
                          <option>DÃ¼zce</option>
                        </select>
                      </div>
                      <div class="form-group col-lg-4">
                        <label for="citySelect">
                          <h5><b>Ä°lÃ§e</b></h5>
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
                          <h5><b>Ziyaret SÄ±klÄ±ÄŸÄ±</b></h5>
                        </label>
                        <select class="form-control" id="remi_freq" name="remi_freq" size="1" required>
                          <option value="" disabled>Ziyaret SÄ±klÄ±ÄŸÄ± Ayarla</option>
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
            </div><!--iÅŸletme_bilgileri end-->

            <!--OSGB Ã‡alÄ±ÅŸanlarÄ± -->
            <div class="tab-pane fade" id="osgb_calisanlar" role="tabpanel" aria-labelledby="oc-tab">
              <form action="index.php" method="POST">
                  <fieldset id="oc_form">
                    <!--Ä°sg UzmanlarÄ± -->
                    <div class="row">
                      <!--1. Ä°sg UzmanÄ± -->
                      <div class="col-lg-4">
                        <label for="uzman"><h5><b>1. Ä°sg UzmanÄ±</b></h5></label>
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
                            <option disabled selected>Ä°sg UzmanÄ± SeÃ§</option>
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

                      <!--2. Ä°sg UzmanÄ± -->
                      <div class="col-lg-4">
                        <label for="uzman_2"><h5><b>2.Ä°sg UzmanÄ±</b></h5></label>
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
                            <option disabled selected>Ä°sg UzmanÄ± SeÃ§</option>
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

                      <!--3. Ä°sg UzmanÄ± -->
                      <div class="col-lg-4">
                        <label for="uzman_3"><h5><b>3.Ä°sg UzmanÄ±</b></h5></label>
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
                            <option disabled selected>Ä°sg UzmanÄ± SeÃ§</option>
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

                    <!--Ä°ÅŸ Yeri Hekimleri-->
                    <div class="row">

                      <!--1 Ä°ÅŸ Yeri Hekimi -->
                      <div class="col-lg-4">
                        <label for="hekim"><h5><b>1. Ä°ÅŸ Yeri Hekimi</b></h5></label>
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
                          <option disabled selected>Ä°ÅŸ Yeri Hekimi SeÃ§</option>
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

                      <!--2. Ä°ÅŸ Yeri Hekimi -->
                      <div class="col-lg-4">
                        <label for="hekim_2">
                          <h5><b>2. Ä°ÅŸ Yeri Hekimi</b></h5>
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
                          <option disabled selected>Ä°ÅŸ Yeri Hekimi SeÃ§</option>
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

                      <!--3. Ä°ÅŸ Yeri Hekimi -->
                      <div class="col-lg-4">
                        <label for="hekim_3">
                          <h5><b>3. Ä°ÅŸ Yeri Hekimi</b></h5>
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
                          <option disabled selected>Ä°ÅŸ Yeri Hekimi SeÃ§</option>
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
                        <label for="saglÄ±k">
                          <h5><b>SaÄŸlÄ±k Personeli</b></h5>
                        </label>
                        <?php
                        $user_id = $company->saglÄ±k_p_id;
                        $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                        $sor->execute();
                        $saÄŸlÄ±kÃ§Ä±lar=$sor->fetchAll(PDO::FETCH_OBJ);
                        foreach ($saÄŸlÄ±kÃ§Ä±lar as $saÄŸlÄ±kÃ§Ä±) {
                            $first_ = $saÄŸlÄ±kÃ§Ä±->firstname;
                            $last_ = $saÄŸlÄ±kÃ§Ä±->lastname;
                        } ?>
                        <select class="form-control" id="saglÄ±k" name="saglÄ±k" size="1">
                          <?php
                          if ($company->saglÄ±k_p_id != 0) {
                              ?>
                          <option value="<?= $company->saglÄ±k_p_id ?>" selected><?= $first_." ".$last_ ?></option>
                          <?php
                          } else {
                              ?>
                          <option disabled selected>Ä°ÅŸ Yeri Hekimi SeÃ§</option>
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
                          <option disabled selected>Ofis Personeli SeÃ§</option>
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
                          <option disabled selected>Muhasebe Personeli SeÃ§</option>
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
                      <!--2. SaÄŸlÄ±k Personeli -->
                      <div class="col-lg-4">
                        <label for="saglÄ±k_2">
                          <h5><b>2.SaÄŸlÄ±k Personeli</b></h5>
                        </label>
                        <?php
                        $user_id = $company->saglÄ±k_p_id_2;
                        $sor=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$user_id'");
                        $sor->execute();
                        $saÄŸlÄ±kÃ§Ä±lar=$sor->fetchAll(PDO::FETCH_OBJ);
                        foreach ($saÄŸlÄ±kÃ§Ä±lar as $saÄŸlÄ±kÃ§Ä±) {
                            $first_ = $saÄŸlÄ±kÃ§Ä±->firstname;
                            $last_ = $saÄŸlÄ±kÃ§Ä±->lastname;
                        } ?>
                        <select class="form-control" id="saglÄ±k_2" name="saglÄ±k_2" size="1">
                          <?php
                          if ($company->saglÄ±k_p_id_2 != 0) {
                              ?>
                          <option value="<?= $company->saglÄ±k_p_id_2 ?>" selected><?= $first_." ".$last_ ?></option>
                          <?php
                          } else {
                              ?>
                          <option disabled selected>Ä°ÅŸ Yeri Hekimi SeÃ§</option>
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
                          <option disabled selected>Ofis Personeli SeÃ§</option>
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
                          <option disabled selected>Muhasebe Personeli SeÃ§</option>
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
                    <option value="81.22.03">81.22.03,Nesne veya binalarÄ±n (ameliyathaneler vb.) sterilizasyonu faaliyetleri.Binalar ile ilgili hizmetler ve Ã§evre dÃ¼zenlemesi faaliyetleri 3</option>
                    <option value="82.20.01">82.20.01,Ã‡aÄŸrÄ± merkezlerinin faaliyetleri  2</option>
                    <option value="86.90.17">86.90.17,Ä°nsan saÄŸlÄ±ÄŸÄ± hizmetleri 3</option>
                    <option value="85.59.16">85.59.16,Ã‡ocuk kulÃ¼plerinin faaliyetleri (6 yaÅŸ ve Ã¼zeri Ã§ocuklar iÃ§in) 1</option>
                    <option value="71.12.14">71.12.14,YapÄ± denetim kuruluÅŸlarÄ± 1</option>
                    <option value="56.10.21">56.10.21,Oturacak yeri olmayan fast-food (hamburger, sandviÃ§, tost vb.) satÄ±ÅŸ yerleri (bÃ¼feler dahil), al gÃ¶tÃ¼r tesisleri (iÃ§li pide ve lahmacun fÄ±rÄ±nlarÄ± hariÃ§) ve benzerleri tarafÄ±ndan saÄŸlanan diÄŸer yemek hazÄ±rlama ve sunum faaliyetleri 1</option>
                    <option value="56.10.20">56.10.20,Oturacak yeri olmayan iÃ§li pide ve lahmacun fÄ±rÄ±nlarÄ±nÄ±n faaliyetleri (al gÃ¶tÃ¼r tesisi olarak hizmet verenler) 1</option>
                    <option value="47.89.19">47.89.19,Seyyar olarak ve motorlu araÃ§larla diÄŸer mallarÄ±n perakende ticareti 1</option>
                    <option value="47.82.03">47.82.03,Seyyar olarak ve motorlu araÃ§larla tekstil, giyim eÅŸyasÄ± ve ayakkabÄ± perakende ticareti 1</option>
                    <option value="47.81.12">47.81.12,Seyyar olarak ve motorlu araÃ§larla gÄ±da Ã¼rÃ¼nleri ve iÃ§eceklerin (alkollÃ¼ iÃ§ecekler hariÃ§) perakende ticareti 1</option>
                    <option value="47.79.06">47.79.06,Belirli bir mala tahsis edilmiÅŸ maÄŸazalarda kullanÄ±lmÄ±ÅŸ giysiler ve aksesuarlarÄ±nÄ±n perakende ticareti 1</option>
                    <option value="45.20.09">45.20.09,Motorlu kara taÅŸÄ±tlarÄ±nÄ±n sadece boyanmasÄ± faaliyetleri 3</option>
                    <option value="25.99.90">25.99.90,BaÅŸka yerde sÄ±nÄ±flandÄ±rÄ±lmamÄ±ÅŸ diÄŸer fabrikasyon metal Ã¼rÃ¼nlerin imalatÄ± 2</option>
                    <option value="08.99.01">08.99.01,AÅŸÄ±ndÄ±rÄ±cÄ± (tÃ¶rpÃ¼leyici) materyaller (zÄ±mpara), amyant, silisli fosil artÄ±klar, arsenik cevherleri, sabuntaÅŸÄ± (talk) ve feldispat madenciliÄŸi (kuartz, mika, ÅŸist, talk, silis, sÃ¼nger taÅŸÄ±, asbest, doÄŸal korindon vb.) 3</option>
                    <option value="08.93.02">08.93.02,Deniz, gÃ¶l ve kaynak tuzu Ã¼retimi (tuzun yemeklik tuza dÃ¶nÃ¼ÅŸtÃ¼rÃ¼lmesi hariÃ§) 2</option>
                    <option value="23.99.07">23.99.07,AmyantlÄ± kaÄŸÄ±t imalatÄ± 3</option>
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
                    <label for="katip_is_yeri_id"><h4><b>Ä°SG-KATÄ°P Ä°ÅŸ Yeri ID</b></h4></label>
                    <input class="form-control" id="katip_is_yeri_id" name="katip_is_yeri_id" type="tel" maxlength="30" placeholder="Ä°SG-KATÄ°P Ä°ÅŸ Yeri ID" value="<?= $company->katip_is_yeri_id ?>">
                  </div>
                  <div class="col-6">
                    <label for="katip_kurum_id"><h4><b>Ä°SG-KATÄ°P Kurum ID</b></h4></label>
                    <input class="form-control" id="katip_kurum_id" name="katip_kurum_id" type="tel" maxlength="30" placeholder="Ä°SG-KATÄ°P Kurum ID" value="<?= $company->katip_kurum_id ?>">
                  </div>
                </div>
              </fieldset>
              </form>
            </div>

            <!--Ä°ÅŸletme Ã‡alÄ±ÅŸanlarÄ± -->
            <div class="tab-pane fade" id="isletme_calisanlar" role="tabpanel" aria-labelledby="ic-tab">
              <?php
                if (file_exists($isim."_calisanlar.xlsx") || file_exists($isim."_calisanlar.xls") || file_exists($isim."_calisanlar.ods")){
                  ?>
                  <button class="btn btn-primary" id="ic_form2" data-toggle="modal" data-target="#addWorker" data-whatever="@getbootstrap">Yeni Ã‡alÄ±ÅŸan Ekle</button>
                  <?php
                }
                else {
                  ?>
                <form method="POST" action="index.php" enctype="multipart/form-data" >
                  <fieldset id="ic_form1">
                    <label for="calisan_list"><b>Ã‡alÄ±ÅŸan Listesi YÃ¼kle-></b></label>
                    <input type="file" class="btn btn-light btn-sm" name="calisan_list" />
                    <input type="submit" class="btn btn-primary" name="calisan_yukle" value="YÃ¼kle"/>
                  </fieldset>
                </form>
                <?php
                  }
                   ?>
                  <input type="text" class="form-control" style="float:right;max-width:600px;" id="myInput" onkeyup="myFunction()" placeholder="Ã‡alÄ±ÅŸan AdÄ± ile ara...">
                  <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                    <table class="table table-striped table-bordered table-hover" id="dataTable">
                      <thead class="thead-dark">
                        <tr>
                          <th >Ã‡alÄ±ÅŸan AdÄ± SoyadÄ±</th>
                          <th>Pozisyonu</th>
                          <th>Cinsiyeti</th>
                          <th>T.C Kimlik No</th>
                          <th>Telefon No</th>
                          <th>E-mail</th>
                          <th>Ä°ÅŸe GiriÅŸ Tarihi</th>
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
                          <tr data-toggle="modal" data-target="#b<?= $key ?>" data-whatever="@getbootstrap" style="cursor: pointer;">
                            <td><?= $coworker->name ?></td>
                            <td><?= $coworker->position ?></td>
                            <td><?= $coworker->sex ?></td>
                            <td><?= $coworker->tc ?></td>
                            <td><?= $coworker->phone ?></td>
                            <td><?= $coworker->mail ?></td>
                            <td><?= $coworker->contract_date ?></td>
                            <form action="index.php" method="POST">
                                <input type="number" name="TCWillDelete" value="<?= $coworker->tc ?>" hidden readonly>
                                <td id="delete_worker_row"><button class="btn btn-danger" type="submit" name="deleteWorkerButton" id="deleteWorkerButton">Sil</button></td>
                            </form>
                          </tr>
                          <!-- Ã‡alÄ±ÅŸan DosyalarÄ± -->
                          <div class="modal fade" id="b<?= $key ?>" tabindex="-1" aria-labelledby="label" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                <div class="modal-header bg-light">
                                  <h5 class="modal-title" id="label"><b><?= $coworker->name." DosyalarÄ±"?></b></h5>
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
                                          <form method="POST" action="index.php" enctype="multipart/form-data" >
                                            <fieldset id="ic_form3">
                                              <label for="calisan_dosya"><b>Yeni Dosya YÃ¼kle-></b></label>
                                              <input name="cdir_name" type="tel" value="<?= $coworker->tc ?>" hidden>
                                              <input type="file" class="btn btn-light btn-sm" name="calisan_dosya" required />
                                              <input type="submit" class="btn btn-primary" name="calisan_dosya_yukle" value="YÃ¼kle"/>
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
                          <td><strong>Ã‡alÄ±ÅŸan AdÄ± SoyadÄ±</strong></td>
                          <td><strong>Pozisyonu</strong></td>
                          <td><strong>Cinsiyeti</strong></td>
                          <td><strong>T.C Kimlik No</strong></td>
                          <td><strong>Telefon No</strong></td>
                          <td><strong>E-mail</strong></td>
                          <td><strong>Ä°ÅŸe GiriÅŸ Tarihi</strong></td>
                          <td id="delete_footer"><strong>Sil</strong></td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
            </div>

            <!--Ä°ÅŸletme Takvimi -->
            <div class="tab-pane fade" id="isletme_takvim" role="tabpanel" aria-labelledby="it-tab">
              <div id="calendar" style="margin: auto;" class="col-centered"></div>
            </div>

            <!--Ä°ÅŸletme EkipmanlarÄ± -->
            <div class="tab-pane fade" id="isletme_ekipman" role="tabpanel" aria-labelledby="ie-tab">
              <button class="btn btn-primary" id="ie_button" data-toggle="modal" data-target="#addEquipment" data-whatever="@getbootstrap">Yeni Ekipman Ekle</button>
              <input type="text" class="form-control" style="float:right;max-width:600px;" id="myInput" onkeyup="myFunction()" placeholder="Ekipman AdÄ± ile ara...">
              <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                <table class="table table-striped table-bordered table-hover" id="dataTable">
                  <thead class="thead-dark">
                    <tr>
                      <th>Ekipman AdÄ±</th>
                      <th>EkipmanÄ±n KullanÄ±m AmacÄ±</th>
                      <th>Ekipman Kontrol SÄ±klÄ±ÄŸÄ±</th>
                      <th>KayÄ±t Tarihi</th>
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
                      <td><strong>Ekipman AdÄ±</strong></td>
                      <td><strong>EkipmanÄ±n KullanÄ±m AmacÄ±</strong></td>
                      <td><strong>EkipmaÄ±n Kontrol SÄ±klÄ±ÄŸÄ±</strong></td>
                      <td><strong>KayÄ±t Tarihi</strong></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>

            <!--Ä°ÅŸletme RaporlarÄ± -->
            <div class="tab-pane fade" id="isletme_rapor" role="tabpanel" aria-labelledby="ir-tab">
            	<button class="btn btn-primary" id="ir_form" data-toggle="modal" data-target="#addReport" data-whatever="@getbootstrap">Yeni Rapor HazÄ±rla</button>
            </div>

            <!--Tamamlanan Raporlar -->
            <div class="tab-pane fade" id="tamamlanan_rapor" role="tabpanel" aria-labelledby="tr-tab">
              tamamlanan_rapor
            </div>

            <!--Ziyaret RaporlarÄ± -->
            <div class="tab-pane fade" id="ziyaret_rapor" role="tabpanel" aria-labelledby="zr-tab">
              <button class="btn btn-primary" id="zr_form" data-toggle="modal" data-target="#addVisitReport" data-whatever="@getbootstrap">Yeni Ziyaret Raporu Ekle</button>
                <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                  <table class="table table-striped table-bordered table-hover table-sm" id="dataTable">
                    <thead class="thead-dark">
                      <tr>
                        <th>Dosya AdÄ±</th>
                        <th>Dosya Tarihi</th>
                        <th>Ä°ndir</th>
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
                              <td><?= $file_name[1] ?></td>
                              <td><?= $file_name[0] ?></td>
                              <td><input class="btn btn-success btn-sm" style="width:80px" type="button" value="Ä°ndir" onclick="window.location.href='ziyaret_raporlari/<?=$file?>';"/></td>
                            </tr>
                            <?php
                            }
                          }
                          ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td><strong>Dosya AdÄ±</strong></td>
                        <td><strong>Dosya Tarihi</strong></td>
                        <td><strong>Ä°ndir</strong></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
            </div>

            <!--Silinen Ã‡alÄ±ÅŸanlar -->
            <div class="tab-pane fade" id="silinen_calisanlar" role="tabpanel" aria-labelledby="tr-tab">
              <input type="text" class="form-control" style="float:right;max-width:600px;" id="myInput" onkeyup="myFunction()" placeholder="Ã‡alÄ±ÅŸan AdÄ± ile ara...">
              <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                <table class="table table-striped table-bordered table-hover" id="dataTable">
                  <thead class="thead-dark">
                    <tr>
                      <th>Ã‡alÄ±ÅŸan AdÄ± SoyadÄ±</th>
                      <th>Pozisyonu</th>
                      <th>Cinsiyeti</th>
                      <th>T.C Kimlik No</th>
                      <th>Telefon No</th>
                      <th>E-mail</th>
                      <th>Ä°ÅŸe GiriÅŸ Tarihi</th>
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
                      <tr data-toggle="modal" data-target="#c<?= $key ?>" data-whatever="@getbootstrap" style="cursor: pointer;">
                        <td><?= $coworker->name ?></td>
                        <td><?= $coworker->position ?></td>
                        <td><?= $coworker->sex ?></td>
                        <td><?= $coworker->tc ?></td>
                        <td><?= $coworker->phone ?></td>
                        <td><?= $coworker->mail ?></td>
                        <td><?= $coworker->contract_date ?></td>
                        <form action="index.php" method="POST">
                          <td><button class="btn btn-success" type="submit" name="recruitAgain">Geri Al</button></td>
                          <input type="number" name="TCWillRecruit" value="<?= $coworker->tc ?>" hidden readonly>
                        </form>
                      </tr>
                      <!-- Ã‡alÄ±ÅŸan DosyalarÄ± -->
                      <div class="modal fade" id="c<?= $key ?>" tabindex="-1" aria-labelledby="label" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content modal-lg">
                            <div class="modal-header bg-light">
                              <h5 class="modal-title" id="label"><b><?= $coworker->name." DosyalarÄ±"?></b></h5>
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
                                          <label for="calisan_dosya"><b>Yeni Dosya YÃ¼kle-></b></label>
                                          <input name="cdir_name" type="tel" value="<?= $coworker->tc ?>" hidden>
                                          <input type="file" class="btn btn-light btn-sm" name="calisan_dosya" />
                                          <input type="submit" class="btn btn-primary" name="calisan_dosya_yukle" value="YÃ¼kle"/>
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
                      <td><strong>Ã‡alÄ±ÅŸan AdÄ± SoyadÄ±</strong></td>
                      <td><strong>Pozisyonu</strong></td>
                      <td><strong>Cinsiyeti</strong></td>
                      <td><strong>T.C Kimlik No</strong></td>
                      <td><strong>Telefon No</strong></td>
                      <td><strong>E-mail</strong></td>
                      <td><strong>Ä°ÅŸe GiriÅŸ Tarihi</strong></td>
                      <td><strong>Geri Al</strong></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div><!-- tab content end -->
        </div><!-- card body end -->
        <form action="index.php" method="POST">
          <fieldset id="save_changes">
            <div class="card-footer bg-light border">
              <button class="btn btn-primary" name="gb_kaydet" id="gb_kaydet" type="submit" style="float:left; height:60px; width:300px;">BÃ¼tÃ¼n DeÄŸiÅŸiklikleri Kaydet*</button>
              <button class="btn btn-danger" name="isletme_sil" id="isletme_sil" type="submit" style="float:right; height:60px; width:300px;">Ä°ÅŸletmeyi Sil</button>
              <br><br><br>
              <p><b>*LÃ¼tfen iÅŸletmenizin bÃ¼tÃ¼n sayfalarÄ±ndaki deÄŸiÅŸikliklerinizi tamamladÄ±ktan sonra bu butonu kullanÄ±n.Bu buton her sayfa iÃ§in ayrÄ± ayrÄ± Ã§alÄ±ÅŸmaz!</b></p>
            </div>
          </fieldset>
        </form>
      </div> <!--card end-->

      <!-- Yeni Ã§alÄ±ÅŸan ekleme modal -->
      <div class="modal fade" id="addWorker" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header bg-light">
              <h5 class="modal-title" id="exampleModalLabel"><b>Yeni Ã‡alÄ±ÅŸan Ekle</b></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="index.php" method="POST">
                <div class="row">
                  <div class="col-6">
                    <label for="worker_name"><h5><b>Ã‡alÄ±ÅŸanÄ±n AdÄ±nÄ± ve SoyadÄ±nÄ± Giriniz</b></h5></label>
                    <input name="worker_name" id="worker_name" class="form-control" type="text" maxlength="100" placeholder="Ad Soyad" required>
                  </div>
                  <div class="col-6">
                    <label for="worker_tc"><h5><b>Ã‡alÄ±ÅŸanÄ±n T.C Kimlik NumarasÄ±nÄ± Giriniz</b></h5></label>
                    <input name="worker_tc" id="worker_tc" class="form-control" type="tel" maxlength="11" minlength="11" placeholder="T.C Kimlik No" required>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-6">
                    <label for="worker_mail"><h5><b>Ã‡alÄ±ÅŸanÄ±n E-mail adresini Giriniz</b></h5></label>
                    <input name="worker_mail" id="worker_mail" class="form-control" type="email" maxlength="100" placeholder="E-mail" required>
                  </div>
                  <div class="col-6">
                    <label for="worker_phone"><h5><b>Ã‡alÄ±ÅŸanÄ±n Telefon NumarasÄ±nÄ± Giriniz</b></h5></label>
                    <input name="worker_phone" id="worker_phone" class="form-control" type="tel" maxlength="11" minlength="11" placeholder="05XXXXXXXXX" required>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-6">
                    <label for="worker_position"><h5><b>Ã‡alÄ±ÅŸanÄ±n Posizyonu Giriniz</b></h5></label>
                    <input name="worker_position" id="worker_position" class="form-control" type="text" maxlength="100" placeholder="Pozisyon" required>
                  </div>
                  <div class="col-6">
                    <label for="worker_sex"><h5><b>Ã‡alÄ±ÅŸanÄ±nÄ±n Cinsiyetini Giriniz</b></h5></label>
                    <select name="worker_sex" id="worker_sex" class="form-control" required>
                      <option value="" disabled selected>Ã‡alÄ±ÅŸanÄ±n Cinsiyeti</option>
                      <option value="Erkek">Erkek</option>
                      <option value="KadÄ±n">KadÄ±n</option>
                    </select>
                  </div>
                </div>
                <br>
                <div class="row col-6">
                  <label for="worker_contract_date"><h5><b>Ã‡alÄ±ÅŸanÄ±n Ä°ÅŸe GiriÅŸ Tarihi</b></h5></label>
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
                <label for="title" class="col-sm-2 control-label">BaÅŸlÄ±k</label>
                <div class="col-sm-10">
                  <input type="text" name="title" class="form-control" id="title" placeholder="BaÅŸlÄ±k">
                </div>
                </div>
                <div class="form-group">
                <label for="description" class="col-sm-2 control-label">AÃ§Ä±klama</label>
                <div class="col-sm-10">
                  <input type="text" name="description" class="form-control" id="description" placeholder="AÃ§Ä±klama">
                </div>
                </div>
                <div class="form-group">
                <label for="color" class="col-sm-2 control-label">Renk</label>
                <div class="col-sm-10">
                  <select name="color" class="form-control" id="color">
                    <option style="color:#0071c5;" value="#0071c5">Lacivert</option>
                    <option style="color:#40E0D0;" value="#40E0D0">Turkuaz</option>
                    <option style="color:#008000;" value="#008000">YeÅŸil</option>
                    <option style="color:#FFD700;" value="#FFD700">SarÄ±</option>
                    <option style="color:#FF8C00;" value="#FF8C00">Turuncu</option>
                    <option style="color:#FF0000;" value="#FF0000">KÄ±rmÄ±zÄ±</option>
                    <option style="color:#000;" value="#000">Siyah</option>
                  </select>
                </div>
                </div>
                <div class="container">
                <div class="row">
                <div class="form-group">
                <label for="start" class="col-sm-12 control-label">BaÅŸlangÄ±Ã§ Tarihi</label>
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

      <!-- KayÄ±tlÄ± etkinliÄŸi dÃ¼zenle modal -->
      <div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form class="form-horizontal" method="POST" action="../../calendar/core/editEventTitle.php">
              <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel">EtkinliÄŸi DÃ¼zenle</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
                <input name="event_company_name" id="event_company_name" value="<?= $isim ?>" hidden readonly>
                <div class="form-group">
                <label for="title" class="col-sm-2 control-label">BaÅŸlÄ±k</label>
                <div class="col-sm-10">
                  <input type="text" name="title" class="form-control" id="title" placeholder="BaÅŸlÄ±k">
                </div>
                </div>
                <div class="form-group">
                <label for="description" class="col-sm-2 control-label">AÃ§Ä±klama</label>
                <div class="col-sm-10">
                  <input type="text" name="description" class="form-control" id="description" placeholder="AÃ§Ä±klama">
                </div>
                </div>
                <div class="form-group">
                <label for="color" class="col-sm-2 control-label">Renk</label>
                <div class="col-sm-10">
                  <select name="color" class="form-control" id="color">
                    <option style="color:#0071c5;" value="#0071c5">Lacivert</option>
                    <option style="color:#40E0D0;" value="#40E0D0">Turkuaz</option>
                    <option style="color:#008000;" value="#008000">YeÅŸil</option>
                    <option style="color:#FFD700;" value="#FFD700">SarÄ±</option>
                    <option style="color:#FF8C00;" value="#FF8C00">Turuncu</option>
                    <option style="color:#FF0000;" value="#FF0000">KÄ±rmÄ±zÄ±</option>
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
              <form action="index.php" method="POST" enctype="multipart/form-data">
                <div class="row col-12">
                  <label for="visit_report_name"><h5><b>Raporun AdÄ±nÄ± Giriniz</b></h5></label>
                  <input name="visit_report_name" id="visit_report_name" class="form-control" type="text" maxlength="20" placeholder="Rapor AdÄ±" required>
                </div>
                <br>
                <div class="row col-12">
                  <label for="visit_report_date"><h5><b>Raporun Tarihini Giriniz</b></h5></label>
                  <input name="visit_report_date" id="visit_report_date" class="form-control" type="date" required>
                </div>
                <br>
                <div class="modal-footer">
                  <input type="file" class="btn btn-sm" name="ziyaret_dosyasÄ±" id="ziyaret_dosyasÄ±" style="margin-right:auto;" required>
                  <button type="submit" class="btn btn-primary" name="ziyaret_dosyasÄ±_yukle" id="ziyaret_dosyasÄ±_yukle">YÃ¼kle</button>
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
            <form action="index.php" method="POST">
              <div class="modal-body">
                <div class="row col-12">
                  <label for="eq_name"><h5><b>EkipmanÄ±n AdÄ±nÄ± Giriniz</b></h5></label>
                  <input name="eq_name" id="eq_name" class="form-control" type="text" maxlength="100" placeholder="Ekipman AdÄ±" required>
                </div>
                <br>
                <div class="row col-12">
                  <label for="eq_purpose"><h5><b>EkipmanÄ±n KullanÄ±m AmacÄ±nÄ± Giriniz</b></h5></label>
                  <input name="eq_purpose" id="eq_purpose" class="form-control" type="text" required>
                </div>
                <br>
                <div class="row col-12">
                  <label for="eq_freq"><h5><b>EkipmanÄ±n DÃ¼zenli Kontrol AralÄ±ÄŸÄ±nÄ± Giriniz</b></h5></label>
                  <select name="eq_freq" id="eq_freq" class="form-control" required>
                    <option value="" selected disabled>Kontrol AralÄ±ÄŸÄ±</option>
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
	
	  <!-- Yeni Rapor Ekle  -->
      <div class="modal fade" id="addReport" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header bg-light">
              <h5 class="modal-title" id="exampleModalLabel"><b>Yeni Rapor HazÄ±rla</b></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="index.php" method="POST">
              <div class="modal-body">
                <div class="row col-12">
                  <label for="eq_freq"><h5><b>Rapor SeÃ§</b></h5></label>
                  <select name="eq_freq" id="eq_freq" class="form-control" required>
                    <option value="" selected disabled>Rapor SeÃ§</option>
                    <option value="katip_sozlesme">Ä°SG Katip SÃ¶zleÅŸmeleri</option>
                    <option value="yangÄ±n">YangÄ±n Tatbikat Raporu</option>
                    <option value="egitim_plan">YÄ±llÄ±k EÄŸitim PlanÄ±</option>
                    <option value="calisma_plan">YÄ±llÄ±k Ã‡alÄ±ÅŸma PlanÄ±(50 Ã¼stÃ¼)</option>
                    <option value="takip_liste">KlasÃ¶r Takip Listesi</option>
                    <option value="tabela_liste">Tabela Listeleri</option>
                    <optgroup label="<h2>Risk Analizi</h2>">
                        <option value="ekip_atamasi">Ekip AtamasÄ±</option>
                        <option value="risk_basla">Risk DeÄŸerlendirme BaÅŸlanmasÄ±</option>
                        <option value="ust_yonetim">Ãœst YÃ¶netime Sunum</option>	
                        <option value="risk_tablo">Risk Analiz Tablosu</option>	
                    	<option value="covid19">Covid 19 Risk DeÄŸerlendirme Raporu</option>
                    	<option value="sonuc">SonuÃ§ Raporu</option>
                    </optgroup>
					<optgroup label="Acil Durum PlanÄ±">
                    	<option value="acil_plan_kapak">Acil Durum Eylem PlanÄ± Kapak</option>
                    	<option value="acil_plan">Acil Durum Eylem PlanÄ±</option>
                    	<option value="acil_plan_sema">Acil Durum Å�emalarÄ±</option>
                    </optgroup>
                    <optgroup label="SaÄŸlÄ±k GÃ¼venlik PlanÄ±">
                    	<option value="sgp_kapak">SGP Kapak</option>
                    	<option value="sg_plan">SaÄŸlÄ±k GÃ¼venlik PlanÄ±</option>
                    	<option value="hazÄ±rlÄ±k">HazÄ±rlÄ±k KoordinatÃ¶rÃ¼</option>
                    	<option value="uygulama">Uygulama KoordinatÃ¶rÃ¼</option>
                    </optgroup>
                    <option value="acil_ekip">Acil Durum MÃ¼dahale Ekipleri</option>
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
    </div> <!--container end-->
    <footer class="bg-white sticky-footer">
        <div class="container my-auto">
          <div class="text-center my-auto copyright"><span>Copyright Â© Ã–zgÃ¼rOSGB 2020</span></div>
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
    if ($giriÅŸ_auth != 0 && $giriÅŸ_auth != 1 && $giriÅŸ_auth != 2 && $giriÅŸ_auth != 3) {
   ?>
    <script>
      $('#gb_form').prop('disabled', true);
      $('#oc_form').prop('disabled', true);
      $('#db_form').prop('disabled', true);
      $('#zr_form').prop('hidden', true);
      $('#ie_button').prop('hidden', true);
      $('#sc-tab').prop('hidden', true);
      $('#deleteWorkerButton').prop('disabled', true);
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
      Adana: ["AladaÄŸ", "Ceyhan", "Ã‡ukurova", "Feke", "Ä°mamoÄŸlu", "KaraisalÄ±", "KarataÅŸ", "Kozan", "PozantÄ±", "Saimbeyli", "SarÄ±Ã§am", "Seyhan", "Tufanbeyli", "YumurtalÄ±k", "YÃ¼reÄŸir"],
      AdÄ±yaman: ["Besni", "Ã‡elikhan", "Gerger", "GÃ¶lbaÅŸÄ±", "Kahta", "Merkez", "Samsat", "Sincik", "Tut"],
      Afyonkarahisar: ["BaÅŸmakÃ§Ä±", "Bayat", "Bolvadin", "Ã‡ay", "Ã‡obanlar", "DazkÄ±rÄ±", "Dinar", "EmirdaÄŸ", "Evciler", "Hocalar", "Ä°hsaniye", "Ä°scehisar", "KÄ±zÄ±lÃ¶ren", "Merkez", "SandÄ±klÄ±", "SinanpaÅŸa", "SultandaÄŸÄ±", "Å�uhut"],
      AÄŸrÄ±: ["Diyadin", "DoÄŸubayazÄ±t", "EleÅŸkirt", "Hamur", "Merkez", "Patnos", "TaÅŸlÄ±Ã§ay", "Tutak"],
      Amasya: ["GÃ¶ynÃ¼cek", "GÃ¼mÃ¼ÅŸhacÄ±kÃ¶y", "HamamÃ¶zÃ¼", "Merkez", "Merzifon", "Suluova", "TaÅŸova"],
      Ankara: ["AltÄ±ndaÄŸ", "AyaÅŸ", "Bala", "BeypazarÄ±", "Ã‡amlÄ±dere", "Ã‡ankaya", "Ã‡ubuk", "ElmadaÄŸ", "GÃ¼dÃ¼l", "Haymana", "Kalecik", "KÄ±zÄ±lcahamam", "NallÄ±han", "PolatlÄ±", "Å�ereflikoÃ§hisar", "Yenimahalle", "GÃ¶lbaÅŸÄ±", "KeÃ§iÃ¶ren", "Mamak", "Sincan",
        "Kazan", "Akyurt", "Etimesgut", "Evren", "Pursaklar"
      ],
      Antalya: ["Akseki", "Alanya", "ElmalÄ±", "Finike", "GazipaÅŸa", "GÃ¼ndoÄŸmuÅŸ", "KaÅŸ", "Korkuteli", "Kumluca", "Manavgat", "Serik", "Demre", "Ä°bradÄ±", "Kemer", "Aksu", "DÃ¶ÅŸemealtÄ±", "Kepez", "KonyaaltÄ±", "MuratpaÅŸa"],
      Artvin: ["ArdanuÃ§", "Arhavi", "Merkez", "BorÃ§ka", "Hopa", "Å�avÅŸat", "Yusufeli", "Murgul"],
      AydÄ±n: ["Merkez", "BozdoÄŸan", "Efeler", "Ã‡ine", "Germencik", "Karacasu", "KoÃ§arlÄ±", "KuÅŸadasÄ±", "Kuyucak", "Nazilli", "SÃ¶ke", "Sultanhisar", "Yenipazar", "Buharkent", "Ä°ncirliova", "Karpuzlu", "KÃ¶ÅŸk", "Didim"],
      BalÄ±kesir: ["AltÄ±eylÃ¼l", "AyvalÄ±k", "Merkez", "Balya", "BandÄ±rma", "BigadiÃ§", "Burhaniye", "Dursunbey", "Edremit", "Erdek", "GÃ¶nen", "Havran", "Ä°vrindi", "Karesi", "Kepsut", "Manyas", "SavaÅŸtepe", "SÄ±ndÄ±rgÄ±", "GÃ¶meÃ§", "Susurluk", "Marmara"],
      Bilecik: ["Merkez", "BozÃ¼yÃ¼k", "GÃ¶lpazarÄ±", "Osmaneli", "Pazaryeri", "SÃ¶ÄŸÃ¼t", "Yenipazar", "Ä°nhisar"],
      BingÃ¶l: ["Merkez", "GenÃ§", "KarlÄ±ova", "KiÄŸÄ±", "Solhan", "AdaklÄ±", "Yayladere", "Yedisu"],
      Bitlis: ["Adilcevaz", "Ahlat", "Merkez", "Hizan", "Mutki", "Tatvan", "GÃ¼roymak"],
      Bolu: ["Merkez", "Gerede", "GÃ¶ynÃ¼k", "KÄ±brÄ±scÄ±k", "Mengen", "Mudurnu", "Seben", "DÃ¶rtdivan", "YeniÃ§aÄŸa"],
      Burdur: ["AÄŸlasun", "Bucak", "Merkez", "GÃ¶lhisar", "Tefenni", "YeÅŸilova", "KaramanlÄ±", "Kemer", "AltÄ±nyayla", "Ã‡avdÄ±r", "Ã‡eltikÃ§i"],
      Bursa: ["Gemlik", "Ä°negÃ¶l", "Ä°znik", "Karacabey", "Keles", "Mudanya", "MustafakemalpaÅŸa", "Orhaneli", "Orhangazi", "YeniÅŸehir", "BÃ¼yÃ¼korhan", "HarmancÄ±k", "NilÃ¼fer", "Osmangazi", "YÄ±ldÄ±rÄ±m", "GÃ¼rsu", "Kestel"],
      Ã‡anakkale: ["AyvacÄ±k", "BayramiÃ§", "Biga", "Bozcaada", "Ã‡an", "Merkez", "Eceabat", "Ezine", "Gelibolu", "GÃ¶kÃ§eada", "Lapseki", "Yenice"],
      Ã‡ankÄ±rÄ±: ["Merkez", "Ã‡erkeÅŸ", "Eldivan", "Ilgaz", "KurÅŸunlu", "Orta", "Å�abanÃ¶zÃ¼", "YapraklÄ±", "Atkaracalar", "KÄ±zÄ±lÄ±rmak", "BayramÃ¶ren", "Korgun"],
      Ã‡orum: ["Alaca", "Bayat", "Merkez", "Ä°skilip", "KargÄ±", "MecitÃ¶zÃ¼", "OrtakÃ¶y", "OsmancÄ±k", "Sungurlu", "BoÄŸazkale", "UÄŸurludaÄŸ", "Dodurga", "LaÃ§in", "OÄŸuzlar"],
      Denizli: ["AcÄ±payam", "Buldan", "Ã‡al", "Ã‡ameli", "Ã‡ardak", "Ã‡ivril", "Merkez", "Merkezefendi", "Pamukkale", "GÃ¼ney", "Kale", "SaraykÃ¶y", "Tavas", "BabadaÄŸ", "Bekilli", "Honaz", "Serinhisar", "Baklan", "BeyaÄŸaÃ§", "Bozkurt"],
      DiyarbakÄ±r: ["KocakÃ¶y", "Ã‡ermik", "Ã‡Ä±nar", "Ã‡Ã¼ngÃ¼ÅŸ", "Dicle", "Ergani", "Hani", "Hazro", "Kulp", "Lice", "Silvan", "EÄŸil", "BaÄŸlar", "KayapÄ±nar", "Sur", "YeniÅŸehir", "Bismil"],
      Edirne: ["Merkez", "Enez", "Havsa", "Ä°psala", "KeÅŸan", "LalapaÅŸa", "MeriÃ§", "UzunkÃ¶prÃ¼", "SÃ¼loÄŸlu"],
      ElazÄ±ÄŸ: ["AÄŸÄ±n", "Baskil", "Merkez", "KarakoÃ§an", "Keban", "Maden", "Palu", "Sivrice", "ArÄ±cak", "KovancÄ±lar", "Alacakaya"],
      Erzincan: ["Ã‡ayÄ±rlÄ±", "Merkez", "Ä°liÃ§", "Kemah", "Kemaliye", "Refahiye", "Tercan", "ÃœzÃ¼mlÃ¼", "Otlukbeli"],
      Erzurum: ["AÅŸkale", "Ã‡at", "HÄ±nÄ±s", "Horasan", "Ä°spir", "KarayazÄ±", "Narman", "Oltu", "Olur", "Pasinler", "Å�enkaya", "Tekman", "Tortum", "KaraÃ§oban", "Uzundere", "Pazaryolu", "KÃ¶prÃ¼kÃ¶y", "PalandÃ¶ken", "Yakutiye", "Aziziye"],
      EskiÅŸehir: ["Ã‡ifteler", "Mahmudiye", "MihalÄ±Ã§Ã§Ä±k", "SarÄ±cakaya", "Seyitgazi", "Sivrihisar", "Alpu", "Beylikova", "Ä°nÃ¶nÃ¼", "GÃ¼nyÃ¼zÃ¼", "Han", "Mihalgazi", "OdunpazarÄ±", "TepebaÅŸÄ±"],
      Gaziantep: ["Araban", "Ä°slahiye", "Nizip", "OÄŸuzeli", "Yavuzeli", "Å�ahinbey", "Å�ehitkamil", "KarkamÄ±ÅŸ", "NurdaÄŸÄ±"],
      Giresun: ["Alucra", "Bulancak", "Dereli", "Espiye", "Eynesil", "Merkez", "GÃ¶rele", "KeÅŸap", "Å�ebinkarahisar", "Tirebolu", "Piraziz", "YaÄŸlÄ±dere", "Ã‡amoluk", "Ã‡anakÃ§Ä±", "DoÄŸankent", "GÃ¼ce"],
      GÃ¼mÃ¼ÅŸhane: ["Merkez", "Kelkit", "Å�iran", "Torul", "KÃ¶se", "KÃ¼rtÃ¼n"],
      Hakkari: ["Ã‡ukurca", "Merkez", "Å�emdinli", "YÃ¼ksekova"],
      Hatay: ["AltÄ±nÃ¶zÃ¼", "Arsuz", "Defne", "DÃ¶rtyol", "Hassa", "Antakya", "Ä°skenderun", "KÄ±rÄ±khan", "Payas", "ReyhanlÄ±", "SamandaÄŸ", "YayladaÄŸÄ±", "Erzin", "Belen", "Kumlu"],
      Isparta: ["Atabey", "EÄŸirdir", "Gelendost", "Merkez", "KeÃ§iborlu", "Senirkent", "SÃ¼tÃ§Ã¼ler", "Å�arkikaraaÄŸaÃ§", "Uluborlu", "YalvaÃ§", "Aksu", "GÃ¶nen", "YeniÅŸarbademli"],
      Mersin: ["Anamur", "Erdemli", "GÃ¼lnar", "Mut", "Silifke", "Tarsus", "AydÄ±ncÄ±k", "BozyazÄ±", "Ã‡amlÄ±yayla", "Akdeniz", "Mezitli", "Toroslar", "YeniÅŸehir"],
      Ä°stanbul: ["Adalar", "BakÄ±rkÃ¶y", "BeÅŸiktaÅŸ", "Beykoz", "BeyoÄŸlu", "Ã‡atalca", "EyÃ¼p", "Fatih", "GaziosmanpaÅŸa", "KadÄ±kÃ¶y", "Kartal", "SarÄ±yer", "Silivri", "Å�ile", "Å�iÅŸli", "ÃœskÃ¼dar", "Zeytinburnu", "BÃ¼yÃ¼kÃ§ekmece", "KaÄŸÄ±thane", "KÃ¼Ã§Ã¼kÃ§ekmece",
        "Pendik", "Ãœmraniye", "BayrampaÅŸa", "AvcÄ±lar", "BaÄŸcÄ±lar", "BahÃ§elievler", "GÃ¼ngÃ¶ren", "Maltepe", "Sultanbeyli", "Tuzla", "Esenler", "ArnavutkÃ¶y", "AtaÅŸehir", "BaÅŸakÅŸehir", "BeylikdÃ¼zÃ¼", "Ã‡ekmekÃ¶y", "Esenyurt", "Sancaktepe", "Sultangazi"
      ],
      Ä°zmir: ["AliaÄŸa", "BayÄ±ndÄ±r", "Bergama", "Bornova", "Ã‡eÅŸme", "Dikili", "FoÃ§a", "Karaburun", "KarÅŸÄ±yaka", "KemalpaÅŸa", "KÄ±nÄ±k", "Kiraz", "Menemen", "Ã–demiÅŸ", "Seferihisar", "SelÃ§uk", "Tire", "TorbalÄ±", "Urla", "BeydaÄŸ", "Buca", "Konak",
        "Menderes", "BalÃ§ova", "Ã‡iÄŸli", "Gaziemir", "NarlÄ±dere", "GÃ¼zelbahÃ§e", "BayraklÄ±", "KarabaÄŸlar"
      ],
      Kars: ["ArpaÃ§ay", "Digor", "KaÄŸÄ±zman", "Merkez", "SarÄ±kamÄ±ÅŸ", "Selim", "Susuz", "Akyaka"],
      Kastamonu: ["Abana", "AraÃ§", "Azdavay", "Bozkurt", "Cide", "Ã‡atalzeytin", "Daday", "Devrekani", "Ä°nebolu", "Merkez", "KÃ¼re", "TaÅŸkÃ¶prÃ¼", "Tosya", "Ä°hsangazi", "PÄ±narbaÅŸÄ±", "Å�enpazar", "AÄŸlÄ±", "DoÄŸanyurt", "HanÃ¶nÃ¼", "Seydiler"],
      Kayseri: ["BÃ¼nyan", "Develi", "Felahiye", "Ä°ncesu", "PÄ±narbaÅŸÄ±", "SarÄ±oÄŸlan", "SarÄ±z", "Tomarza", "YahyalÄ±", "YeÅŸilhisar", "AkkÄ±ÅŸla", "Talas", "Kocasinan", "Melikgazi", "HacÄ±lar", "Ã–zvatan"],
      KÄ±rklareli: ["Babaeski", "DemirkÃ¶y", "Merkez", "KofÃ§az", "LÃ¼leburgaz", "PehlivankÃ¶y", "PÄ±narhisar", "Vize"],
      KÄ±rÅŸehir: ["Ã‡iÃ§ekdaÄŸÄ±", "Kaman", "Merkez", "Mucur", "AkpÄ±nar", "AkÃ§akent", "Boztepe"],
      Kocaeli: ["Gebze", "GÃ¶lcÃ¼k", "KandÄ±ra", "KaramÃ¼rsel", "KÃ¶rfez", "Derince", "BaÅŸiskele", "Ã‡ayÄ±rova", "DarÄ±ca", "DilovasÄ±", "Ä°zmit", "Kartepe"],
      Konya: ["AkÅŸehir", "BeyÅŸehir", "BozkÄ±r", "Cihanbeyli", "Ã‡umra", "DoÄŸanhisar", "EreÄŸli", "Hadim", "IlgÄ±n", "KadÄ±nhanÄ±", "KarapÄ±nar", "Kulu", "SarayÃ¶nÃ¼", "SeydiÅŸehir", "Yunak", "AkÃ¶ren", "AltÄ±nekin", "Derebucak", "HÃ¼yÃ¼k", "Karatay", "Meram",
        "SelÃ§uklu", "TaÅŸkent", "AhÄ±rlÄ±", "Ã‡eltik", "Derbent", "Emirgazi", "GÃ¼neysÄ±nÄ±r", "HalkapÄ±nar", "TuzlukÃ§u", "YalÄ±hÃ¼yÃ¼k"
      ],
      KÃ¼tahya: ["AltÄ±ntaÅŸ", "DomaniÃ§", "Emet", "Gediz", "Merkez", "Simav", "TavÅŸanlÄ±", "Aslanapa", "DumlupÄ±nar", "HisarcÄ±k", "Å�aphane", "Ã‡avdarhisar", "Pazarlar"],
      Malatya: ["AkÃ§adaÄŸ", "Arapgir", "Arguvan", "Darende", "DoÄŸanÅŸehir", "Hekimhan", "Merkez", "PÃ¼tÃ¼rge", "YeÅŸilyurt", "Battalgazi", "DoÄŸanyol", "Kale", "Kuluncak", "YazÄ±han"],
      Manisa: ["Akhisar", "AlaÅŸehir", "Demirci", "GÃ¶rdes", "KÄ±rkaÄŸaÃ§", "Kula", "Merkez", "Salihli", "SarÄ±gÃ¶l", "SaruhanlÄ±", "Selendi", "Soma", "Å�ehzadeler", "Yunusemre", "Turgutlu", "Ahmetli", "GÃ¶lmarmara", "KÃ¶prÃ¼baÅŸÄ±"],
      KahramanmaraÅŸ: ["AfÅŸin", "AndÄ±rÄ±n", "DulkadiroÄŸlu", "OnikiÅŸubat", "Elbistan", "GÃ¶ksun", "Merkez", "PazarcÄ±k", "TÃ¼rkoÄŸlu", "Ã‡aÄŸlayancerit", "EkinÃ¶zÃ¼", "Nurhak"],
      Mardin: ["Derik", "KÄ±zÄ±ltepe", "Artuklu", "Merkez", "MazÄ±daÄŸÄ±", "Midyat", "Nusaybin", "Ã–merli", "Savur", "DargeÃ§it", "YeÅŸilli"],
      MuÄŸla: ["Bodrum", "DatÃ§a", "Fethiye", "KÃ¶yceÄŸiz", "Marmaris", "MenteÅŸe", "Milas", "Ula", "YataÄŸan", "Dalaman", "Seydikemer", "Ortaca", "KavaklÄ±dere"],
      MuÅŸ: ["BulanÄ±k", "Malazgirt", "Merkez", "Varto", "HaskÃ¶y", "Korkut"],
      NevÅŸehir: ["Avanos", "Derinkuyu", "GÃ¼lÅŸehir", "HacÄ±bektaÅŸ", "KozaklÄ±", "Merkez", "ÃœrgÃ¼p", "AcÄ±gÃ¶l"],
      NiÄŸde: ["Bor", "Ã‡amardÄ±", "Merkez", "UlukÄ±ÅŸla", "Altunhisar", "Ã‡iftlik"],
      Ordu: ["AkkuÅŸ", "AltÄ±nordu", "AybastÄ±", "Fatsa", "GÃ¶lkÃ¶y", "Korgan", "Kumru", "Mesudiye", "PerÅŸembe", "Ulubey", "Ãœnye", "GÃ¼lyalÄ±", "GÃ¼rgentepe", "Ã‡amaÅŸ", "Ã‡atalpÄ±nar", "Ã‡aybaÅŸÄ±", "Ä°kizce", "KabadÃ¼z", "KabataÅŸ"],
      Rize: ["ArdeÅŸen", "Ã‡amlÄ±hemÅŸin", "Ã‡ayeli", "FÄ±ndÄ±klÄ±", "Ä°kizdere", "Kalkandere", "Pazar", "Merkez", "GÃ¼neysu", "DerepazarÄ±", "HemÅŸin", "Ä°yidere"],
      Sakarya: ["AkyazÄ±", "Geyve", "Hendek", "Karasu", "Kaynarca", "Sapanca", "Kocaali", "Pamukova", "TaraklÄ±", "Ferizli", "KarapÃ¼rÃ§ek", "SÃ¶ÄŸÃ¼tlÃ¼", "AdapazarÄ±", "Arifiye", "Erenler", "Serdivan"],
      Samsun: ["AlaÃ§am", "Bafra", "Ã‡arÅŸamba", "Havza", "Kavak", "Ladik", "Terme", "VezirkÃ¶prÃ¼", "AsarcÄ±k", "OndokuzmayÄ±s", "SalÄ±pazarÄ±", "TekkekÃ¶y", "AyvacÄ±k", "Yakakent", "Atakum", "Canik", "Ä°lkadÄ±m"],
      Siirt: ["Baykan", "Eruh", "Kurtalan", "Pervari", "Merkez", "Å�irvan", "Tillo"],
      Sinop: ["AyancÄ±k", "Boyabat", "DuraÄŸan", "Erfelek", "Gerze", "Merkez", "TÃ¼rkeli", "Dikmen", "SaraydÃ¼zÃ¼"],
      Sivas: ["DivriÄŸi", "Gemerek", "GÃ¼rÃ¼n", "Hafik", "Ä°mranlÄ±", "Kangal", "Koyulhisar", "Merkez", "SuÅŸehri", "Å�arkÄ±ÅŸla", "YÄ±ldÄ±zeli", "Zara", "AkÄ±ncÄ±lar", "AltÄ±nyayla", "DoÄŸanÅŸar", "GÃ¶lova", "UlaÅŸ"],
      TekirdaÄŸ: ["Ã‡erkezkÃ¶y", "Ã‡orlu", "Ergene", "Hayrabolu", "Malkara", "MuratlÄ±", "Saray", "SÃ¼leymanpaÅŸa", "KapaklÄ±", "Å�arkÃ¶y", "MarmaraereÄŸlisi"],
      Tokat: ["Almus", "Artova", "Erbaa", "Niksar", "ReÅŸadiye", "Merkez", "Turhal", "Zile", "Pazar", "YeÅŸilyurt", "BaÅŸÃ§iftlik", "Sulusaray"],
      Trabzon: ["AkÃ§aabat", "AraklÄ±", "Arsin", "Ã‡aykara", "MaÃ§ka", "Of", "Ortahisar", "SÃ¼rmene", "Tonya", "VakfÄ±kebir", "Yomra", "BeÅŸikdÃ¼zÃ¼", "Å�alpazarÄ±", "Ã‡arÅŸÄ±baÅŸÄ±", "DernekpazarÄ±", "DÃ¼zkÃ¶y", "Hayrat", "KÃ¶prÃ¼baÅŸÄ±"],
      Tunceli: ["Ã‡emiÅŸgezek", "Hozat", "Mazgirt", "NazÄ±miye", "OvacÄ±k", "Pertek", "PÃ¼lÃ¼mÃ¼r", "Merkez"],
      Å�anlÄ±urfa: ["AkÃ§akale", "Birecik", "Bozova", "CeylanpÄ±nar", "EyyÃ¼biye", "Halfeti", "Haliliye", "Hilvan", "KarakÃ¶prÃ¼", "Siverek", "SuruÃ§", "ViranÅŸehir", "Harran"],
      UÅŸak: ["Banaz", "EÅŸme", "KarahallÄ±", "SivaslÄ±", "Ulubey", "Merkez"],
      Van: ["BaÅŸkale", "Ã‡atak", "ErciÅŸ", "GevaÅŸ", "GÃ¼rpÄ±nar", "Ä°pekyolu", "Muradiye", "Ã–zalp", "TuÅŸba", "BahÃ§esaray", "Ã‡aldÄ±ran", "Edremit", "Saray"],
      Yozgat: ["AkdaÄŸmadeni", "BoÄŸazlÄ±yan", "Ã‡ayÄ±ralan", "Ã‡ekerek", "SarÄ±kaya", "Sorgun", "Å�efaatli", "YerkÃ¶y", "Merkez", "AydÄ±ncÄ±k", "Ã‡andÄ±r", "KadÄ±ÅŸehri", "Saraykent", "YenifakÄ±lÄ±"],
      Zonguldak: ["Ã‡aycuma", "Devrek", "EreÄŸli", "Merkez", "AlaplÄ±", "GÃ¶kÃ§ebey"],
      Aksaray: ["AÄŸaÃ§Ã¶ren", "Eskil", "GÃ¼laÄŸaÃ§", "GÃ¼zelyurt", "Merkez", "OrtakÃ¶y", "SarÄ±yahÅŸi"],
      Bayburt: ["Merkez", "AydÄ±ntepe", "DemirÃ¶zÃ¼"],
      Karaman: ["Ermenek", "Merkez", "AyrancÄ±", "KazÄ±mkarabekir", "BaÅŸyayla", "SarÄ±veliler"],
      KÄ±rÄ±kkale: ["Delice", "Keskin", "Merkez", "Sulakyurt", "BahÅŸili", "BalÄ±ÅŸeyh", "Ã‡elebi", "KarakeÃ§ili", "YahÅŸihan"],
      Batman: ["Merkez", "BeÅŸiri", "GercÃ¼ÅŸ", "Kozluk", "Sason", "Hasankeyf"],
      Å�Ä±rnak: ["BeytÃ¼ÅŸÅŸebap", "Cizre", "Ä°dil", "Silopi", "Merkez", "Uludere", "GÃ¼Ã§lÃ¼konak"],
      BartÄ±n: ["Merkez", "KurucaÅŸile", "Ulus", "Amasra"],
      Ardahan: ["Merkez", "Ã‡Ä±ldÄ±r", "GÃ¶le", "Hanak", "Posof", "Damal"],
      IÄŸdÄ±r: ["AralÄ±k", "Merkez", "Tuzluca", "Karakoyunlu"],
      Yalova: ["Merkez", "AltÄ±nova", "Armutlu", "Ã‡Ä±narcÄ±k", "Ã‡iftlikkÃ¶y", "Termal"],
      KarabÃ¼k: ["Eflani", "Eskipazar", "Merkez", "OvacÄ±k", "Safranbolu", "Yenice"],
      Kilis: ["Merkez", "Elbeyli", "Musabeyli", "Polateli"],
      Osmaniye: ["BahÃ§e", "Kadirli", "Merkez", "DÃ¼ziÃ§i", "Hasanbeyli", "Sumbas", "Toprakkale"],
      DÃ¼zce: ["AkÃ§akoca", "Merkez", "YÄ±ÄŸÄ±lca", "Cumayeri", "GÃ¶lyaka", "Ã‡ilimli", "GÃ¼mÃ¼ÅŸova", "KaynaÅŸlÄ±"]
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
      monthNames: ['Ocak','Å�ubat','Mart','Nisan','MayÄ±s','Haziran','Temmuz','AÄŸustos','EylÃ¼l','Ekim','KasÄ±m','AralÄ±k'],
      monthNamesShort: ['Ocak','Å�ubat','Mart','Nisan','MayÄ±s','Haziran','Temmuz','AÄŸustos','EylÃ¼l','Ekim','KasÄ±m','AralÄ±k'],
      dayNames: ['Pazar','Pazartesi','SalÄ±','Ã‡arÅŸamba','PerÅŸembe','Cuma','Cumartesi'],
      dayNamesShort: ['Pazar','Pazartesi','SalÄ±','Ã‡arÅŸamba','PerÅŸembe','Cuma','Cumartesi'],
      editable:true,
      buttonText: {
            today:    'BugÃ¼n',
            month:    'Ay',
            week:     'Hafta',
            day:      'GÃ¼n',
            list:     'Liste',
            listMonth: 'AylÄ±k Liste',
            listYear: 'YÄ±llÄ±k Liste',
            listWeek: 'HaftalÄ±k Liste',
            listDay: 'GÃ¼nlÃ¼k Liste'
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
