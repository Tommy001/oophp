<?php
$kabyssen['database']['dsn']            = 'mysql:localhost;dbname=toja14;'; // host=blu-ray.student.bth.se
$kabyssen['database']['username']       = 'root'; //toja14
$kabyssen['database']['password']       = ''; //b8nRR5(s
$kabyssen['database']['driver_options'] = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");
$options = $kabyssen['database'];
$default = array(
      'dsn' => null,
      'username' => null,
      'password' => null,
      'driver_options' => null,
      'fetch_style' => PDO::FETCH_OBJ,
    );
    $kalle = array_merge($default, $options);
    
    var_dump($kalle);
