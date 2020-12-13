<?php
require '../../../connect.php';
if (isset($_POST['cu_kayıt'])) {
    $company_id = !empty($_POST['company_id']) ? trim($_POST['company_id']) : null;
    $company_name = !empty($_POST['company_name']) ? trim($_POST['company_name']) : null;
    $firstname = !empty($_POST['cu_firstname']) ? trim($_POST['cu_firstname']) : null;
    $lastname = !empty($_POST['cu_lastname']) ? trim($_POST['cu_lastname']) : null;
    $username = !empty($_POST['cu_username']) ? trim($_POST['cu_username']) : null;
    $phone = !empty($_POST['cu_phone']) ? trim($_POST['cu_phone']) : null;
    $kod = md5(rand(0, 9999999999));

    $sql = "SELECT COUNT(username) AS num FROM `users` WHERE username = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['num'] > 0) {
        ?>
        <script type="text/javascript">
            var retVal = confirm("Bu mail adresiyle kayıtlı kullanıcı bulunmaktadır");
            window.location.replace("../<?=$company_name?>/index.php?tab=genel_bilgiler");
        </script>
      <?php
    } else {
        $min = 15263548;
        $max = 94523675;
        $pass = rand($min, $max);
        include("../../../register/mail/PHPMailerAutoload.php");
        $mail = new PHPMailer;
        $mail->IsSMTP();
        //$mail->SMTPDebug = 1; // hata ayiklama: 1 = hata ve mesaj, 2 = sadece mesaj
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl'; // Güvenli baglanti icin ssl normal baglanti icin tls
        $mail->Host = "smtp.gmail.com"; // Mail sunucusuna ismi
        $mail->Port = 465; // Gucenli baglanti icin 465 Normal baglanti icin 587
        $mail->IsHTML(true);
        $mail->SetLanguage("tr", "phpmailer/language");
        $mail->CharSet  ="utf-8";
        $mail->Username = "osgbdeneme@gmail.com"; // Mail adresimizin kullanicı adi
        $mail->Password = "deneme123"; // Mail adresimizin sifresi
        $mail->SetFrom("osgbdeneme@gmail.com", "Özgür OSGB"); // Mail attigimizda gorulecek ismimiz
        $mail->AddAddress($username); // Maili gonderecegimiz kisi yani alici
        $mail->addReplyTo($username, $firstname, $lastname);
        $mail->Subject = "Kullanıcı Kaydı"; // Konu basligi
        $mail->Body = "<div style='background:#eee;padding:5px;margin:5px;width:300px;'> eposta : ".$username."</div> <br />
         <p><b>Merhaba $firstname, Özgür OSGB'ye kullanıcı olarak kaydınız yapılmıştır.<br>Lütfen öncelikle aşağıdaki linki tarayıcınızda açarak üyeliğinizi onaylayın</b></p>
         <br>http://142.93.97.101/isg/admin/register/validate.php?username=".$username."&valid_code=".$kod."
         <h4><b>Giriş bilgileriniz:</b></h4>
         <h5><b>Kullanıcı adı:</b></h5> $username
         <h5><b>Şifre:</b></h5> $pass
         <h4><b>Kullanıcı bilgileriniz:</b></h4>
         <h5><b>Ad:</b></h5> $firstname
         <h5><b>Soyad:</b></h5> $lastname
         <h5><b>Telefon No:</b></h5> $phone
        "; // Mailin icerigi
        if (!$mail->Send()) {
            ?>
            <script type="text/javascript">
                var retVal = confirm("Onay maili gönderilemedi. Lütfen bizimle iletişime geçin");
                window.location.replace("../<?=$company_name?>/index.php?tab=genel_bilgiler");
            </script>
          <?php
        } else {
            $passwordHash =md5(md5($pass));
            $add_user = "INSERT INTO `users` (`firstname`, `lastname`, `username`, `phone`, `password`, `valid_code`, `auth`)
           VALUES ('$firstname', '$lastname', '$username', '$phone', '$passwordHash', '$kod', 7)";
            $stmt2 = $pdo->prepare($add_user);
            $result = $stmt2->execute();

            $sorgu5=$pdo->prepare("SELECT * FROM `users` WHERE `username` = '$username'");
            $sorgu5->execute();
            $users5=$sorgu5-> fetchAll(PDO::FETCH_OBJ);
            foreach ($users5 as $user5) {
                $id = $user5->id;
            }
            $sorgu6=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `id` = '$company_id'");
            $sorgu6->execute();
            $comps=$sorgu6-> fetchAll(PDO::FETCH_OBJ);
            foreach ($comps as $comp) {
                $id1 = $comp->isletme_id;
                $id2 = $comp->isletme_id_2;
                $id3 = $comp->isletme_id_3;
            }
            if ($id1 == 0) {
              $addtocomp = "UPDATE `coop_companies` SET `isletme_id` = '$id' WHERE `id` = '$company_id'";
              $stmt3 = $pdo->prepare($addtocomp);
              $result2 = $stmt3->execute();
            }
            elseif ($id2 == 0) {
              $addtocomp = "UPDATE `coop_companies` SET `isletme_id_2` = '$id' WHERE `id` = '$company_id'";
              $stmt3 = $pdo->prepare($addtocomp);
              $result2 = $stmt3->execute();
            }
            else {
              $addtocomp = "UPDATE `coop_companies` SET `isletme_id_3` = '$id' WHERE `id` = '$company_id'";
              $stmt3 = $pdo->prepare($addtocomp);
              $result2 = $stmt3->execute();
            }

            if (!$result && !$result2) {?>
            <script type="text/javascript">
                var retVal = confirm("Veritabanına kaydınız yapılamadı. Lütfen bizimle iletişime geçin");
                window.location.replace("../<?=$company_name?>/index.php?tab=genel_bilgiler");
            </script>
          <?php
            }
            else {
           ?>
           <script type="text/javascript">
               var retVal = confirm("Kullanıcı başarıyla oluşturuldu!");
               window.location.replace("../<?=$company_name?>/index.php?tab=genel_bilgiler");
           </script>

            <?php
            }
        }
  }
}
?>
