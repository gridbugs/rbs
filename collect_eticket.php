
<?
include_once('includes/utilities.php');
$link = db_connect();
include_once('includes/adminauth.php');

$ticket = $_POST["ticket"];
$booking = $_POST["booking"];

print $ticket;
if(!empty($_POST["submitBooking"])){
	$sql = "update bookedseat set collected=1 where booking = '$booking'";
} else {
	$sql = "update bookedseat set collected=1 where guid = '$ticket'";
}
	$results = mysql_query($sql, $link);
	header("Location: confirm_eticket.php?id=" . $ticket);
?>


