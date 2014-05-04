<?
/**
 * TODO: make sure only proper admins have access to each production specific page
 * TODO: Cleanup the printing checkboxes etc.  The code can be a lot cleaner
 */

include_once('includes/utilities.php');
$link = db_connect();
include_once('includes/adminauth.php');

include_once('includes/perfmanagement.php');
include_once('includes/prodmanagement.php');
include_once('includes/bookingmanagement.php');
include_once('includes/frames/priceselection.php');

if(isset($_GET['production']) && production_exists($link, $_GET['production'])) {
	check_access_to_production($_GET['production']);
    $_SESSION['admin_production'] = $_GET['production'];
}

if(!isset($_SESSION['admin_production'])) {
	echo("<div class=\"error\"><p>Production does not exist</p></div>");
	exit;
}

$production = get_production($link, $_SESSION['admin_production']);



$performances = get_performances($link, $production['id']);

if(isset($_POST['restrictperf'])) {
	foreach($performances as $performance) {
		if($performance['id'] == $_POST['restrictperf']) {
			$perftitle = prettydate($performance['tsdate']);
			break;
		}
	}
}

include('includes/groundwork-header.php');
include('includes/page-header.php');
 ?>
<?
if(isset($perftitle) && $perftitle != '') {
	echo("<h2>Booking list for $perftitle</h2>");
} else {
	echo ("<h2>Booking list</h2>"); }
?>

<?
if(isset($_POST['sortby']))
	$sb = $_POST['sortby'];
if(isset($_POST['order']))
	$order = $_POST['order'];

if(isset($_POST['restrictstatus'])) {
	$rs = $_POST['restrictstatus'];
	if(isset($rs[11])) {
		$rs[4] = true;
		$rs[5] = true;
		$rs[6] = true;
		$rs[7] = true;
	}

	if(isset($rs[12])) {
		$rs[1] = true;
		$rs[3] = true;
		$rs[4] = true;
		$rs[5] = true;
		$rs[6] = true;
		$rs[7] = true;
		$rs[8] = true;
		$rs[9] = true;
		$rs[10] = true;
	}
} else
	$rs = array();

if(isset($_POST['restrictpickedup']))
	$rpu = $_POST['restrictpickedup'];
else
	$rpu = -1;

if(isset($_POST['showcolumn']))
	$showcolumn = $_POST['showcolumn'];
else
	$showcolumn = array();

$ia = isset($_POST['includeadmin']);

$sc = $showcolumn;

