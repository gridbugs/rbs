<?
include_once('includes/utilities.php');
$link = db_connect();
include_once('includes/adminauth.php');
include_once('includes/prodmanagement.php');
include_once('includes/bookingmanagement.php');

if(isset($_GET['production']) && production_exists($link, $_GET['production'])) {
	check_access_to_production($_GET['production']);
    $_SESSION['admin_production'] = $_GET['production'];
}

if(!isset($_SESSION['admin_production'])) {
	exit;
}


$production = get_production($link, $_SESSION['admin_production']);

$tickettotals = get_ticket_totals($link, $_SESSION['admin_production']);
?>

<?php include ('includes/groundwork-header.php') ?>  

<?php include('includes/page-header.php') ?>

      <article class="row">
        <section class="padded">
<h1>Production Information for <?=$production['name']?></h1>
<h2>Ticket Totals:</h2>
<div class="row">
<?
foreach ( $tickettotals as $tt ) {
?>
<div class="one fourth">
<h3><?=$tt['title']?></h3>
<ul>
<li>Booked seats: <?=$tt['bookedseats']?></li>
<li>Paid seats: <?=$tt['paidseats']?></li>
<li>Confirmed seats: <?=$tt['confirmedseats']?></li>
<li>Payment Pending seats: <?=$tt['ppseats']?></li>
<li>VIP seats: <?=$tt['vipseats']?></li>
</ul></p>
<p><strong>Total confirmed or paid:</strong> <?=$tt['paidseats'] + $tt['confirmedseats'] + $tt['ppseats'] + $tt['vipseats']?></p></div>

<?php
    if (isset($tt['confirmed']) and count($tt['confirmed']) > 0){
        echo "</div><strong>Total confirmed/paid by price class:</strong><ul>";
        foreach ($tt['confirmed'] as $price){
            echo "<li class='margin-left: 5em;'>".$price['name'].": ".$price['count']."</li>";
        }
        echo "</ul>";
    }
}
?>

        </section>
      </article>

<?php include('includes/page-footer.php') ?>

