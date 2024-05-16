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

function client_connected(){
  if (isset($_SESSION) && isset($_SESSION["client_id"])){
    return $_SESSION["client_id"]; unset($_SESSION["client_id"])
  } else {
    false;
  }
}

function client_connected_or_redirect(){
  if (isset($_SESSION) && isset($_SESSION["client_id"])){
    return $_SESSION["client_id"];
  } else {
    header("Location: login.php");
    exit();
  }
}

function buisness_connected(){
  if (isset($_SESSION) && isset($_SESSION["buisness_id"])){
    return $_SESSION["buisness_id"];
  } else {
    false;
  }
}

function buisness_connected_or_redirect(){
  if (isset($_SESSION) && isset($_SESSION["buisness_id"])){
    return $_SESSION["buisness_id"];
  } else {
    header("Location: login.php");
    exit();
  }
}

?>