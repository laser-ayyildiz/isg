<?php
require '../../../connect.php';
if (isset($_FILES['calisan_dosya']) && isset($_POST['calisan_dosya_yukle'])) {
  $company_name = $_POST['company_name'];
  $dir =  dirname(__DIR__);
  $worker_tc = !empty($_POST['coworker_tc']) ? trim($_POST['coworker_tc']) : null;
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
        $fileDestination = $dir.'/'.$company_name.'/'.$worker_tc.'/'.$fileNameNew;
        move_uploaded_file($_FILES['calisan_dosya']['tmp_name'],$fileDestination);
        header("Location: ../$company_name/index.php?tab=isletme_calisanlar");
      }else{
        ?>
        <script type="text/javascript">
            var retVal = confirm("Dosya yüklenirken bir hata ile karşılaşıldı!Lütfen daha sonra tekrar deneiyiniz! Sorun devam ederse sistem yöneticinizle görüşün!");
            window.location.replace("../<?=$company_name?>/index.php?tab=isletme_calisanlar");
        </script>
        <?php
      }
  }else{
    ?>
    <script type="text/javascript">
        var retVal = confirm("Dosya türü uygun değil. Lütfen 'xlsx','xls','odt','odf','ods','pdf','docx','doc','txt','jpg','jpeg','png','ppt','pptx' türündeki dosyaları yükleyin!");
        window.location.replace("../<?=$company_name?>/index.php?tab=isletme_calisanlar");
    </script>
    <?php
  }
}
?>
