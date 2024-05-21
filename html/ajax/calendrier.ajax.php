<?php

// on fera la requete sql ici

require_once '../../utils.php';


$id = $_GET["id"];
$sql = 'SELECT r.date_debut, r.date_fin FROM sae._reservation r';
$sql .= ' WHERE r.id_logement = ' . $id . ' AND r.annulation = false';
$ret = request($sql);

if($ret === false){
    print 'Erreur requête';
}else{

    $date = array();

   foreach($ret as $val){
        $date[] = array($val['date_debut'],$val['date_fin']);
       
   }   

    print json_encode($date);
}