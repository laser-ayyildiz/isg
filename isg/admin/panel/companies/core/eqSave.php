<?php
require '../../../connect.php';
if (isset($_POST['eq_save'])) {
  $eq_name = !empty($_POST['eq_name']) ? trim($_POST['eq_name']) : null;
  $eq_purpose = !empty($_POST['eq_purpose']) ? trim($_POST['eq_purpose']) : null;
  $eq_freq = !empty($_POST['eq_freq']) ? trim($_POST['eq_freq']) : null;
  $company_id = !empty($_POST['company_id']) ? trim($_POST['company_id']) : null;
  $sql = "INSERT INTO `equipment`(`company_id`, `name`, `purpose`, `maintenance_freq`)
  VALUES('$company_id', '$eq_name', '$eq_purpose', '$eq_freq')";
  $stmt = $pdo->prepare($sql);
  $result = $stmt->execute();
  if ($result) {
    $sql = "SELECT `name` FROM `coop_companies` WHERE `id` = '$company_id'";
	  foreach ($pdo->query($sql) as $row) {
			$company_name = $row['name'];
	 	}
	  header("Location: ../$company_name/index.php?tab=isletme_ekipman");
	}
}
?>
