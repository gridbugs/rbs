<link rel="stylesheet" type="text/css" href="show_data/2014_med/MR14eticket.css" />
<div class="eticket">

<?php
include('includes/settings.php');
include('includes/utilities.php');
include('includes/newutils.php');
include('includes/prodmanagement.php');
include('includes/paymentmanagement.php');
error_reporting(-1);
$ticket_id = $_GET["ticket_id"];
$seat = substr($ticket_id, 13);
$id = substr($ticket_id,0, 13);
if($ticket_id == NULL){
    print "error";
}else {

    //set it to writable location, a place for temp generated PNG files
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;

    //html PNG location prefix
    $PNG_WEB_DIR = 'temp/';

    include "phpqrcode/qrlib.php";    

    //ofcourse we need rights to create temp dir
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);

    $filename = $PNG_TEMP_DIR.$id.'.png';
    $errorCorrectionLevel = 'L';
    $matrixPointSize = 6;

        QRcode::png('rbs.cserevue.org.au/confirm_eticket.php?id='.$id, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
        
    echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" class=""ticketqrcode"/><hr/>';  
 
    $db = db_connect_pdo();
    $stmt = $db->prepare(<<<EOT
SELECT p.*, UNIX_TIMESTAMP(p.date) tsdate
FROM bookedseat bs JOIN booking b JOIN performance p
ON (bs.booking = b.id AND b.performance = p.id)
WHERE bs.guid = :guid
EOT
);
    if (!$stmt->execute(array(':guid' => $id))) {
        echo "sql error";
    }

    $performance = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo "<div class=\"ticketdata\">";
	echo "<p class=\"ticketseat\">Seat: $seat</p>";
    //echo($performance[0]['title']);
    echo "<p class=\"ticketperformance\">";
    echo(prettydate($performance[0]['tsdate']));
	echo "</p>";
}   
?>
</div>
<div class="sponsors">
<p>Please print out this ticket, or display it on a device, upon arrival at the Science Theatre.<br />
Please ensure that all group members have a copy of their ticket.</p>
</div>