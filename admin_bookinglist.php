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

if(isset($_GET['production']) && production_exists($link, $_GET['production']))
	$_SESSION['admin_production'] = $_GET['admin_production'];

if(!isset($_SESSION['admin_production'])) {
	echo("production does not exist");
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

?>

<html>
<head>
	<title>Booking List for <?=$production['name']?></title>
	<link rel="stylesheet" type="text/css" href="css/admin_bookinglist.css" />
</head>
<body>
<h1>Booking List for <?=$production['name']?></h1>
<p><a href="admin_production.php">Back to production page</a></p>
<?
if($perftitle != '') {
	echo("<h2>$perftitle</h2>");
}
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

if(isset($_POST['includeadmin']))
	$ia = true;
else
	$ia = false;

$sc = $showcolumn;

if(!isset($_POST['printing'])) {
?>

<form method="post" action="admin_bookinglist.php">
<div class="formentry">Select bookings from the following performance:
<select name="restrictperf">
	<option value="-1">All Performances</option>
<?
foreach($performances as $performance) {
	echo("<option value='" . $performance['id'] . "'");
	if(isset($_POST['restrictperf']) && $_POST['restrictperf'] == $performance['id'])
		echo(" SELECTED");
	echo(">" . prettydate($performance['tsdate']) . "</option>");
}
?>

</select>
</div>

<div class="formentry">Select only bookings with this payment status:<br>
	<input type="checkbox" name="restrictstatus[1]"<?if(isset($rs[1])) echo(" CHECKED")?>>Booked
	<input type="checkbox" name="restrictstatus[3]"<?if(isset($rs[3])) echo(" CHECKED")?>>Confirmed
	<input type="checkbox" name="restrictstatus[4]"<?if(isset($rs[4])) echo(" CHECKED")?>>Paid
	<input type="checkbox" name="restrictstatus[5]"<?if(isset($rs[5])) echo(" CHECKED")?>>Paid Sales Desk
	<input type="checkbox" name="restrictstatus[6]"<?if(isset($rs[6])) echo(" CHECKED")?>>Paid DD
	<input type="checkbox" name="restrictstatus[7]"<?if(isset($rs[7])) echo(" CHECKED")?>>Paid Paypal
	<input type="checkbox" name="restrictstatus[8]"<?if(isset($rs[8])) echo(" CHECKED")?>>Payment Pending
	<input type="checkbox" name="restrictstatus[9]"<?if(isset($rs[9])) echo(" CHECKED")?>>Unavailable
	<input type="checkbox" name="restrictstatus[10]"<?if(isset($rs[10])) echo(" CHECKED")?>>VIP
	<input type="checkbox" name="restrictstatus[11]"<?if(isset($rs[11])) echo(" CHECKED")?>>All Paid Tickets
	<input type="checkbox" name="restrictstatus[12]"<?if(isset($rs[12])) echo(" CHECKED")?>>All Tickets
</div>

<div class="formentry">
<input type="checkbox" name="includeadmin"<?if($ia) echo(" CHECKED")?>>Include tickets that have been booked by an admin
</div>

<div class="formentry">Show only bookings with the following picked up status:
<select name="restrictpickedup">
	<option value="-1"<?if($rpu == -1) echo(" SELECTED")?>>All Bookings</option>
	<option value="0"<?if($rpu == 0) echo(" SELECTED")?>>Not Picked Up</option>
	<option value="1"<?if($rpu == 1) echo(" SELECTED")?>>Picked Up</option>
</select>
</div>

<div class="formentry">Sort By
<select name="sortby">
	<option value="id"<?if($sb == 'id') echo(" SELECTED")?>>ID Number</option>
	<option value="name"<?if($sb == 'name') echo(" SELECTED")?>>Name</option>
	<option value="deadline"<?if($sb == 'deadline') echo(" SELECTED")?>>Payment Deadline</option>
	<option value="bookedtime"<?if($sb == 'bookedtime') echo(" SELECTED")?>>Booked Time</option>
</select>
<select name="order">
	<option value="asc"<?if($order == 'asc') echo(" SELECTED")?>>Ascending</option>
	<option value="desc"<?if($order == 'desc') echo(" SELECTED")?>>Descending</option>
</select>
</div>

<div class="formentry">Show the following columns:<br>
	<input type="checkbox" name="showcolumn[bookingid]"<?if(isset($sc['bookingid'])) echo(" CHECKED")?>>Booking ID
	<input type="checkbox" name="showcolumn[performance]"<?if(isset($sc['performance'])) echo(" CHECKED")?>>Performance
	<input type="checkbox" name="showcolumn[name]"<?if(isset($sc['name'])) echo(" CHECKED")?>>Name
	<input type="checkbox" name="showcolumn[email]"<?if(isset($sc['email'])) echo(" CHECKED")?>>Email
	<input type="checkbox" name="showcolumn[seats]"<?if(isset($sc['seats'])) echo(" CHECKED")?>>Seats
	<input type="checkbox" name="showcolumn[desc]"<?if(isset($sc['desc'])) echo(" CHECKED")?>>Description
	<input type="checkbox" name="showcolumn[phoneno]"<?if(isset($sc['phone'])) echo(" CHECKED")?>>Phone Number
	<input type="checkbox" name="showcolumn[pickedup]"<?if(isset($sc['pickedup'])) echo(" CHECKED")?>>Picked Up
	<input type="checkbox" name="showcolumn[amountpaid]"<?if(isset($sc['amountpaid'])) echo(" CHECKED")?>>Amount Paid
	<input type="checkbox" name="showcolumn[discount]"<?if(isset($sc['discount'])) echo(" CHECKED")?>>Discount
	<input type="checkbox" name="showcolumn[paymentid]"<?if(isset($sc['paymentid'])) echo(" CHECKED")?>>Payment ID
	<input type="checkbox" name="showcolumn[deadline]"<?if(isset($sc['deadline'])) echo(" CHECKED")?>>Payment Deadline
	<input type="checkbox" name="showcolumn[bookedtime]"<?if(isset($sc['bookedtime'])) echo(" CHECKED")?>>Booked Time
</div>

<div class="formentry"><input type="checkbox" name="printing"> Print Friendly</div>

<div><input type="submit" value="Get Bookings"></div>

</form>

<?
} // Printing


if(!isset($_POST['restrictperf'])) { // If the user hasn't clicked "Get Bookings"
	echo("</body></html>");
	exit;
}

// Get the bookings
$restrictions = array();
$restrictions['performance'] = $_POST['restrictperf'];
$restrictions['status'] = $_POST['restrictstatus'];
$restrictions['pickedup'] = $_POST['restrictpickedup'];

$sortby = $_POST['sortby'];
$order = $_POST['order'];

$bookings = get_all_bookings($link, $production['id'], $sortby, $order, $restrictions, $ia);

$nbookings = count($bookings);
$nseats = 0;

?>

<table id="bookinglist">
	<tr>
<?
if(isset($showcolumn['bookingid']))
	echo('<th>Booking ID</th>');
if(isset($showcolumn['performance']))
	echo('<th>Performance</th>');
if(isset($showcolumn['name']))
	echo('<th>Name</th>');
if(isset($showcolumn['email']))
	echo('<th>Email</th>');
if(isset($showcolumn['seats']))
	echo('<th>Seats Booked</th>');
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
if(isset($showcolumn['paymentid']))
	echo('<th>Payment ID</th>');
if(isset($showcolumn['deadline']))
	echo('<th>Payment Deadline</th>');
if(isset($showcolumn['bookedtime']))
	echo('<th>Booked Time</th>');
?>
	</tr>
<?
foreach($bookings as $booking) {
	echo("<tr>");
	if(isset($showcolumn['bookingid']))
		echo("<td>" . $booking['id'] . "</td>");
	if(isset($showcolumn['performance']))
		echo("<td>" . prettydate($booking['tsdate']) . "</td>");
	if(isset($showcolumn['name']))
		echo("<td>" . htmlspecialchars($booking['name']) . "</td>");
	if(isset($showcolumn['email']))
		echo("<td>" . htmlspecialchars($booking['email']) . "</td>");
	if(isset($showcolumn['seats'])) {
		echo("<td>");
		$nseats += count($booking['seats']);
		foreach($booking['seats'] as $seat) {
			echo($seat['seat']);
			echo(" (" . status_message($seat['status']) . ")");
			echo("<br>");
		}
		echo("</td>");
	}
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
	if(isset($showcolumn['paymentid']))
		echo("<td>" . htmlspecialchars($booking['paymentid']) . "&nbsp;</td>");
	if(isset($showcolumn['deadline']))
		echo("<td>" . htmlspecialchars($booking['deadline']) . "</td>");
	if(isset($showcolumn['bookedtime']))
		echo("<td>" . htmlspecialchars($booking['bookedtime']) . "</td>");
	echo("</tr>");
}

echo("</table>");

echo("<p>Total number of bookings: $nbookings</p>");
echo("<p>Total number of seats: $nseats</p>");

?>
</body>
</html>
