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

/* check if the user actually has admin access to this show */
if(isset($_SESSION['admin_prodlist']) && (int)$_SESSION['admin_superadmin'] == 0) {
    $can_access = false;
    /* get the show they're trying to access */
    $current_prod = $_SESSION['admin_production'];

    /* go through each production they can access and stop
     * when the current production is found
     */
    foreach ($_SESSION['admin_prodlist'] as $prod) {
        if ($prod == $current_prod) {
            /* found it! */
            $can_access = true;
            break;
        }
    }

    if (!$can_access) {
        /* they don't have permission to access this show */
        die("This hack is no longer supported. Talk to webmin to get admin access to the current production.");
    }

}

$production = get_production($link, $_SESSION['admin_production']);

$tickettotals = get_ticket_totals($link, $_SESSION['admin_production']);
?>

<html><head><title>Production Info for <?=$production['name']?></title></head>
<body style='padding-bottom:100px;'>
<h1>Production Info for <?=$production['name']?></h1>
<p><a href="admin_booking.php">Administration Booking Screen</a></p>
<p><a href="admin_bookinglist.php">Booking List</a></p>
<p><a href="admin_editproduction.php">Edit Production Details</a></p>
<p><a href="logout.php">Logout</a></p>
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
</ul></p>
<p><strong>Total confirmed or paid:</strong> <?=$tt['paidseats'] + $tt['confirmedseats'] + $tt['ppseats'] + $tt['vipseats']?></p>
<?php
    if (isset($tt['confirmed']) and count($tt['confirmed']) > 0){
        echo "<strong>Total confirmed/paid by price class:</strong><ul>";
        foreach ($tt['confirmed'] as $price){
            echo "<li class='margin-left: 5em;'>".$price['name'].": ".$price['count']."</li>";
        }
        echo "</ul>";
    }
}
?>
</body>
</html>
