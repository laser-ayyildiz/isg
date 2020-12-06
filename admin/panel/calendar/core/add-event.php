<?php
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
}

header('Location: '.$_SERVER['HTTP_REFERER']);
?>
