<?
/**
 * This page displays a summary of the booked seats
 * and gives the user the option of choosing the type of ticket and entering other details.
 * (if there's more than one type of ticket)
 */


include_once('includes/utilities.php');
$link = db_connect();
include_once('includes/adminauth.php');

include_once('includes/prodmanagement.php');
include_once('includes/bookingmanagement.php');
include_once('includes/pricemanagement.php');
include_once('includes/frames/priceselection.php');

$production = get_production($link, $_SESSION['admin_production']);

check_access_to_production($_SESSION['admin_production']);

$user = $_SESSION['admin_id'];
include_once('includes/theatres/' . $production['theatre'] . '.inc');

$htmlheaders = '<link rel="stylesheet" type="text/css" href="css/bookingsummary.css" />';

$message = "";

// Receive a new booking if one's been submitted
if(isset($_POST['modify']) || isset($_POST['new']))
{

	$performance = (int)$_POST['performance'];
	if(isset($_POST['modify']))
		$bookingid = $_POST['booking'];
	else {
		$bookingid = create_user_booking($link, $performance, $_SESSION['admin_id'], 1);
		if($bookingid === null) {
			echo('<p class="error message red">Creating the booking failed!</p>');
			exit;
		}
	}

	if(isset($_POST['changeseat'])) {

		$results = admin_save_changes($link, $production['id'], $_SESSION['admin_id'], $bookingid, $_POST['changeseat'], $theatre);

		

		if(count($results) != 0) {
		
		$message .= "<p class='error message red'>Unfortunately there is a problem with this booking</p>";
		}
		foreach($results as $performance => $seats) {
			foreach($seats as $seat => $reason) {
				$message .= "<p class='seattaken'><strong>$seat:</strong>  $reason</p>";
			}
		}
			
	}
}

include('includes/groundwork-header.php');
include('includes/page-header.php');
?>

<? if (count($results) != 0) {
    echo "<div class='error box'>$message</div>";
	}?>

<form method="post" action="admin_savebooking.php">
<input type="hidden" name="submitprices" value="true">
<?
$booking = get_booking($link, $bookingid);
print_price_selection($link, $booking, true);
?>
<div class="bookingupdate row">
	<div class="bookinglabel one sixth padded">Name:</div>
	<div class="bookinginput two sixths padded"><input name="name" class="bookingta"><?=htmlspecialchars($booking['name'])?></textarea></div>
	<div class="bookinglabel one sixth padded clear">Description:</div>
	<div class="bookinginput two sixths padded"><textarea name="description" class="bookingta"><?=htmlspecialchars($booking['description'])?></textarea></div>
	<div class="bookinglabel one sixth padded clear">Phone Number:</div>
	<div class="bookinginput two sixths padded"><input type="text" name="phonenumber" class="bookingin" value="<?=htmlspecialchars($booking['phonenumber'])?>"></div>
	<div class="bookinglabel one sixth padded clear">Email Address:</div>
	<div class="bookinginput two sixths padded"><input type="email" name="email" class="bookingin" value="<?=htmlspecialchars($booking['email'])?>"></div>
</div>
<!--
<div class="bookingupdate">
	<div class="bookinglabel">Has it been picked up?:</div>
	<div class="bookinginput"><input type="checkbox" name="pickedup" class="bookingcb" <? if($booking['phonenumber']) echo("CHECKED");?>></div>
</div>
<div class="bookingupdate">
	<div class="bookinglabel">Amount Paid:</div>
	<div class="bookinginput">$<input name="amountpaid" class="bookingin" value="<?=$booking['amountpaid']?>"></div>
</div>
<div class="bookingupdate">
	<div class="bookinglabel">Discount:</div>
	<div class="bookinginput">$<input name="discount" class="bookingin" value="<?=$booking['discount']?>"></div>
</div>
<div class="bookingupdate">
	<div class="bookinglabel">Deadline:</div>
	<div class="bookinginput"><input name="deadline" class="bookingin" value="<?=$booking['deadline']?>"></div>
</div>
<div class="bookingupdate">
	<div class="bookinglabel">Email Sent:</div>
	<div class="bookinginput"><input name="emailsent" class="bookingin" value="<?=$booking['emailsent']?>"></div>
</div>
--!>
<input type="hidden" name="bookingid" value="<?=$bookingid?>">
<?
if(isset($_POST['tosegment']))
	echo('<input type="hidden" name="tosegment" value="' . (int)$_POST['tosegment'] . '">');
if(isset($_POST['performance']))
	echo('<input type="hidden" name="performance" value="' . (int)$_POST['performance'] . '">');
if(isset($_POST['fulltheatre']))
	echo('<input type="hidden" name="fulltheatre" value="true">');
?>
<p class="savebooking"><input type="submit" value="Save The Booking"></p>
</form>
<? include('includes/page-footer.php');?>
