<?php
ob_start();
require_once('../utils/auth.php');
require_once('../utils/sanitize.php');

if (isset($_POST['title'])) {

	$title = sanitizeInput($_POST['title']);
	$description = sanitizeInput($_POST['description']);
	$start = $_POST['start'];
	$end = $_POST['end'];
	$color = sanitizeInput($_POST['color']);
	$company_id = !empty($_POST['company_id']) ? trim($_POST['company_id']) : 0;
	$user_id = !empty($_POST['user_id']) ? trim($_POST['user_id']) : 0;

	$sql = "INSERT INTO events(title, description, start, end, color, company_id, user_id) values ('$title', '$description', '$start', '$end', '$color', '$company_id', '$user_id')";
	echo $sql;

	$prepareQuery = $auth->prepare($sql);

	if ($prepareQuery == false) {
	 print_r($auth->errorInfo());
	 die ('Error preparing the query.');
	}

	$executeQuery = $prepareQuery->execute();

	if ($executeQuery == false) {
	 print_r($prepareQuery->errorInfo());
	 die ('Error executing the query.');
	}
	if ($company_id == 0) {
		header('Location: ../index.php');
	}
	else {
		$sql = "SELECT `name` FROM `coop_companies` WHERE `id` = '$company_id'";
	  foreach ($auth->query($sql) as $row) {
			$company_name = $row['name'];
	 	}
	  header("Location: ../../companies/$company_name/index.php?tab=isletme_takvim");

	}

}
ob_flush();
?>
