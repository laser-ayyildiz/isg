<?php
require '../../../connect.php';
$başlangıç=$pdo->prepare("SELECT * FROM `users` WHERE `id` = '$id'");
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

$yetki=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `name` = '$isim' AND `change` = 1");
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

    $yetkili_isletme_id = $yet->isletme_id;
    $yetkili_isletme_id_2 = $yet->isletme_id_2;
    $yetkili_isletme_id_3 = $yet->isletme_id_3;
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
    }elseif ($yetkili_isletme_id == $id) {
        echo "";
    }elseif ($yetkili_isletme_id_2 == $id) {
        echo "";
    }elseif ($yetkili_isletme_id_3 == $id) {
        echo "";
    } else {
        header('Location: ../../403.php');
    }
}
$sql_event = "SELECT * FROM `events` WHERE `company_id` = '$company_id'";

$req = $auth->prepare($sql_event);
$req->execute();

$events = $req->fetchAll();
?>
