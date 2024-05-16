<?php

require_once '../utils.php';

define('FRAIS',1.01);
define('TAUX',1);

$id = 1;
$sql = 'SELECT base_tarif FROM sae._logement';
$sql .= ' WHERE id = ' . $id;
$res = request($sql,1);
$base_tarif = $res['base_tarif'];

$nombre_personne = (int) $_GET['nombrePersonne'];
$reservArrDate = new DateTime($_GET['dateDebut']);
$reservDepDate = new DateTime($_GET['dateFin']);
    
$interval = $reservArrDate->diff($reservDepDate);   
$base_tarif = $res['base_tarif'];
$jour = $interval->days;
                                    
$prix_ht = $base_tarif * (empty($jour) ? 1 : $jour);
$nuit = empty($jour) ? 0 : $jour - 1;
$frais = ($prix_ht * FRAIS) - $prix_ht;
$taxe = $nuit * TAUX * $nombre_personne;
                                
$prix_ttc = $prix_ht + $frais + $taxe;

$response = array(
    'base_tarif' => $base_tarif,
    'prix_ht' => $prix_ht,
    'prix_ttc'=>$prix_ttc,
    'frais' =>$frais,
    'taxe'=>$taxe,
    'nombre_jour' => $jour,
);

print json_encode($response);

?>
