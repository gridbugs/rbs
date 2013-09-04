<?
include_once('includes/utilities.php');
$link = db_connect();
include_once('includes/adminauth.php');

$ticket_id = $_GET["id"];

    print "<html>";
    print "<head>";
    print "<meta id='meta' name='viewport' content='width=device-width' initial-scale=1.0 />";
    print "</head>";
    print "<body>";
	$sql = "select * from bookedseat where guid = '$ticket_id'";
	$results = sql_get_array($link, $sql );
    if ($results == null){
        print "<h1>error see sales desk</h1>";
    }
    print "<br>";
    print $results[0][seat] . " - " .$results[0][collected];
    print "<br>";
    print "<form method='POST' action='collect_eticket.php'>";
    print "<input type='hidden' name='ticket' value='$ticket_id'>";
    print "<input type='submit' name='submitTicket' value='ticket'>";
    print "<br>";
    print "<br>";
    print "<br>";
    $bookingid = $results[0][booking];
	$sql = "select seat, id, collected from bookedseat where booking = $bookingid";
	$results = sql_get_array($link, $sql );
    foreach( $results as $seat){
        print $seat[seat] . " - ". $seat[collected];
        print "<br>";
    }
    print "<form method='POST' action='collect_eticket.php'>";
    print "<input type='hidden' name='ticket' value='$ticket_id'>";
    print "<input type='hidden' name='booking' value='$bookingid'>";
    print "<input type='submit' name='submitBooking' value='booking'>";
    print "</body>";
    print "</html>";
?>


