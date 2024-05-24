<?php

require_once '../../utils.php';

define('FRAIS',1.01);
define('TAUX',1);



$id = $_GET["id"];
$sql = 'SELECT base_tarif FROM sae._logement';
$sql .= ' WHERE id = ' . $id;
$res = request($sql,1);
$base_tarif = $res['base_tarif'];

$nombre_personne = (int) $_GET['nombrePersonne'];
$reservArrDate = new DateTime($_GET['dateDebut']);
$reservDepDate = new DateTime($_GET['dateFin']);
    
$interval = $reservArrDate->diff($reservDepDate);   
$base_tarif = $res['base_tarif'];
$jour = $interval->days + 1;
                                    
$prix_ht = $base_tarif * (empty($jour) ? 1 : $jour) * $nombre_personne;
$nuit = empty($jour) ? 0 : $jour - 1;
$frais = ($prix_ht * FRAIS) - $prix_ht;
$taxe = $nuit * TAUX * $nombre_personne;
                                
$prix_ttc = $prix_ht + $frais + $taxe;

$response = array(
    'base_tarif' => round($base_tarif, 3),
    'prix_ht' => round($prix_ht, 3),
    'prix_ttc'=> round($prix_ttc, 3),
    'frais' => round($frais, 3),
    'taxe'=>round($taxe, 3),
    'nombre_jour' => $jour,
    'nombre_nuit'=> $nuit,
);

print json_encode($response);

?>