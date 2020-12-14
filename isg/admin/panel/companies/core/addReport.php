<?php
require '../../vendor/autoload.php';
require '../../../connect.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
date_default_timezone_set('Europe/Istanbul');
if (isset($_POST['addReport_sub'])) {

  $report_name = $_POST['new_report'];
  $company_name = $_POST['company_name'];

  $sorgu = $pdo->prepare("SELECT * FROM `coop_companies` WHERE `name` = '$company_name' AND `change` = '1'");
  $sorgu->execute();
  $companies=$sorgu->fetchAll(PDO::FETCH_OBJ);
  foreach ($companies as $company) {
    $danger_type = $company->danger_type;
    $uzman_id = $company->uzman_id;
    $hekim_id = $company->hekim_id;
    $is_veren = $company->is_veren;
    $sgk_sicil = $company->sgk_sicil;
    $address = $company->address;
    $nace_kodu = $company->nace_kodu;
  }
  $sorgu2 = $pdo->prepare("SELECT * FROM `users` WHERE `id` = '$uzman_id'");
  $sorgu2->execute();
  $uzmanlar=$sorgu2->fetchAll(PDO::FETCH_OBJ);
  foreach ($uzmanlar as $uzman) {
    $u_first = $uzman->firstname;
    $u_last = $uzman->lastname;
    $u_username = $uzman->username;
  }
  $sorgu3 = $pdo->prepare("SELECT * FROM `users` WHERE `id` = '$hekim_id'");
  $sorgu3->execute();
  $hekimler=$sorgu3->fetchAll(PDO::FETCH_OBJ);
  foreach ($hekimler as $hekim) {
    $h_first = $hekim->firstname;
    $h_last = $hekim->lastname;
    $h_username = $hekim->username;
  }
  $sorgu4 = $pdo->prepare("SELECT * FROM `osgb_workers` WHERE `mail` = '$u_username'");
  $sorgu4->execute();
  $uzmanlar=$sorgu4->fetchAll(PDO::FETCH_OBJ);
  foreach ($uzmanlar as $uzman) {
    $u_tc = $uzman->tc;
  }
  $sorgu5 = $pdo->prepare("SELECT * FROM `osgb_workers` WHERE `mail` = '$h_username'");
  $sorgu5->execute();
  $hekimler=$sorgu5->fetchAll(PDO::FETCH_OBJ);
  foreach ($hekimler as $hekim) {
    $h_tc = $hekim->tc;
  }

  if ($report_name == 'egitim_plan') {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('../custom_reports/2-YILLIK EĞİTİM PLANI/YILLIK EĞİTİM PLANI.xls');
    $future = new DateTime(date('d-m-Y'));
    if ($danger_type == 3) {
      $future->modify('+3 year');
      $saat = 16;
    }
    elseif ($danger_type == 2) {
      $future->modify('+2 year');
      $saat = 12;
    }
    elseif ($danger_type == 1) {
      $future->modify('+1 year');
      $saat = 8;
    }
    $today = date('d-m-Y');
    $future = $future->format('d-m-Y');
    $worksheet = $spreadsheet->getActiveSheet();
    $worksheet->getCell('E2')->setValue($company_name);
    $worksheet->getCell('O2')->setValue("Hazırlanma Tarihi: $today \n Geçerlilik Tarihi: $future");
    $worksheet->getCell('O4')->setValue("SGK Sicil No\n$company->sgk_sicil");
    $worksheet->getCell('N7')->setValue("$saat saat");
    $worksheet->getCell('B33')->setValue(" $u_first $u_last \n $u_tc");
    $worksheet->getCell('H33')->setValue(" $h_first $h_last \n $h_tc");
    $worksheet->getCell('L33')->setValue(" $company->is_veren");

    $file_name = 'Yıllık Eğitim Planı_'.date('d-m-Y_G:i:s').'_.xls';
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
    $writer->save("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'calisma_plan') {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('../custom_reports/3-YILLIK ÇALIŞMA PLANI/YILLIK ÇALIŞMA(50 ÜSTÜ).xlsx');
    $future = new DateTime(date('d-m-Y'));
    $future->modify('+1 year');

    $today = date('d-m-Y');
    $future = $future->format('d-m-Y');
    $worksheet = $spreadsheet->getActiveSheet();
    $worksheet->getCell('G3')->setValue($company_name);
    $worksheet->getCell('AQ3')->setValue("Hazırlanma Tarihi: $today\nGeçerlilik Tarihi: $future");
    $worksheet->getCell('AQ6')->setValue("SGK Sicil No\n$company->sgk_sicil");

    $file_name = 'Yıllık Çalışma Planı_'.date('d-m-Y_G:i:s').'_.xlsx';
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");

  }

  elseif ($report_name == 'takip_liste') {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('../custom_reports/30-KLASÖR TAKİP LİSTESİ/İSG KLASÖRÜ TAKİP LİSTESİ.xlsx');
    $file_name = 'İSG Klasörü Takip Listesi_'.date('d-m-Y_G:i:s').'_.xlsx';
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'İçindekiler') {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $today = date('d-m-Y');
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../custom_reports/içindekiler/icindekiler.docx');
    $templateProcessor->setValue(array('{{company_name}}','{{tarih}}','{{sgk_sicil}}','{{is_veren}}','{{adres}}','{{hekim}}','{{uzman}}'), array("$company_name","Tarih:\n$today","SGK Sicil No:\n$sgk_sicil","$is_veren","$address","$h_first $h_last","$u_first $u_last"));
    $file_name = 'İçindekiler_'.date('d-m-Y_G:i:s').'_.docx';
    $templateProcessor->saveAs("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'yangın') {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('../custom_reports/yangın/YANGIN TATBİKATI KATILIM FÖYÜ.xls');
    $file_name = 'Yangın Tatbikatı Katılım Föyü_'.date('d-m-Y_G:i:s').'_.xls';
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
    $writer->save("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'risk_analizi_amac') {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $future = new DateTime(date('d-m-Y'));
    if ($danger_type == 3) {
      $future->modify('+3 year');
    }
    elseif ($danger_type == 2) {
      $future->modify('+2 year');
    }
    elseif ($danger_type == 1) {
      $future->modify('+1 year');
    }
    $today = date('d-m-Y');
    $future = $future->format('d-m-Y');
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../custom_reports/7-RİSK ANALİZİ/risk_analizi_amac.docx');
    $templateProcessor->setValue(array('{{company_name}}','{{tarih}}','{{future}}','{{is_veren}}','{{hekim}}','{{uzman}}'), array("$company_name","$today","$future","$is_veren","$h_first $h_last","$u_first $u_last"));
    $file_name = 'Risk Analizi Amaç_'.date('d-m-Y_G:i:s').'_.docx';
    $templateProcessor->saveAs("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");

  }

  elseif ($report_name == 'ekip_atamasi') {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $today = date('d-m-Y');
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../custom_reports/7-RİSK ANALİZİ/1- RİSK DEĞERLENDİRME EKİBİ ATAMASI/2- EKİP ATAMASI.docx');
    $templateProcessor->setValue(array('{{company_name}}','{{tarih}}','{{sgk_sicil}}','{{is_veren}}','{{hekim}}','{{uzman}}'), array("$company_name","$today","$sgk_sicil","$is_veren","$h_first $h_last","$u_first $u_last"));
    $file_name = 'Ekip Ataması_'.date('d-m-Y_G:i:s').'_.docx';
    $templateProcessor->saveAs("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'risk_basla') {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $today = date('d-m-Y');
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../custom_reports/7-RİSK ANALİZİ/1- RİSK DEĞERLENDİRME EKİBİ ATAMASI/risk_degerlendirmesi_baslanmasi.docx');
    $templateProcessor->setValue(array('{{company_name}}','{{tarih}}','{{is_veren}}','{{hekim}}','{{uzman}}'), array("$company_name","$today","$is_veren","$h_first $h_last","$u_first $u_last"));
    $file_name = 'Risk Değerlendirme Başlanması_'.date('d-m-Y_G:i:s').'_.docx';
    $templateProcessor->saveAs("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'ust_yonetim') {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $today = date('d-m-Y');
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../custom_reports/7-RİSK ANALİZİ/1- RİSK DEĞERLENDİRME EKİBİ ATAMASI/ust_yonetime_sunum.docx');
    $templateProcessor->setValue(array('{{company_name}}','{{tarih}}','{{is_veren}}','{{uzman}}','{{sgk_sicil}}'), array("$company_name","$today","$is_veren","$u_first $u_last","$sgk_sicil"));
    $file_name = 'Üst Yönetime Sunum_'.date('d-m-Y_G:i:s').'_.docx';
    $templateProcessor->saveAs("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'sonuc') {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $future = new DateTime(date('d-m-Y'));
    if ($danger_type == 3) {
      $future->modify('+3 year');
    }
    elseif ($danger_type == 2) {
      $future->modify('+2 year');
    }
    elseif ($danger_type == 1) {
      $future->modify('+1 year');
    }
    $today = date('d-m-Y');
    $future = $future->format('d-m-Y');
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../custom_reports/7-RİSK ANALİZİ/sonuc.docx');
    $templateProcessor->setValue(array('{{company_name}}','{{tarih}}','{{is_veren}}','{{uzman}}','{{sgk_sicil}}','{{future}}','{{hekim}}'), array("$company_name","$today","$is_veren","$u_first $u_last","$sgk_sicil","$future","$h_first $h_last"));
    $file_name = 'Sonuç_'.date('d-m-Y_G:i:s').'_.docx';
    $templateProcessor->saveAs("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");

  }
  elseif ($report_name == 'adep_kapak') {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $today = date('d-m-Y');
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../custom_reports/7-RİSK ANALİZİ/COVİD-19/ADEP/adep_kapak.docx');
    $templateProcessor->setValue(array('{{tarih}}','{{is_veren}}','{{uzman}}','{{hekim}}'), array("$today","$is_veren","$u_first $u_last","$h_first $h_last"));
    $file_name = 'Covid 19 ADEP Kapak_'.date('d-m-Y_G:i:s').'_.docx';
    $templateProcessor->saveAs("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");

  }

  elseif ($report_name == 'adep_tablo') {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('../custom_reports/7-RİSK ANALİZİ/COVİD-19/ADEP/COVİD-19 ADEP.xlsx');
    $worksheet = $spreadsheet->getActiveSheet();

    $file_name = 'Covid 19 ADEP Tablo_'.date('d-m-Y_G:i:s').'_.xls';
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'vaka_sema') {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../custom_reports/7-RİSK ANALİZİ/COVİD-19/ADEP/özgür Corona Virüs Vakaya Müdahale Şeması.docx');
    $templateProcessor->setValue(array('{{is_veren}}','{{uzman}}','{{hekim}}'), array("$is_veren","$u_first $u_last","$h_first $h_last"));
    $file_name = 'Vakaya Müdahale Şeması_'.date('d-m-Y_G:i:s').'_.docx';
    $templateProcessor->saveAs("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'c19_risk') {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('../custom_reports/7-RİSK ANALİZİ/COVİD-19/COVİD OKUL/RİSK DEĞERLENDİRME COVİD 19.xlsx');
    $future = new DateTime(date('d-m-Y'));
    $future->modify('+6 year');
    $today = date('d-m-Y');
    $future = $future->format('d-m-Y');
    $worksheet = $spreadsheet->getActiveSheet();
    $worksheet->getCell('C1')->setValue($company_name);
    $worksheet->getCell('L3')->setValue("$today");
    $worksheet->getCell('L4')->setValue("$future");
    $worksheet->getCell('P1')->setValue("$address");
    $worksheet->getCell('N8')->setValue("$u_first $u_last");
    $worksheet->getCell('N9')->setValue("$h_first $h_last");
    $worksheet->getCell('N7')->setValue("$is_veren");

    $file_name = 'Risk Değerlendirme Covid 19_'.date('d-m-Y_G:i:s').'_.xlsx';
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'tedbir_kapak') {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $today = date('d-m-Y');
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../custom_reports/7-RİSK ANALİZİ/COVİD-19/RİSK KONTROL PLANI/Tedbirler-kapak.docx');
    $templateProcessor->setValue(array('{{is_veren}}','{{uzman}}','{{hekim}}','{{tarih}}'), array("$is_veren","$u_first $u_last","$h_first $h_last","$today"));
    $file_name = 'Tedbirler Kapak_'.date('d-m-Y_G:i:s').'_.docx';
    $templateProcessor->saveAs("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'isyeri_tedbir') {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $today = date('d-m-Y');
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../custom_reports/7-RİSK ANALİZİ/COVİD-19/RİSK KONTROL PLANI/işyerlerinde alınacak tedbirler.docx');
    $templateProcessor->setValue(array('{{tarih}}'), array("$today"));
    $file_name = 'İşyerlerinde Alınacak Tedbirler_'.date('d-m-Y_G:i:s').'_.docx';
    $templateProcessor->saveAs("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'acil_plan') {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $future = new DateTime(date('d-m-Y'));
    if ($danger_type == 3) {
      $future->modify('+3 year');
    }
    elseif ($danger_type == 2) {
      $future->modify('+2 year');
    }
    elseif ($danger_type == 1) {
      $future->modify('+1 year');
    }
    $today = date('d-m-Y');
    $future = $future->format('d-m-Y');

    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../custom_reports/8-ACİL DURUM PLANI/eylem_plani.docx');
    $templateProcessor->setValue(array('{{company_name}}','{{tarih}}','{{is_veren}}','{{uzman}}','{{sgk_sicil}}','{{future}}'), array("$company_name","$today","$is_veren","$u_first $u_last","$sgk_sicil","$future"));
    $file_name = 'Acil Durum Eylem Planı_'.date('d-m-Y_G:i:s').'_.docx';
    $templateProcessor->saveAs("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'acil_plan_kapak') {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $future = new DateTime(date('d-m-Y'));
    $tehlike_sınıfı="";
    if ($danger_type == 3) {
      $future->modify('+3 year');
      $tehlike_sınıfı = "Çok Tehlikeli";
    }
    elseif ($danger_type == 2) {
      $future->modify('+2 year');
      $tehlike_sınıfı = "Orta Tehlikeli";
    }
    elseif ($danger_type == 1) {
      $future->modify('+1 year');
      $tehlike_sınıfı = "Az Tehlikeli";
    }
    $today = date('d-m-Y');
    $future = $future->format('d-m-Y');

    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../custom_reports/8-ACİL DURUM PLANI/kapak.docx');
    $templateProcessor->setValue(array('{{company_name}}','{{tarih}}','{{is_veren}}','{{uzman}}','{{future}}','{{tehlike_sınıfı}}','{{nace_kodu}}','{{address}}'), array("$company_name","$today","$is_veren","$u_first $u_last","$future","$tehlike_sınıfı","$nace_kodu","$address"));
    $file_name = 'Acil Durum Eylem Planı Kapak_'.date('d-m-Y_G:i:s').'_.docx';
    $templateProcessor->saveAs("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'acil_plan_sema') {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $future = new DateTime(date('d-m-Y'));
    if ($danger_type == 3) {
      $future->modify('+3 year');
    }
    elseif ($danger_type == 2) {
      $future->modify('+2 year');
    }
    elseif ($danger_type == 1) {
      $future->modify('+1 year');
    }
    $today = date('d-m-Y');
    $future = $future->format('d-m-Y');

    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../custom_reports/8-ACİL DURUM PLANI/semalar.docx');
    $templateProcessor->setValue(array('{{company_name}}','{{tarih}}','{{is_veren}}','{{uzman}}','{{future}}','{{sgk_sicil}}'), array("$company_name","$today","$is_veren","$u_first $u_last","$future","$sgk_sicil"));
    $file_name = 'Acil Durum Eylem Planı Şemaları_'.date('d-m-Y_G:i:s').'_.docx';
    $templateProcessor->saveAs("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'sgp_kapak') {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $today = date('d-m-Y');
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../custom_reports/9- SAĞLIK GÜVENLİK PLANI/kapak.docx');
    $templateProcessor->setValue(array('{{company_name}}','{{tarih}}','{{address}}'), array("$company_name","$today","$address"));
    $file_name = 'Sağlık Güvenlik Planı Kapak_'.date('d-m-Y_G:i:s').'_.docx';
    $templateProcessor->saveAs("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'hazırlık') {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $today = date('d-m-Y');
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../custom_reports/9- SAĞLIK GÜVENLİK PLANI/hazirlik.docx');
    $templateProcessor->setValue(array('{{company_name}}','{{tarih}}','{{is_veren}}'), array("$company_name","$today","$is_veren"));
    $file_name = 'Hazırlık Koordinatörü_'.date('d-m-Y_G:i:s').'_.docx';
    $templateProcessor->saveAs("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'sg_plan') {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $today = date('d-m-Y');
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../custom_reports/9- SAĞLIK GÜVENLİK PLANI/sgp_plan.docx');
    $templateProcessor->setValue(array('{{company_name}}'), array("$company_name"));
    $file_name = 'Sağlık Güvenlik Planı_'.date('d-m-Y_G:i:s').'_.docx';
    $templateProcessor->saveAs("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'uygulama') {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $today = date('d-m-Y');
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../custom_reports/9- SAĞLIK GÜVENLİK PLANI/uygulama.docx');
    $templateProcessor->setValue(array('{{company_name}}','{{tarih}}','{{is_veren}}'), array("$company_name","$today","$is_veren"));
    $file_name = 'Uygulama Koordinatörü_'.date('d-m-Y_G:i:s').'_.docx';
    $templateProcessor->saveAs("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'ekip_list') {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('../custom_reports/9-ACİL DURUM MÜDAHALE EKİPLERİ/1- EKİP LİSTELERİ.xlsx');
    $worksheet = $spreadsheet->getActiveSheet();
    $file_name = 'Ekip Listeleri_'.date('d-m-Y_G:i:s').'_.xlsx';
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'akvt_egitim') {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $today = date('d-m-Y');
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../custom_reports/9-ACİL DURUM MÜDAHALE EKİPLERİ/1-ARAMA KURTARMA VE TAHLİYE EĞİTİMİ.docx');
    $templateProcessor->setValue(array('{{hekim}}','{{uzman}}','{{tarih}}','{{is_veren}}'), array("$h_first $h_last","$u_first $u_last","$today","$is_veren"));
    $file_name = 'Arama Kurtarma ve Tahliye Eğitimi_'.date('d-m-Y_G:i:s').'_.docx';
    $templateProcessor->saveAs("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'akvt_atama') {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $today = date('d-m-Y');
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../custom_reports/9-ACİL DURUM MÜDAHALE EKİPLERİ/arama_kurtarma.docx');
    $templateProcessor->setValue(array('{{company_name}}','{{tarih}}','{{is_veren}}','{{sgk_sicil}}'), array("$company_name","$today","$is_veren","$sgk_sicil"));
    $file_name = 'Arama Kurtarma ve Tahliye Ataması_'.date('d-m-Y_G:i:s').'_.docx';
    $templateProcessor->saveAs("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'iy_ekip') {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $today = date('d-m-Y');
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../custom_reports/9-ACİL DURUM MÜDAHALE EKİPLERİ/ilyardım_ekibi.docx');
    $templateProcessor->setValue(array('{{company_name}}','{{tarih}}','{{is_veren}}','{{sgk_sicil}}'), array("$company_name","$today","$is_veren","$sgk_sicil"));
    $file_name = 'İlk Yardım Ekibi Ataması_'.date('d-m-Y_G:i:s').'_.docx';
    $templateProcessor->saveAs("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'ym_ekip') {
    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $today = date('d-m-Y');
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../custom_reports/9-ACİL DURUM MÜDAHALE EKİPLERİ/yangin_mucadele.docx');
    $templateProcessor->setValue(array('{{company_name}}','{{tarih}}','{{is_veren}}','{{sgk_sicil}}'), array("$company_name","$today","$is_veren","$sgk_sicil"));
    $file_name = 'Yangınla Mücadele Ekibinin Ataması_'.date('d-m-Y_G:i:s').'_.docx';
    $templateProcessor->saveAs("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

  elseif ($report_name == 'dk_ekip') {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('../custom_reports/9-ACİL DURUM MÜDAHALE EKİPLERİ/DENETİM KONTROL EKİBİ COVİD-19.xlsx');
    $worksheet = $spreadsheet->getActiveSheet();
    $file_name = 'Denetim Kontrol Ekibi_'.date('d-m-Y_G:i:s').'_.xlsx';
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save("../$company_name/isletme_raporlari/$file_name");
    header("Location: ../$company_name/index.php?tab=isletme_rapor");
  }

}
?>
