<?php 
function request($sql){
require "connect_db/connect_param.php";

try {
  $connexion = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
  $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


  $requete = $connexion->prepare($sql);

  $requete->execute();
  $results = $requete->fetchAll(PDO::FETCH_ASSOC);

  $connexion = null;
  return $results;

} catch(PDOException $e) {
  $connexion = null;
  echo "Error : ".$e;
  return false;
}
}

?>