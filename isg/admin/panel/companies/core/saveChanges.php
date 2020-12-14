<?php
require '../../../connect.php';
if (isset($_POST['kaydet'])) {
    $comp_type = !empty($_POST['comp_type']) ? trim($_POST['comp_type']) : null;
    $name = $_POST['company_name'];
    $email = !empty($_POST['mail']) ? trim($_POST['mail']) : null;
    $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : null;
    $is_veren = !empty($_POST['is_veren']) ? trim($_POST['is_veren']) : null;
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
    $changer = $_POST['changer'];

    $uzman_id_2 = !empty($_POST['uzman_2']) ? trim($_POST['uzman_2']) : 0;
    $uzman_id_3 = !empty($_POST['uzman_3']) ? trim($_POST['uzman_3']) : 0;

    $hekim_id_2 = !empty($_POST['hekim_2']) ? trim($_POST['hekim_2']) : 0;
    $hekim_id_3 = !empty($_POST['hekim_3']) ? trim($_POST['hekim_3']) : 0;

    $saglık_p_id_2 = !empty($_POST['saglık_2']) ? trim($_POST['saglık_2']) : 0;

    $ofis_p_id_2 = !empty($_POST['ofis_2']) ? trim($_POST['ofis_2']) : 0;

    $muhasebe_p_id_2 = !empty($_POST['muhasebe_2']) ? trim($_POST['muhasebe_2']) : 0;

    $nace_kodu = !empty($_POST['nace_kodu']) ? trim($_POST['nace_kodu']) : 0;
    $mersis_no = !empty($_POST['mersis_no']) ? trim($_POST['mersis_no']) : 0;
    $sgk_sicil = !empty($_POST['sgk_sicil']) ? trim($_POST['sgk_sicil']) : 0;
    $vergi_no = !empty($_POST['vergi_no']) ? trim($_POST['vergi_no']) : 0;
    $vergi_dairesi = !empty($_POST['vergi_dairesi']) ? trim($_POST['vergi_dairesi']) : null;
    $katip_is_yeri_id = !empty($_POST['katip_is_yeri_id']) ? trim($_POST['katip_is_yeri_id']) : 0;
    $katip_kurum_id = !empty($_POST['katip_kurum_id']) ? trim($_POST['katip_kurum_id']) : 0;

    $isletme_id = !empty($_POST['isletme_id']) ? trim($_POST['isletme_id']) : 0;
    $isletme_id_2 = !empty($_POST['isletme_id_2']) ? trim($_POST['isletme_id_2']) : 0;
    $isletme_id_3 = !empty($_POST['isletme_id_3']) ? trim($_POST['isletme_id_3']) : 0;

    $sql = "INSERT INTO `coop_companies`
    (`comp_type`, `name`, `mail`, `phone`, `is_veren`, `address`, `city`, `town`, `contract_date`, `uzman_id`, `uzman_id_2`, `uzman_id_3`, `hekim_id`,`hekim_id_2`,
       `hekim_id_3`,`saglık_p_id`,`saglık_p_id_2`,`ofis_p_id`,`ofis_p_id_2`,`muhasebe_p_id`,`muhasebe_p_id_2`, `change`, `remi_freq`,`changer`,
     `nace_kodu`,`mersis_no`,`sgk_sicil`,`vergi_no`,`vergi_dairesi`,`katip_is_yeri_id`,`katip_kurum_id`,
   `isletme_id`, `isletme_id_2`, `isletme_id_3`)
   VALUES
   ('$comp_type', '$name', '$email', '$phone', '$is_veren', '$address', '$city', '$town', '$contract_date', '$uzman_id', '$uzman_id_2', '$uzman_id_3',
     '$hekim_id', '$hekim_id_2', '$hekim_id_3', '$saglık_p_id', '$saglık_p_id_2', '$ofis_p_id', '$ofis_p_id_2',
     '$muhasebe_p_id', '$muhasebe_p_id_2', '0', '$remi_freq','$changer',
     '$nace_kodu','$mersis_no','$sgk_sicil','$vergi_no','$vergi_dairesi','$katip_is_yeri_id','$katip_kurum_id',
   '$isletme_id', '$isletme_id_2', '$isletme_id_3')";
    $stmt = $pdo->prepare($sql);

    $result = $stmt->execute();
    if ($result) {
      header("Location: ../$name/index.php?tab=genel_bilgiler");
      }
}

if (isset($_POST['sil'])) {
    $comp_type = !empty($_POST['comp_type']) ? trim($_POST['comp_type']) : null;
    $name = $_POST['company_name'];
    $email = !empty($_POST['mail']) ? trim($_POST['mail']) : null;
    $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : null;
    $is_veren = !empty($_POST['is_veren']) ? trim($_POST['is_veren']) : null;
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
    $changer = $_POST['changer'];

    $uzman_id_2 = !empty($_POST['uzman_2']) ? trim($_POST['uzman_2']) : 0;
    $uzman_id_3 = !empty($_POST['uzman_3']) ? trim($_POST['uzman_3']) : 0;

    $hekim_id_2 = !empty($_POST['hekim_2']) ? trim($_POST['hekim_2']) : 0;
    $hekim_id_3 = !empty($_POST['hekim_3']) ? trim($_POST['hekim_3']) : 0;

    $saglık_p_id_2 = !empty($_POST['saglık_2']) ? trim($_POST['saglık_2']) : 0;

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
    (`comp_type`, `name`, `mail`, `phone`, `is_veren`, `address`, `city`, `town`, `contract_date`, `uzman_id`, `uzman_id_2`, `uzman_id_3`, `hekim_id`,`hekim_id_2`,
       `hekim_id_3`,`saglık_p_id`,`saglık_p_id_2`,`ofis_p_id`,`ofis_p_id_2`,`muhasebe_p_id`,`muhasebe_p_id_2`, `change`, `remi_freq`,`changer`,
     `nace_kodu`,`mersis_no`,`sgk_sicil`,`vergi_no`,`vergi_dairesi`,`katip_is_yeri_id`,`katip_kurum_id`)
   VALUES
   ('$comp_type', '$name', '$email', '$phone', '$is_veren', '$address', '$city', '$town', '$contract_date', '$uzman_id', '$uzman_id_2', '$uzman_id_3',
     '$hekim_id', '$hekim_id_2', '$hekim_id_3', '$saglık_p_id', '$saglık_p_id_2', '$ofis_p_id', '$ofis_p_id_2',
     '$muhasebe_p_id', '$muhasebe_p_id_2', '2', '$remi_freq','$changer',
     '$nace_kodu','$mersis_no','$sgk_sicil','$vergi_no','$vergi_dairesi','$katip_is_yeri_id','$katip_kurum_id')";
    $stmt = $pdo->prepare($sql);

    $result = $stmt->execute();
    if ($result) {
        header("Location: ../$name/index.php?tab=genel_bilgiler");
        }
  }
?>