if(!isset($_POST['printing'])) {
?>

<form method="post" class="bookinglist" action="admin_bookinglist.php">
<div class="row">
<div class="one third padded"><strong>Select bookings from the following performance:</strong><br />
<select name="restrictperf">
	<option value="-1">All Performances</option>
<?
foreach($performances as $performance) {
	echo("<option value='" . $performance['id'] . "'");
	if(isset($_POST['restrictperf']) && $_POST['restrictperf'] == $performance['id'])
		echo(" selected='selected'");
	echo(">" . prettydate($performance['tsdate']) . "</option>");
}
?>

</select>
</div>

<!--<div class="formentry">Show only bookings with the following picked up status:
<select name="restrictpickedup">
	<option value="-1"<?if($rpu == -1) echo(" selected='selected'")?>>All Bookings</option>
	<option value="0"<?if($rpu == 0) echo(" selected='selected'")?>>Not Picked Up</option>
	<option value="1"<?if($rpu == 1) echo(" selected='selected'")?>>Picked Up</option>
</select>
</div>-->

<? if (!isset($sb)) $sb = ''; ?>
<? if (!isset($order)) $order = ''; ?>
<div class="one third padded"><strong>Sort by:</strong><br />
<select name="sortby">
	<option value="paymentid"<?if($sb == 'paymentid') echo(" selected='selected'")?>>Payment ID Number</option>
	<option value="id"<?if($sb == 'id') echo(" selected='selected'")?>>ID Number</option>
	<option value="name"<?if($sb == 'name') echo(" selected='selected'")?>>Name</option>
	<option value="deadline"<?if($sb == 'deadline') echo(" selected='selected'")?>>Payment Deadline</option>
	<option value="bookedtime"<?if($sb == 'bookedtime') echo(" selected='selected'")?>>Booked Time</option>
</select>
<select name="order">
	<option value="asc"<?if($order == 'asc') echo(" selected='selected'")?>>Ascending</option>
	<option value="desc"<?if($order == 'desc') echo(" selected='selected'")?>>Descending</option>
</select>
</div>

<div class="one third padded"><strong>Other options</strong><br />
<input type="checkbox" name="includeadmin" <?
    if (isset($_POST['includeadmin']) ||$_SERVER['REQUEST_METHOD'] == 'GET') {
        echo 'checked="checked"';
    }
?>/>Include tickets that have been booked by an admin<br />
<input type="checkbox" name="printing" />Print friendly booking list (hides these controls)
</div>

<div class="three thirds padded"><strong>Select only bookings with this payment status:</strong>
<?php
if (!isset($rs) || count($rs) == 0){
    $rs[12] = true;
}
?>
	<input type="checkbox" name="restrictstatus[1]"<?if(isset($rs[1])) echo(" checked='checked'")?>>Booked
	<input type="checkbox" name="restrictstatus[3]"<?if(isset($rs[3])) echo(" checked='checked'")?>>Confirmed
	<input type="checkbox" name="restrictstatus[4]"<?if(isset($rs[4])) echo(" checked='checked'")?>>Paid
	<input type="checkbox" name="restrictstatus[5]"<?if(isset($rs[5])) echo(" checked='checked'")?>>Paid Sales Desk
	<input type="checkbox" name="restrictstatus[9]"<?if(isset($rs[9])) echo(" checked='checked'")?>>Unavailable
	<input type="checkbox" name="restrictstatus[10]"<?if(isset($rs[10])) echo(" checked='checked'")?>>VIP
	<input type="checkbox" name="restrictstatus[7]"<?if(isset($rs[11])) echo(" checked='checked'")?>>Paypal
	<input type="checkbox" name="restrictstatus[12]"<?if(isset($rs[12])) echo(" checked='checked'")?>>All Tickets
</div>

<div class="three thirds padded"><strong>Show the following columns:</strong>
<?php
if (!isset($sc) || count($sc) == 0){
    $sc['bookingid'] = true;
    $sc['performance'] = true;
    $sc['name'] = true;
    $sc['email'] = true;
    $sc['seats'] = true;
    $sc['phoneno'] = true;
    $sc['totalcost'] = true;
    $sc['description'] = true;
}
?>
	<input type="checkbox" name="showcolumn[bookingid]"<?if(isset($sc['bookingid'])) echo(" checked='checked'")?>>Booking ID
	<input type="checkbox" name="showcolumn[performance]"<?if(isset($sc['performance'])) echo(" checked='checked'")?>>Performance
	<input type="checkbox" name="showcolumn[name]"<?if(isset($sc['name'])) echo(" checked='checked'")?>>Name
	<input type="checkbox" name="showcolumn[email]"<?if(isset($sc['email'])) echo(" checked='checked'")?>>Email
	<input type="checkbox" name="showcolumn[seats]"<?if(isset($sc['seats'])) echo(" checked='checked'")?>>Seats
	<input type="checkbox" name="showcolumn[desc]"<?if(isset($sc['desc'])) echo(" checked='checked'")?>>Description
	<input type="checkbox" name="showcolumn[phoneno]"<?if(isset($sc['phoneno'])) echo(" checked='checked'")?>>Phone Number
	<input type="checkbox" name="showcolumn[totalcost]"<?if(isset($sc['totalcost'])) echo(" checked='checked'")?>>Total Cost
	<input type="checkbox" name="showcolumn[amountpaid]"<?if(isset($sc['amountpaid'])) echo(" checked='checked'")?>>Amount Paid
	<input type="checkbox" name="showcolumn[bookedtime]"<?if(isset($sc['bookedtime'])) echo(" checked='checked'")?>>Booked Time
</div>

<div class="one third"><input type="submit" value="Get Bookings"></div>
</div>
</form>

<?
} // Printing

if(!isset($_POST['restrictperf'])) { // If the user hasn't clicked "Get Bookings"
	include('includes/page-footer.php');
	exit;
} 

// Get the bookings
$restrictions = array();
$restrictions['performance'] = $_POST['restrictperf'];
$restrictions['status'] = $_POST['restrictstatus'];
//$restrictions['pickedup'] = $_POST['restrictpickedup'];

