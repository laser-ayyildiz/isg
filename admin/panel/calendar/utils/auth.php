<?php

  try {
    $auth = new PDO('mysql:host=localhost;dbname=isg;charset=utf8', 'root', 'Monokotil2453+');
  }
  catch(Exception $error) {
    die('Error : ' . $error->getMessage());
  }

?>
