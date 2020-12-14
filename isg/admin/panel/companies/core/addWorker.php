<?php
require '../../../connect.php';
if (isset($_POST['add_worker'])) {
  $company_id = $_POST['company_id'];
  $company_name = $_POST['company_name'];
  $worker_name = !empty($_POST['worker_name']) ? trim($_POST['worker_name']) : null;
  $worker_tc = !empty($_POST['worker_tc']) ? trim($_POST['worker_tc']) : null;
  $worker_position= !empty($_POST['worker_position']) ? trim($_POST['worker_position']) : null;
  $worker_sex = !empty($_POST['worker_sex']) ? trim($_POST['worker_sex']) : null;
  $worker_mail = !empty($_POST['worker_mail']) ? trim($_POST['worker_mail']) : null;
  $worker_phone = !empty($_POST['worker_phone']) ? trim($_POST['worker_phone']) : null;
  $worker_contract_date = !empty($_POST['worker_contract_date']) ? trim($_POST['worker_contract_date']) : null;
  $worker_contract_date = date('Y-m-d');

  $sql = "SELECT COUNT(tc) AS num FROM coop_workers WHERE tc = :tc";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':tc', $worker_tc);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row['num'] > 0) {
      ?>
      <script type="text/javascript">
          var retVal = confirm("Bu T.C Kimlik Numarası ile daha önce kayıt yapıldı!");
          window.location.replace("../<?=$company_name?>/index.php?tab=isletme_calisanlar");
      </script>
    <?php
      }
  else {
      $sql = "INSERT INTO `coop_workers`(`name`, `tc`, `position`, `sex`, `mail`, `phone`, `contract_date`,`company_id`)
      VALUES('$worker_name', '$worker_tc', '$worker_position', '$worker_sex', '$worker_mail', '$worker_phone', '$worker_contract_date','$company_id')";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute();
      if ($result) {
        mkdir("../$company_name/$worker_tc",0777);
        header("Location: ../$company_name/index.php?tab=isletme_calisanlar");
      }
  }
}
?>
