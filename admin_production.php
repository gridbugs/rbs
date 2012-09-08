<?
include_once('includes/utilities.php');
$link = db_connect();
include_once('includes/adminauth.php');
include_once('includes/prodmanagement.php');
include_once('includes/bookingmanagement.php');

if(isset($_GET['production']) && production_exists($link, $_GET['production']))
	$_SESSION['admin_production'] = $_GET['production'];

if(!isset($_SESSION['admin_production'])) {
	exit;
}

$production = get_production($link, $_SESSION['admin_production']);

$tickettotals = get_ticket_totals($link, $_SESSION['admin_production']);
?>

<html><head><title>Production Info for <?=$production['name']?></title></head>
<body>
<h1>Production Info for <?=$production['name']?></h1>
<p><a href="admin_booking.php">Administration Booking Screen</a></p>
<p><a href="admin_bookinglist.php">Booking List</a></p>
<p><a href="admin_editproduction.php">Edit Production Details</a></p>
<h2>Ticket Totals:</h2>
<?
foreach ( $tickettotals as $tt ) {
?>
<h2><?=$tt['title']?></h2>
<ul>
<li>Booked seats: <?=$tt['bookedseats']?></li>
<li>Paid seats: <?=$tt['paidseats']?></li>
<li>Confirmed seats: <?=$tt['confirmedseats']?></li>
<li>Payment Pending seats: <?=$tt['ppseats']?></li>
<li>VIP seats: <?=$tt['vipseats']?></li>
<li>ARC seats: <?=$tt['arcseats']?></li>
</ul></p>
<p>Total non-booked: <?=$tt['paidseats'] + $tt['confirmedseats'] + $tt['ppseats'] + $tt['vipseats']?></p>
<?
}
?>
</body>
</html>
