<?
/**
 * Displays the payment summary, accepts the ticket price data from bookingsummary.php.
 * If the ticketing information is incomplete then push the user back to the booking summary page.
 * If there have been no bookings push the user straight to the bookings page.
 */

include_once('includes/utilities.php');
$link = db_connect();

include_once('includes/userauth.php');

include_once('includes/prodmanagement.php');
include_once('includes/pricemanagement.php');
include_once('includes/paymentmanagement.php');
include_once('includes/perfmanagement.php');
include_once('includes/usermanagement.php');
include_once('includes/frames/paymentsummary.php');
include_once('includes/frames/prodtheme.php');
include_once('paypalbutton.php');


$user = $_SESSION['user_id'];
$userinfo = get_user($link, $user);

// Get the prices from the booked seats
if(isset($_POST['price'])) {
	foreach($_POST['price'] as $seat => $price) {
		set_price_user($link, $user, $seat, $price);
	}
}

$ps = get_payment_summary($link, $_SESSION['user_id']);
if(!$ps || count($ps) == 0) {
	header('Location: booking.php');
	exit;
}

$htmlheaders = '<link rel="stylesheet" type="text/css" href="css/paymentsummary.css" /><script type="text/javascript" src="js/paymentsummary.js" ></script><script src="js/jquery.js" type="test/javascript"></script><script src="js/jquery.anchor.js" type="test/javascript"></script>';

$production = get_production($link, $_SESSION['production']);
print_prod_header($link, $production, $htmlheaders);

?>

<h1>Payment Summary</h1>

<p id="booktickets"><a href="booking.php">Click here to modify your bookings.</a></p>

<?
$total = 0;
foreach($ps as $perfid => $summary) {
	// If there's no payment summary yet then don't display it.
	$displayps = false;
	foreach($summary as $sc) {
		foreach($sc as $row) {
			if($row['num'] > 0){
				$displayps = true;
				break;
			}
		}
	}
	if(!$displayps)
		continue;
	$perf = get_performance($link, $perfid);
	echo("<div class='paymentsummaryperf'>");
	echo("<h2>" . $perf['title'] . "</h2>");
	print_payment_summary($link, $summary);

	if(isset($summary[1])) {
		foreach($summary[1] as $row) {
			$total += $row['price'] * $row['num'];
		}
	}
	echo("</div>");
}
?>

<h2>Total</h2>
<div class="paymenttotal">Total Amount Due: $<?=$total?></div>

<h2>How would you like to pay?</h2>
<div id="paymentoptions">
<?
if($production['acceptsales'] == 1) echo('<a class="paymentoption bigbutton" id="paybysales" href="#sales" onClick="togglePay(\'sales\');">Sales Desk</a>');
if($production['acceptdd'] == 1) echo('<a class="paymentoption bigbutton" id="paybydd" href="#dd" onClick="togglePay(\'dd\');">Bank Transfer</a>');
if($production['acceptpaypal'] == 1) echo('<a href="#paypal" class="paymentoption bigbutton" onClick="togglePay(\'paypal\');">Paypal or Credit Card</a>');
?>
</div>
<a name="anchor" id="anchor"></a>
<?
if($production['acceptsales']) {
	$salesinfo = $production['salesinfo'];
	$salesinfo = str_replace('{paymentid}', $userinfo['paymentid'], $salesinfo);
	echo('<div class="pay" id="salesinfo"><a name="sales"></a>' . $salesinfo . '</div>');
}
if($production['acceptdd']) {
	$ddinfo = $production['ddinfo'];
	$ddinfo = str_replace('{paymentid}', $userinfo['paymentid'], $ddinfo);
	echo('<div class="pay" id="ddinfo"><a name="dd"></a>' . $ddinfo . '</div>');
}
if($production['acceptpaypal']) {
	echo('<div class="pay" id="paypalinfo"><a name="paypal"></a><h2>Purchase Tickets with Paypal</h2><center>');
	if($total > 0) {
		$userRow = get_user($link, $user);
		generate_paypal_button($production, $userRow['paymentid'], $total);
	}
	echo('</center></div>');
}
?>

</div>
</div>

<?php

print_prod_footer($link, $production);
?>
