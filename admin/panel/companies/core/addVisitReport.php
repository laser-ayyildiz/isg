<?php
require '../../../connect.php';
if (isset($_FILES['ziyaret_dosyası']) && isset($_POST['ziyaret_dosyası_yukle'])) {
  $company_name = $_POST['company_name'];
  $dir =  dirname(__DIR__);
  $rapor_adi = $_POST['visit_report_name'];
  $rapor_tarihi = $_POST['visit_report_date'];
  $dosya_id = rand(0, 9999);
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
        $fileDestination = $dir.'/'.$company_name.'/ziyaret_raporlari/'.$fileNameNew.".".$fileActualExt;
        while (file_exists("../$company_name/ziyaret_raporlari/".$fileNameNew.".".$fileActualExt)) {
          $dosya_id = rand(0, 9999999999);
          $fileNameNew = $rapor_tarihi."_".$rapor_adi."_".$dosya_id;
        }

        move_uploaded_file($_FILES['ziyaret_dosyası']['tmp_name'],$fileDestination);
        header("Location: ../$company_name/index.php?tab=ziyaret_rapor");
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
?>
