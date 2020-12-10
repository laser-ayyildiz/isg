<?php

require_once('../utils/auth.php');
if (isset($_POST['delete']) && isset($_POST['id'])){

	$id = $_POST['id'];
	$company_name = isset($_POST['event_company_name']) ? trim($_POST['event_company_name']) : null;

	$sql = "DELETE FROM events WHERE id = $id";

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
	if ($company_name == null) {
		header('Location: ../index.php');
	}
	else {
		header("Location: ../../companies/$company_name/index.php?tab=isletme_takvim");

	}

} else if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['color']) && isset($_POST['id'])){

	$id = $_POST['id'];
	$title = $_POST['title'];
	$description = $_POST['description'];
	$color = $_POST['color'];
	$company_name = isset($_POST['event_company_name']) ? trim($_POST['event_company_name']) : null;


	$sql = "UPDATE events SET  title = '$title', description = '$description', color = '$color' WHERE id = $id ";

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
	if ($company_name == null) {
		header('Location: ../index.php');
	}
	else {
		header("Location: ../../companies/$company_name/index.php?tab=isletme_takvim");

	}
}

?>
