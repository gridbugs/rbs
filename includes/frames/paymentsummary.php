<?
/**
 * Renders a summary of the user's bookings.
 */

include_once('includes/utilities.php');

function print_payment_summary($link, $perfsummary) {
	// Print the booked seats
	if(isset($perfsummary[1])) {
		echo('<p>Your seats have been booked and are now awaiting payment.</p>');
		//echo('<div class="paymentexpiry">These tickets expire on ' . prettydate($perfsummary[1][0]['tsdeadline']) . ' at ' . prettytime($perfsummary[1][0]['tsdeadline']) . '</div>'); 
		echo('<table class="paymentsummary">');
		echo('<tr><th class="paymentseatshead">Seats Booked</th><th class="paymentpricehead">Amount to Pay</th></tr>');
		$nexpired = 0;
		foreach($perfsummary[1] as $row) {
			echo('<tr><td class="paymentseats">' . $row['num'] . ' ' . $row['name']);
			if($row['num'] == 1)
				echo(' seat');
			else
				echo(' seats');
			echo('</td><td class="paymentprice">$' . $row['price'] * $row['num'] . '</td></tr>');
		}
		echo('</table>');
	}

	// Print the paid for seats
	if(isset($perfsummary[2])) {
		echo('<p>The following seats have been paid for</p>');
		echo('<table class="paymentsummary">');
		foreach($perfsummary[2] as $row) {
			echo('<tr><td class="paymentseats">' . $row['num'] . ' ' . $row['name']);
			if($row['num'] == 1)
				echo(' seat');
			else
				echo(' seats');
			echo('</td></tr>');
		}
		echo('</table>');
	}
}
?>
