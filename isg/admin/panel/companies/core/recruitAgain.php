<?php
require '../../../connect.php';
if (isset($_POST['recruitAgain'])) {
  $worker_tc = $_POST['TCWillRecruit'];
  $company_id = $_POST['company_id'];
  $company_name = $_POST['company_name'];

    $sql = "UPDATE `coop_workers` SET `deleted` = 0 WHERE `company_id` = '$company_id' AND `tc` = '$worker_tc'";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute();
    if ($result) {
  	  header("Location: ../$company_name/index.php?tab=isletme_calisanlar");
    }
}
?>
