<?php
require '../../../connect.php';
date_default_timezone_set('Europe/Istanbul');
if (isset($_FILES['ziyaret_dosyası']) && isset($_POST['ziyaret_dosyası_yukle'])) {
  $company_name = $_POST['company_name'];
  $dir =  dirname(__DIR__);
  $rapor_adi = $_POST['visit_report_name'];
  $rapor_tarihi = date('d-m-Y', strtotime($_POST["visit_report_date"]));

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
        $fileNameNew = $rapor_adi."_".$rapor_tarihi."_".date('G:i:s')."_";
        $fileDestination = $dir.'/'.$company_name.'/ziyaret_raporlari/'.$fileNameNew.".".$fileActualExt;

        move_uploaded_file($_FILES['ziyaret_dosyası']['tmp_name'],$fileDestination);
        header("Location: ../$company_name/index.php?tab=ziyaret_rapor");
      }else{
        ?>
        <script type="text/javascript">
            var retVal = confirm("Dosya yüklenirken bir hata ile karşılaşıldı!Lütfen daha sonra tekrar deneiyiniz! Sorun devam ederse sistem yöneticinizle görüşün!");
            window.location.replace("../<?=$company_name?>/index.php?tab=ziyaret_raporlari");
        </script>
        <?php
      }
  }else{
    ?>
    <script type="text/javascript">
        var retVal = confirm("Dosya türü uygun değil. Lütfen 'xlsx','xls','odt','odf','ods','pdf','docx','doc','txt','jpg','jpeg','png','ppt','pptx' türündeki dosyaları yükleyin!!");
        window.location.replace("../<?=$company_name?>/index.php?tab=ziyaret_raporlari");
    </script>
    <?php
  }
}
?>
