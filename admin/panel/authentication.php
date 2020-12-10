<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header('Location: ../login.php');
    exit;
}
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require '../connect.php';
$id=$_SESSION['user_id'];
$başlangıç=$pdo->prepare("SELECT * FROM users WHERE id = '$id'");
$başlangıç->execute();
$girişler=$başlangıç-> fetchAll(PDO::FETCH_OBJ);
foreach ($girişler as $giriş) {
    $fn = $giriş->firstname;
    $ln = $giriş->lastname;
    $ume = $giriş->username;
    $picture= $giriş->picture;
    $auth = $giriş->auth;
}

if ($auth != 1) {
    header('Location: 403.php');
}
if (isset($_POST['yetkilendir'])) {
    $comp_name = !empty($_POST['comp_name']) ? trim($_POST['comp_name']) : null;
    $username = !empty($_POST['username']) ? trim($_POST['username']) : null;

    $sirket = $pdo->prepare("SELECT * FROM `coop_companies` WHERE `name` = '$comp_name'");
    $sirket->execute();
    $personels=$sirket->fetchAll(PDO::FETCH_OBJ);
    foreach ($personels as $personel) {
        $uzman_id = $personel->uzman_id;
        $uzman_id_2 = $personel->uzman_id_2;
        $uzman_id_3 = $personel->uzman_id_3;

        $hekim_id = $personel->hekim_id;
        $hekim_id_2 = $personel->hekim_id_2;
        $hekim_id_3 = $personel->hekim_id_3;

        $saglık_p_id = $personel->saglık_p_id;
        $saglık_p_id_2 = $personel->saglık_p_id_2;

        $ofis_p_id = $personel->ofis_p_id;
        $ofis_p_id_2 = $personel->ofis_p_id_2;

        $muhasebe_p_id = $personel->muhasebe_p_id;
        $muhasebe_p_id_2 = $personel->muhasebe_p_id_2;
    }

    $kişi = $pdo->prepare("SELECT * FROM `users` WHERE `username` = '$username'");
    $kişi->execute();
    $yets=$kişi->fetchAll(PDO::FETCH_OBJ);
    foreach ($yets as $yet) {
        $id = $yet->id;
        $yet_auth = $yet->auth;
    }

    if ($yet_auth==0) {
      if($uzman_id != 0){
        if ($uzman_id_2 != 0) {
          if ($uzman_id_3 != 0) {
            $sql = "UPDATE `coop_companies` SET `yetkili_id` = $id WHERE `coop_companies`.`name` = '$comp_name'";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute();
            if ($result) {
                ?>
              <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
                <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyebilir ve düzenleme yapabilir!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <?php
                }
              }
          else{
            $sql = "UPDATE `coop_companies` SET `uzman_id_3` = $id WHERE `coop_companies`.`name` = '$comp_name'";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute();
            if ($result) {
                ?>
              <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
                <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyebilir ve düzenleme yapabilir!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <?php
            }
          }
        }
        else {
          $sql = "UPDATE `coop_companies` SET `uzman_id_2` = $id WHERE `coop_companies`.`name` = '$comp_name'";
          $stmt = $pdo->prepare($sql);
          $result = $stmt->execute();
          if ($result) {
              ?>
            <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
              <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyebilir ve düzenleme yapabilir!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <?php
          }
        }
      }
      else {
        $sql = "UPDATE `coop_companies` SET `uzman_id` = $id WHERE `coop_companies`.`name` = '$comp_name'";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            ?>
          <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
            <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyebilir ve düzenleme yapabilir!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php
      }
      }
    }

    else if($yet_auth==2) {
      if($hekim_id != 0){
        if ($hekim_id_2 != 0) {
          if ($hekim_id_3 != 0) {
            $sql = "UPDATE `coop_companies` SET `yetkili_id` = $id WHERE `coop_companies`.`name` = '$comp_name'";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute();
            if ($result) {
                ?>
              <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
                <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyebilir ve düzenleme yapabilir!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <?php
            }
          }
          else{
            $sql = "UPDATE `coop_companies` SET `hekim_id_3` = $id WHERE `coop_companies`.`name` = '$comp_name'";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute();
            if ($result) {
                ?>
              <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
                <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyebilir ve düzenleme yapabilir!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <?php
            }
          }
        }
        else {
          $sql = "UPDATE `coop_companies` SET `hekim_id_2` = $id WHERE `coop_companies`.`name` = '$comp_name'";
          $stmt = $pdo->prepare($sql);
          $result = $stmt->execute();
          if ($result) {
              ?>
            <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
              <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyebilir ve düzenleme yapabilir!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <?php
          }
        }
      }
      else {
        $sql = "UPDATE `coop_companies` SET `hekim_id` = $id WHERE `coop_companies`.`name` = '$comp_name'";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            ?>
          <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
            <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyebilir ve düzenleme yapabilir!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php
      }
      }
    }

    else if($yet_auth==3) {
      if($saglık_p_id != 0){
        if ($saglık_p_id_2 != 0) {
          $sql = "UPDATE `coop_companies` SET `yetkili_id` = $id WHERE `coop_companies`.`name` = '$comp_name'";
          $stmt = $pdo->prepare($sql);
          $result = $stmt->execute();
          if ($result) {
              ?>
            <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
              <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyebilir ve düzenleme yapabilir!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <?php
          }
        }
        else {
          $sql = "UPDATE `coop_companies` SET `saglık_p_id_2` = $id WHERE `coop_companies`.`name` = '$comp_name'";
          $stmt = $pdo->prepare($sql);
          $result = $stmt->execute();
          if ($result) {
              ?>
            <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
              <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyebilir ve düzenleme yapabilir!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <?php
          }
        }
      }
      else {
        $sql = "UPDATE `coop_companies` SET `saglık_p_id` = $id WHERE `coop_companies`.`name` = '$comp_name'";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            ?>
          <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
            <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyebilir ve düzenleme yapabilir!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php
      }
      }
    }

    else if($yet_auth==4) {
      if($ofis_p_id != 0){
        if ($ofis_p_id_2 != 0) {
          $sql = "UPDATE `coop_companies` SET `yetkili_id` = $id WHERE `coop_companies`.`name` = '$comp_name'";
          $stmt = $pdo->prepare($sql);
          $result = $stmt->execute();
          if ($result) {
              ?>
            <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
              <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyebilir ve düzenleme yapabilir!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <?php
          }
        }
        else {
          $sql = "UPDATE `coop_companies` SET `ofis_p_id_2` = $id WHERE `coop_companies`.`name` = '$comp_name'";
          $stmt = $pdo->prepare($sql);
          $result = $stmt->execute();
          if ($result) {
              ?>
            <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
              <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyebilir ve düzenleme yapabilir!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <?php
          }
        }
      }
      else {
        $sql = "UPDATE `coop_companies` SET `ofis_p_id` = $id WHERE `coop_companies`.`name` = '$comp_name'";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            ?>
          <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
            <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyebilir ve düzenleme yapabilir!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php
      }
      }
    }

    else if($yet_auth==6) {
      if($muhasebe_p_id != 0){
        if ($muhasebe_p_id_2 != 0) {
          $sql = "UPDATE `coop_companies` SET `yetkili_id` = $id WHERE `coop_companies`.`name` = '$comp_name'";
          $stmt = $pdo->prepare($sql);
          $result = $stmt->execute();
          if ($result) {
              ?>
            <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
              <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyebilir ve düzenleme yapabilir!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <?php
          }
        }
        else {
          $sql = "UPDATE `coop_companies` SET `muhasebe_p_id_2` = $id WHERE `coop_companies`.`name` = '$comp_name'";
          $stmt = $pdo->prepare($sql);
          $result = $stmt->execute();
          if ($result) {
              ?>
            <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
              <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyebilir ve düzenleme yapabilir!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <?php
          }
        }
      }
      else {
        $sql = "UPDATE `coop_companies` SET `muhasebe_p_id` = $id WHERE `coop_companies`.`name` = '$comp_name'";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            ?>
          <div class="alert alert-primary alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
            <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyebilir ve düzenleme yapabilir!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php
      }}}

    else {?>
      <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
        <b>Beklenmedik bir hatayla karşılaşıldı</b>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php
    }
}

