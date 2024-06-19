<?php
require_once "../../utils.php";
$token = $_GET['token'];
$scope = request("SELECT date_debut, date_fin FROM sae._ical_token WHERE token='$token'",1);

if(!empty($events)){
    $logements = request("SELECT logement FROM sae._ical_token_logements WHERE token='$token'");
    
    foreach($logements as $id_logement){
        
        $sql = 'SELECT statut FROM sae._calendrier c WHERE c.date >= ' . $scope['date_debut'];
        $sql .= ' AND c.date <= ' . $scope['date_fin'];
        $sql .= ' WHERE id_logement = ' . $id_logement;
        $status = request($sql);
        foreach($status as $s){
            print $s;
        }
        
    }
/*
    header('Content-Type: text/calendar; charset=utf-8');
    header('Content-Disposition: attachment; filename="cal.ics"');

    print "BEGIN:VCALENDAR\n";
    print "VERSION:2.0\n";
    print "PRODID:-//Your Company//Your Product//EN\n";

    foreach($events as $event) {
        print "BEGIN:VEVENT\n";
        print "UID:" . uniqid() . "@yourdomain.com\n";
        print "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\n";
        print "DTSTART:" . gmdate('Ymd\THis\Z', strtotime($event['date_debut'])) . "\n";
        print "DTEND:" . gmdate('Ymd\THis\Z', strtotime($event['date_fin'])) . "\n";
        print "SUMMARY:" . $event['type'] . "\n";
        print "DESCRIPTION:Client Info: " . $event['client_info'] . ", Property Info: " . $event['property_info'] . "\n";
        print "END:VEVENT\n";
    }
    print "END:VCALENDAR";*/
}else{}



?>
