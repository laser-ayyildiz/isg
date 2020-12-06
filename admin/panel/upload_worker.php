<?php
if (($_FILES['my_file']['name']!="")){
// Where the file is going to be stored
 $isim = $_POST['company_name'];
 $target_dir = "companies/$isim/";
 $file = $_FILES['my_file']['name'];
 $path = pathinfo($file);
 $filename = $isim."_calisanlar";
 $ext = $path['extension'];
 $temp_name = $_FILES['my_file']['tmp_name'];
 $path_filename_ext = $target_dir.$filename.".".$ext;

// Check if file already exists
if (file_exists($path_filename_ext)) {
 echo "Sorry, file already exists.";
 }else{
 move_uploaded_file($temp_name,$path_filename_ext);
 header("Location: companies/$isim/$isim.php");
 echo "<div class='alert alert-primary alert-dismissible fade show' style=' margin-bottom: 0 !important;' role='alert'>
   <strong>İşletmenizde yaptığınız değişiklikler firma yöneticinize iletilmiştir. İstek onaylandıktan sonra firma değişiklikler uygulanacaktır. Onaylanana kadar yaptığınız işlemler kayıt edilmez!</strong>
   <button type='button' class='close' data-dismiss='alert' aria-label='Close' padding='auto'>
     <span aria-hidden='true'>&times;</span>
   </button>
 </div>";
 }
}
?>
