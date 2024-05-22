<?php 
function request($sql, $uniq = false){
require "connect_db/connect_param.php";

try {
  $connexion = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
  $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


  $requete = $connexion->prepare($sql);

  $requete->execute();

  if ($uniq){
    $results = $requete->fetch(PDO::FETCH_ASSOC);
  } else {
    $results = $requete->fetchAll(PDO::FETCH_ASSOC);
  }

  $connexion = null;
  return $results;

} catch(PDOException $e) {
  $connexion = null;
  echo "Error : ".$e;
  return false;
}
}

function insert($table, $name, $value, $get_id = true){
  require "connect_db/connect_param.php";
  
  try {
    $connexion = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $name = implode(", ", $name);

    $values = implode(", ", array_map(function ($v){
      if (strtolower($v) != "null"){
        return "'$v'";
      } else {
        return $v;
      }
    }, $value));

    $sql = "INSERT INTO $table($name) VALUES ($values)";

    $requete = $connexion->prepare($sql);
  
    $requete->execute();
    $id;
    if ($get_id){
      $id = $connexion->lastInsertId();
    } else {
      $id = true;
    }
    $connexion = null;
    return $id;
  } catch(PDOException $e) {
    $connexion = null;
    echo "Error : ".$e;
    return false;
  }
  }

function client_connected(){
  if (isset($_SESSION) && isset($_SESSION["client_id"])){
    return $_SESSION["client_id"];
  } else {
    false;
  }
}

function client_connected_or_redirect(){
  if (isset($_SESSION) && isset($_SESSION["client_id"])){
    return $_SESSION["client_id"];
  } else {
    $_SESSION["last_page"] = $_SERVER["REQUEST_URI"];
    header("Location: login.php");
    exit();
  }
}

function buisness_connected(){
  if (isset($_SESSION) && isset($_SESSION["business_id"])){
    return $_SESSION["business_id"];
  } else {
    false;
  }
}

function buisness_connected_or_redirect(){
  if (isset($_SESSION) && isset($_SESSION["business_id"])){
    return $_SESSION["business_id"];
  } else {
    $_SESSION["last_page"] = $_SERVER["REQUEST_URI"];
    header("Location: login.php");
    exit();
  }
}

function client_disconnect(){
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }
  if (isset($_SESSION["client_id"])) {
      unset($_SESSION["client_id"]);
  }
  session_unset();
  session_destroy();
}

function business_disconnect(){
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }
  if (isset($_SESSION["business_id"])) {
      unset($_SESSION["business_id"]);
  }
  session_unset();
  session_destroy();
}

function redirect(){
  if (isset($_SESSION["last_page"])){
    header('Location: '.$_SESSION["last_page"]);
  } else {
    header('Location: index.php');
  }
  exit();
}

function redirect_business(){
  if (isset($_SESSION["last_page"])){
    header('Location: '.$_SESSION["last_page"]);
  } else {
    header('Location: index.php');
  }
  exit();
}

?>