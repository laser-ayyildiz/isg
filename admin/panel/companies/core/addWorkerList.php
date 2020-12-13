<?php
require '../../../connect.php';
require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
date_default_timezone_set('Europe/Istanbul');
if(isset($_FILES['calisan_list']) && isset($_POST['calisan_yukle'])){
    $dir =  dirname(__DIR__);
    $company_id = $_POST['company_id'];
    $company_name = $_POST['company_name'];
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
          $fileNameNew = $company_name."_calisanlar".".".$fileActualExt;
          $fileDestination = $dir.'/'.$company_name.'/'.$fileNameNew;
          $basarili = move_uploaded_file($_FILES['calisan_list']['tmp_name'],$fileDestination);
          if ($basarili) {
            $ext = mb_convert_case($fileActualExt, MB_CASE_TITLE, "UTF-8");

            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($ext);
            $reader->setReadDataOnly(TRUE);
            $spreadsheet = $reader->load($fileDestination);

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

                $sql = "INSERT INTO `coop_workers`(`name`, `position`, `sex`, `tc`, `phone`, `mail`, `contract_date`,`company_id`)
                VALUES('$value1', '$value2', '$value3', '$value4', '$value5', '$value6', '$value7','$company_id')";
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute();
                if ($result) {
                  mkdir("../$company_name/$value4", 0777);
            }
          }
        }
          header("Location: ../$company_name/index.php?tab=isletme_calisanlar");
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
?>
