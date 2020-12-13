<?php

  try {
    $auth = new PDO('mysql:host=localhost;dbname=isg;charset=utf8', 'root', '');
  }
  catch(Exception $error) {
    die('Error : ' . $error->getMessage());
  }

?>