$sortby = $_POST['sortby'];
$order = $_POST['order'];
$bookings = get_all_bookings($link, $production['id'], $sortby, $order, $restrictions, $ia);
$nbookings = count($bookings);
$nseats = 0;

?>

<table class="sortable" id="bookinglist">
	<tr>
<?
if(isset($showcolumn['bookingid']))
	echo('<th>Booking ID</th>');
if(isset($showcolumn['paymentid']))
	echo('<th>Payment ID</th>');
if(isset($showcolumn['performance']))
	echo('<th>Performance</th>');
if(isset($showcolumn['name']))
	echo('<th>Name</th>');
if(isset($showcolumn['email']))
	echo('<th>Email</th>');
if(isset($showcolumn['seats']))
	echo('<th>Seats Booked</th>');
if(isset($showcolumn['totalcost']))
	echo('<th>Total Price</th>');
if(isset($showcolumn['desc']))
	echo('<th>Description</th>');
if(isset($showcolumn['phoneno']))
	echo('<th>Phone Number</th>');
if(isset($showcolumn['pickedup']))
	echo('<th>Picked Up</th>');
if(isset($showcolumn['amountpaid']))
	echo('<th>Amount Paid</th>');
if(isset($showcolumn['discount']))
	echo('<th>Discount</th>');
if(isset($showcolumn['deadline']))
	echo('<th>Payment Deadline</th>');
if(isset($showcolumn['bookedtime']))
	echo('<th>Booked Time</th>');
echo('<th>Booked by</th>');
?>
	</tr>
<?
foreach($bookings as $booking) {
	echo("<tr>");
	if(isset($showcolumn['bookingid']))
		echo("<td>" . $booking['id'] . "</td>");
	if(isset($showcolumn['paymentid']))
		echo("<td>" . htmlspecialchars(strtoupper($booking['paymentid'])) . "&nbsp;</td>");
	if(isset($showcolumn['performance']))
		echo("<td>" . prettydate($booking['tsdate']) . "</td>");
	if(isset($showcolumn['name']))
		echo("<td>" . htmlspecialchars($booking['name']) . "</td>");
	if(isset($showcolumn['email']))
		echo("<td>" . htmlspecialchars($booking['email']) . "</td>");
	if(isset($showcolumn['seats'])) {
		echo("<td>");
		$nseats += count($booking['seats']);
        $total_cost = 0;
		foreach($booking['seats'] as $seat) {
            if ($seat['status'] > 1){
                echo "<span style='color:green; font-weight:bold;'>";
            } else {
                echo "<span style='color:red'>";
            }
			echo($seat['seat']);
            $price = get_price_by_id($link, $seat['price']);
			echo(" (" . status_message($seat['status']) . ")");
            echo " <small>".$price[0]['name']."</small>";
            $total_cost += (int)$price[0]['price'];
            echo "</span><br/>";
		}
		echo("</td>");
	}
    if(isset($showcolumn['totalcost']))
        echo "<td>\$$total_cost</td>";
	if(isset($showcolumn['desc']))
		echo("<td>" . htmlspecialchars($booking['description']) . "&nbsp;</td>");
	if(isset($showcolumn['phoneno'])) {
		if($booking['phonenumber'])
			echo("<td>" . htmlspecialchars($booking['phonenumber']) . "&nbsp;</td>");
		else
			echo("<td>" . htmlspecialchars($booking['phone']) . "&nbsp;</td>");
	}
	if(isset($showcolumn['pickedup'])) {
		if($booking['pickedup'])
			echo("<td>Yes</td>");
		else
			echo("<td>No</td>");
	}
	if(isset($showcolumn['amountpaid']))
		echo("<td>$" . htmlspecialchars($booking['amountpaid']) . "</td>");
	if(isset($showcolumn['discount']))
		echo("<td>$" . htmlspecialchars($booking['discount']) . "</td>");
	if(isset($showcolumn['deadline']))
		echo("<td>" . htmlspecialchars($booking['deadline']) . "</td>");
	if(isset($showcolumn['bookedtime']))
		echo("<td>" . htmlspecialchars($booking['bookedtime']) . "</td>");
	echo ("<td>" . htmlspecialchars($booking['user']) . "</td>");
 	echo("</tr>");
}

echo("</table>");

/*echo("<p>Total number of bookings: $nbookings</p>");*/
/*echo("<p>Total number of seats: $nseats</p>");*/

include('includes/page-footer.php') ?>
