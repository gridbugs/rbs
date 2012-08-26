<?
/**
 * This script expires tickets which removes tickets which have passed their deadline.
 */

include('../includes/utilities.php');
include("../includes/prodmanagement.php");
include("../includes/bookingmanagement.php");

$link = db_connect();

//$et = email_prod_admins($link, 1, "hi there now", "This is a test message emailing all production admins");

// First find tickets which will expire and are for performances which have autoexpire turned on.
$sql = "SELECT b.id, u.name, u.email, p.date, p.production, p.title, b.deadline, b.bookedtime, count(bs.id) seats";
$sql .= " FROM user u, performance p, booking b, bookedseat bs";
$sql .= " WHERE b.deadline < NOW() AND b.user = u.id AND b.performance = p.id AND p.autoexpire = 1";
$sql .= " AND bs.booking = b.id AND bs.status = 1";
$sql .= " GROUP BY b.id";

$results = sql_get_array($link, $sql);

// We need to sort them into productions so we can email en mass
$bookings = array();

foreach($results as $result) {
	$bookings[$result['production']][] = $result;
}

foreach($bookings as $production => $bookinglist) {
	// Create the email and expire the bookings.
	$email = "";
	foreach($bookinglist as $booking) {
		$email .= "Expired booking number " . $booking['id'] . ".\r\n";
		$email .= "User " . $booking['name'] . " with " . $booking['seats'] . " seats expired";
		$email .= " for performance " . $booking['date'] . ": " . $booking['title'] . "\r\n";
		$email .= "The deadline was at " . $booking['deadline'] . "\r\n\r\n";
		expire_booking($link, $booking['id']);
	}
	echo($email);
	email_prod_admins($link, $production, "Expired Tickets for production " . $booking['production'], $email);
}

?>