if (isset($_POST['yetki_kaldır'])) {
  $comp_name = !empty($_POST['comp_name']) ? trim($_POST['comp_name']) : null;
  $username = !empty($_POST['username']) ? trim($_POST['username']) : null;

  $kişi = $pdo->prepare("SELECT * FROM `users` WHERE `username` = '$username'");
  $kişi->execute();
  $yets=$kişi->fetchAll(PDO::FETCH_OBJ);
  foreach ($yets as $yet) {
      $id = $yet->id;
      $yet_auth = $yet->auth;
  }
  $sirket = $pdo->prepare("SELECT * FROM `coop_companies` WHERE `name` = '$comp_name'");
  $sirket->execute();
  $personels=$sirket->fetchAll(PDO::FETCH_OBJ);
  foreach ($personels as $personel) {
      $uzman_id = $personel->uzman_id;
      $uzman_id_2 = $personel->uzman_id_2;
      $uzman_id_3 = $personel->uzman_id_3;

      $hekim_id = $personel->hekim_id;
      $hekim_id_2 = $personel->hekim_id_2;
      $hekim_id_3 = $personel->hekim_id_3;

      $saglık_p_id = $personel->saglık_p_id;
      $saglık_p_id_2 = $personel->saglık_p_id_2;

      $ofis_p_id = $personel->ofis_p_id;
      $ofis_p_id_2 = $personel->ofis_p_id_2;

      $muhasebe_p_id = $personel->muhasebe_p_id;
      $muhasebe_p_id_2 = $personel->muhasebe_p_id_2;
  }


  if ($yet_auth==0) {
    if($uzman_id != $id){
      if ($uzman_id_2 != $id) {
        if ($uzman_id_3 != $id) {
          $sql = "UPDATE `coop_companies` SET `yetkili_id` = 0 WHERE `coop_companies`.`name` = '$comp_name'";
          $stmt = $pdo->prepare($sql);
          $result = $stmt->execute();
          if ($result) {
              ?>
            <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
              <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyemeyecek!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <?php
              }
            }
        else{
          $sql = "UPDATE `coop_companies` SET `uzman_id_3` = 0 WHERE `coop_companies`.`name` = '$comp_name'";
          $stmt = $pdo->prepare($sql);
          $result = $stmt->execute();
          if ($result) {
              ?>
              <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
                <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyemeyecek!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <?php
          }
        }
      }
      else {
        $sql = "UPDATE `coop_companies` SET `uzman_id_2` = 0 WHERE `coop_companies`.`name` = '$comp_name'";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            ?>
            <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
              <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyemeyecek!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php
        }
      }
    }
    else {
      $sql = "UPDATE `coop_companies` SET `uzman_id` = 0 WHERE `coop_companies`.`name` = '$comp_name'";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute();
      if ($result) {
          ?>
          <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
            <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyemeyecek!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
      <?php
    }
    }
  }

  else if($yet_auth==2) {
    if($hekim_id != 0){
      if ($hekim_id_2 != 0) {
        if ($hekim_id_3 != 0) {
          $sql = "UPDATE `coop_companies` SET `yetkili_id` = 0 WHERE `coop_companies`.`name` = '$comp_name'";
          $stmt = $pdo->prepare($sql);
          $result = $stmt->execute();
          if ($result) {
              ?>
              <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
                <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyemeyecek!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <?php
          }
        }
        else{
          $sql = "UPDATE `coop_companies` SET `hekim_id_3` = 0 WHERE `coop_companies`.`name` = '$comp_name'";
          $stmt = $pdo->prepare($sql);
          $result = $stmt->execute();
          if ($result) {
              ?>
              <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
                <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyemeyecek!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <?php
          }
        }
      }
      else {
        $sql = "UPDATE `coop_companies` SET `hekim_id_2` = 0 WHERE `coop_companies`.`name` = '$comp_name'";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            ?>
            <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
              <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyemeyecek!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          </div>
          <?php
        }
      }
    }
    else {
      $sql = "UPDATE `coop_companies` SET `hekim_id` = 0 WHERE `coop_companies`.`name` = '$comp_name'";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute();
      if ($result) {
          ?>
          <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
            <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyemeyecek!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
      <?php
    }
    }
  }

  else if($yet_auth==3) {
    if($saglık_p_id != 0){
      if ($saglık_p_id_2 != 0) {
        $sql = "UPDATE `coop_companies` SET `yetkili_id` = 0 WHERE `coop_companies`.`name` = '$comp_name'";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            ?>
            <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
              <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyemeyecek!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php
        }
      }
      else {
        $sql = "UPDATE `coop_companies` SET `saglık_p_id_2` = 0 WHERE `coop_companies`.`name` = '$comp_name'";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            ?>
            <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
              <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyemeyecek!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php
        }
      }
    }
    else {
      $sql = "UPDATE `coop_companies` SET `saglık_p_id` = 0 WHERE `coop_companies`.`name` = '$comp_name'";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute();
      if ($result) {
          ?>
          <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
            <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyemeyecek!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
      <?php
    }
    }
  }

  else if($yet_auth==4) {
    if($ofis_p_id != 0){
      if ($ofis_p_id_2 != 0) {
        $sql = "UPDATE `coop_companies` SET `yetkili_id` = 0 WHERE `coop_companies`.`name` = '$comp_name'";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            ?>
            <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
              <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyemeyecek!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php
        }
      }
      else {
        $sql = "UPDATE `coop_companies` SET `ofis_p_id_2` = 0 WHERE `coop_companies`.`name` = '$comp_name'";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            ?>
            <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
              <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyemeyecek!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php
        }
      }
    }
    else {
      $sql = "UPDATE `coop_companies` SET `ofis_p_id` = 0 WHERE `coop_companies`.`name` = '$comp_name'";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute();
      if ($result) {
          ?>
          <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
            <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyemeyecek!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
      <?php
    }
    }
  }

  else if($yet_auth==6) {
    if($muhasebe_p_id != 0){
      if ($muhasebe_p_id_2 != 0) {
        $sql = "UPDATE `coop_companies` SET `yetkili_id` = 0 WHERE `coop_companies`.`name` = '$comp_name'";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            ?>
            <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
              <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyemeyecek!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php
        }
      }
      else {
        $sql = "UPDATE `coop_companies` SET `muhasebe_p_id_2` = 0 WHERE `coop_companies`.`name` = '$comp_name'";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            ?>
            <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
              <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyemeyecek!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php
        }
      }
    }
    else {
      $sql = "UPDATE `coop_companies` SET `muhasebe_p_id` = 0 WHERE `coop_companies`.`name` = '$comp_name'";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute();
      if ($result) {
          ?>
          <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
            <strong><?=$username." "?></strong>kullanıcısı<b><?= " ".$comp_name." " ?></b>işletmesini artık görüntüleyemeyecek!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" padding="auto">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
      <?php
    }}}

  else {?>
    <div class="alert alert-danger alert-dismissible fade show" style=" margin-bottom: 0 !important;" role="alert">
      <b>Beklenmedik bir hatayla karşılaşıldı</b>
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
  <title>Yetkilendir</title>
  <link rel="shortcut icon" href="../images/osgb_amblem.ico">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
  <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
  <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
  <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
  <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
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
      box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
      z-index: 1;
    }

    .dropdown-content a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }

    .dropdown-content a:hover {
      background-color: #ddd;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }

    .dropdown:hover .dropbtn {
      background-color: #3e8e41;
    }


  </style>
</head>

<body id="page-top">
  <nav class="navbar shadow navbar-expand mb-3 bg-warning topbar static-top">
    <img width="55" height="40" class="rounded-circle img-profile" src="assets/img/nav_brand.jpg" />
    <a class="navbar-brand" title="Anasayfa" style="color: black;" href="index.php"><b>Özgür OSGB</b></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span></button>

    <ul class="navbar-nav navbar-expand mr-auto">
      <li class="nav-item">
      <div class="dropdown no-arrow">
        <a style="color:black;" class="nav-link btn btn-warning dropdown-toggle"type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-building"></i><span>&nbsp;İşletmeler</span></a>
            <div class="dropdown-content" aria-labelledby="dropdownMenu2">
              <a class="dropdown-item" type="button" href="companies.php"><i class="fas fa-stream"></i><span>&nbsp;İşletme Listesi</span></a>
              <a class="dropdown-item" type="button" href="deleted_companies.php"><i class="fas fa-eraser"></i><span>&nbsp;Silinen İşletmeler</span></a>
              <?php
              if($auth == 1){?>
              <a class="dropdown-item" type="button" href="change_validate.php"><i class="fas fa-exchange-alt"></i><span>&nbsp;Onay Bekleyenler</span></a>
              <?php }?>
            </div>
      </div>
      </li>
      <li class="nav-item">
        <a style="color: black;" class="nav-link btn-warning" href="reports.php"><i
            class="fas fa-folder"></i><span>&nbsp;Raporlar</span></a>
      </li>
      <li class="nav-item">
          <a style="color: black;" class="nav-link btn-warning" href="calendar/index.php"><i class="fas fa-calendar-alt"></i><span>&nbsp;Takvim</span></a>
        </li>
      <?php
          if ($auth == 1) {
        ?>
      <li class="nav-item"><a style="color: black;" class="nav-link btn-warning" href="settings.php"><i
            class="fas fa-wrench"></i><span>&nbsp;Ayarlar</span></a></li>
      <li class="nav-item">
      <div class="dropdown no-arrow">
        <button style="color:black;" class="nav-link btn btn-warning dropdown-toggle"type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
            class="fas fa-users"></i><span>&nbsp;Çalışanlar</span></button>
            <div class="dropdown-content" aria-labelledby="dropdownMenu2">
              <a class="dropdown-item" type="button" href="osgb_users.php"><i class="fas fa-stream"></i><span>&nbsp;Çalışan Listesi</span></a>
              <a class="dropdown-item" type="button" href="deleted_workers.php"><i class="fas fa-eraser"></i><span>&nbsp;Silinen Çalışanlar</span></a>
              <a class="dropdown-item" type="button" href="authentication.php"><i class="fas fa-user-edit"></i><span>&nbsp;Yetkilendir</span></a>
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
          <?php
            $bildirim_say = $pdo->query("SELECT COUNT(*) FROM `notifications` WHERE `user_id` = '$id' ORDER BY reg_date")->fetchColumn();
                  ?>
        <a href="notifications.php" title="Bildirimler" class="nav-link"
          data-bs-hover-animate="rubberBand">
          <i style="color: black;" class="fas fa-bell fa-fw"></i>
          <span class="badge badge-danger badge-counter"><?= $bildirim_say ?></span></a>
        </div>
      </li>
      <li class="nav-item dropdown no-arrow mx-1">
        <div class="nav-item dropdown no-arrow">
          <a style="color: black;" title="Mesajlar" href="messages.php" class="dropdown-toggle nav-link"
            data-bs-hover-animate="rubberBand">
            <i style="color: black;" class="fas fa-envelope fa-fw"></i>
            <?php
              $mesaj_say = $pdo->query("SELECT COUNT(*) FROM `message` WHERE `kime` = '$ume' ORDER BY tarih")->fetchColumn();
                    ?>
            <span class="badge badge-danger badge-counter"><?=$mesaj_say?></span></a>
        </div>
        <div class="shadow dropdown-list dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown"></div>
      </li>
      <div class="d-none d-sm-block topbar-divider"></div>
      <li class="nav-item">
        <div class="nav-item">
          <a href="profile.php" class="nav-link" title="Profil">
            <span style="color:black;" class="d-none d-lg-inline mr-2 text-600"><?=$fn?> <?=$ln?></span><img
              class="rounded-circle img-profile" src="assets/users/<?=$picture?>"></a>
      </li>
      <div class="d-none d-sm-block topbar-divider"></div>
        <li class="nav-item"><a style="color: black;" title="Çıkış" class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i><span>&nbsp;Çıkış</span></a></li>
    </ul>
  </div>
  </nav>
  <div class="container-fluid">
    <div class="card shadow-lg">
      <div class="card-header border bg-light">
        <h1 class="text-dark mb-1" style="text-align: center;"><b>Çalışan Yetkilendir</b></h1>
      </div>
      <div class="card-body border">
        <div class="row col-md-4">
          <input type="text" class="form-control" id="myInput" onkeyup="myFunction()" placeholder="Çalışan ismi ile ara...">
        </div>
        <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
          <table class="table table-striped table-bordered" id="dataTable">
            <thead class="thead-dark">
              <tr>
                <th>Ad</th>
                <th>Soyad</th>
                <th>Çalışma Alanı</th>
                <th>T.C Kimlik No</th>
                <th>İşe Giriş Tarihi</th>
                <th>Yetkilendir</th>
                <th>Yetkisini Kaldır</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $sorgu=$pdo->prepare("SELECT * FROM `osgb_workers` WHERE `deleted` = 0");
                $sorgu->execute();
                $workers=$sorgu-> fetchAll(PDO::FETCH_OBJ);
                foreach ($workers as $key=>$worker) {?>
              <tr>
                <td><?= $worker->firstname ?></td>
                <td><?= $worker->lastname ?></td>
                <td><?= $worker->user_type ?></td>
                <td><?= $worker->tc ?></td>
                <td><?= $worker->start_date ?></td>
                <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#a<?php echo $key; ?>" data-whatever="@getbootstrap">Yetkilendir</button></td>
                <td><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#b<?php echo $key; ?>" data-whatever="@getbootstrap">Yetkisini Kaldır</button></td>
              </tr>
              <div class="modal fade" id="a<?php echo $key; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Yetkilendir</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form action="authentication.php" method="POST">
                        <div class="row col-sm-10">
                          <label><b>Kullanıcı adı</b></label>
                          <input class="form-control" name="username" value="<?= $worker->mail ?>" readonly>
                        </div>
                        <br>
                        <div class="row col-sm-10">
                          <label><b>Yetkilendirileceği işletmeyi seçin</b></label>
                          <select class="form-control" name="comp_name" required>
                            <option value="" disabled>İş Yeri Seç</option>
                            <?php
                                  $yetki_company=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `deleted` = 0");
                                  $yetki_company->execute();
                                  $hedefler=$yetki_company->fetchAll(PDO::FETCH_OBJ);
                                  foreach ($hedefler as $hedef) {?>
                                    <option value="<?=$hedef->name?>"><?= $hedef->name ?></option>
                                    <?php
                                    }
                              ?>
                          </select>
                        </div>
                        <br>
                        <div style="float: right;">
                          <button id="yetkilendir" name="yetkilendir" type="submit" class="btn btn-success">Yetkilendir</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

              <div class="modal fade" id="b<?php echo $key; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Yetkisini Kaldır</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form action="authentication.php" method="POST">
                        <div class="row col-sm-10">
                          <label><b>Kullanıcı adı</b></label>
                          <input class="form-control" name="username" value="<?= $worker->mail ?>" readonly>
                        </div>
                        <br>
                        <div class="row col-sm-10">
                          <label><b>Yetkilendirildiği işletmeler</b></label>
                          <select class="form-control" name="comp_name" required>
                            <option value="" disabled>İş Yeri Seç</option>
                            <?php
                            $sorgu5=$pdo->prepare("SELECT * FROM `users` WHERE `username` = '$worker->mail'");
                            $sorgu5->execute();
                            $workers5=$sorgu5-> fetchAll(PDO::FETCH_OBJ);
                            foreach ($workers5 as $worker5) {
                              $user_id = $worker5->id;
                              $user_auth = $worker5->auth;
                            }

                              if ($user_auth == 0) {
                                  $yetki_company=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `uzman_id` = '$user_id' OR `uzman_id_2` = '$user_id' OR `uzman_id_3` = '$user_id' OR `yetkili_id` = '$user_id'");
                                  $yetki_company->execute();
                                  $hedefler=$yetki_company->fetchAll(PDO::FETCH_OBJ);
                                  foreach ($hedefler as $hedef) {?>
                                    <option value="<?=$hedef->name?>"><?= $hedef->name ?></option>
                                    <?php
                                    }
                                  }

                              else if ($user_auth == 2) {
                                $yetki_company=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `hekim_id` = '$user_id' OR `hekim_id_2` = '$user_id' OR `hekim_id_3` = '$user_id' OR `yetkili_id` = '$user_id'");
                                $yetki_company->execute();
                                $hedefler=$yetki_company->fetchAll(PDO::FETCH_OBJ);
                                foreach ($hedefler as $hedef) {?>
                                  <option value="<?=$hedef->name?>"><?= $hedef->name ?></option>
                                <?php
                                }
                              }

                              else if ($user_auth == 3) {
                                $yetki_company=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `saglık_p_id` = '$user_id' OR `saglık_p_id_2` = '$user_id' OR `yetkili_id` = '$user_id'");
                                $yetki_company->execute();
                                $hedefler=$yetki_company->fetchAll(PDO::FETCH_OBJ);
                                foreach ($hedefler as $hedef) {?>
                                  <option value="<?=$hedef->name?>"><?= $hedef->name ?></option>
                                <?php
                                }
                              }

                              else if ($user_auth == 4) {
                                $yetki_company=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `ofis_p_id` = '$user_id' OR `ofis_p_id_2` = '$user_id' OR `yetkili_id` = '$user_id'");
                                $yetki_company->execute();
                                $hedefler=$yetki_company->fetchAll(PDO::FETCH_OBJ);
                                foreach ($hedefler as $hedef) {?>
                                  <option value="<?=$hedef->name?>"><?= $hedef->name ?></option>
                                <?php
                                }
                              }

                              else if ($user_auth == 6) {
                                $yetki_company=$pdo->prepare("SELECT * FROM `coop_companies` WHERE `muhasebe_p_id` = '$user_id' OR `muhasebe_p_id_2` = '$user_id' OR `yetkili_id` = '$user_id'");
                                $yetki_company->execute();
                                $hedefler=$yetki_company->fetchAll(PDO::FETCH_OBJ);
                                foreach ($hedefler as $hedef) {?>
                                  <option value="<?=$hedef->name?>"><?= $hedef->name ?></option>
                                <?php
                                }
                              }

                              ?>
                          </select>
                        </div>
                        <br>
                        <div style="float:left;">
                          <button id="hepsini_kaldır" name="hepsini_kaldır" type="submit" class="btn btn-warning">Bütün Yetkilerini Kaldır</button>
                        </div>
                        <div style="float: right;">
                          <button id="yetki_kaldır" name="yetki_kaldır" type="submit" class="btn btn-danger">Seçili Yetkisini Kaldır</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
        </div>
        <?php } ?>
        </tbody>
        <tfoot>
          <tr>
            <td><strong>Ad</strong></td>
            <td><strong>Soyad</strong></td>
            <td><strong>Çalışma Alanı</strong></td>
            <td><strong>T.C Kimlik No</strong></td>
            <td><strong>İşe Giriş Tarihi</strong></td>
            <td><strong>Yetkilendir</strong></td>
            <td><strong>Yetkisini Kaldır</strong></td>
          </tr>
        </tfoot>
        </table>
      </div>
    </div>
  </div>
  <footer class="bg-white sticky-footer">
    <div class="container my-auto">
      <div class="text-center my-auto copyright"><span>Copyright © ÖzgürOSGB 2020</span></div>
    </div>
  </footer>
  </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
  </div>
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/js/chart.min.js"></script>
  <script src="assets/js/bs-init.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
  <script src="assets/js/theme.js"></script>
  <script type="text/javascript">
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>
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
</body>

</html>
