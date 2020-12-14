<?php
require '../../../connect.php';
if (isset($_POST['deleteWorkerButton'])) {
  $worker_tc = $_POST['TCWillDelete'];
  $company_id = $_POST['company_id'];
  $company_name = $_POST['company_name'];

  $sql = "UPDATE `coop_workers` SET `deleted` = 1 WHERE `company_id` = '$company_id' AND `tc` = '$worker_tc'";
  $stmt = $pdo->prepare($sql);
  $result = $stmt->execute();
  if ($result) {
    header("Location: ../$company_name/index.php?tab=silinen_calisanlar");
  }
}
?>
