<?
// Allows the user to edit production details and add a new production
// TODO: filtering to prevent XSS

include_once('includes/utilities.php');
$link = db_connect();
include_once('includes/adminauth.php');
include_once('includes/prodmanagement.php');
include_once('includes/theatremanagement.php');

$theatres = theatre_list();

if(isset($_SESSION['admin_production'])) {
	$prodid = (int)$_SESSION['admin_production'];
} else {
	$prodid = -1;
}

if(isset($_POST['name'])) {
	// Form has been submitted, lets save
	if($prodid < 0) {
		$ret = add_production($link, $_POST);
		if(is_int($ret)) {
			// Adding the production was successful
			$prodid = $ret;
			$production = get_production($link, $prodid);
			$message = "<p class=\"success message\">The production has been successfully added.</p>";
            header("Location: /admin_editproduction.php");
            exit;
		} else {
			// The function returned an error
			$message = $ret;
			$production = $_POST;
			$prodid = -2;
		}
	} else {
		$message = modify_production($link, $prodid, $_POST);
		if(is_int($message)) {
			$message = "<p class=\"success message\">Update of production successful</p>";
			$production = get_production($link, $prodid);
		} else {
			$production = $_POST;
		}
	}
} else if($prodid != -1)
	$production = get_production($link, $prodid);
include ('includes/groundwork-header.php');
include ('includes/superadmin-header.php'); ?>

<div class="container">
      <article class="row">
        <section class="padded">
<? if ($prodid >= 0): ?>
<h1>Modify Production Details for Existing Production</h1>
<? else: ?>
<h2>Add a new production</h1>
<? endif ?>
<?if(isset($message)) {?>
	<em><?=$message?></em>
<?}?>

<form method="post" action="admin_newproduction.php" id="addnewproduction">

<div class="row">
<?if($prodid >= 0): ?>
<div class="one third padded">Production ID:</div>
<div class="two thirds padded"><?=$prodid?></div>
<?endif?>
<div class="one third padded">Production Name *:</div>
<div class="two thirds padded"><input type="text" name="name" value="<?if($prodid != -1) echo(htmlspecialchars($production['name']))?>"></div>

<div class="one third padded">Show Header:</div>
<div class="two thirds padded"><textarea name="header"><?if($prodid != -1) echo(htmlspecialchars($production['header']))?></textarea></div>
	
<div class="one third padded">Show Footer:</div>
<div class="two thirds padded"><textarea name="footer"><?if($prodid != -1) echo(htmlspecialchars($production['footer']))?></textarea></div>

<div class="one third padded">CSS File Location:</div>
<div class="two thirds padded"><input type="text" name="css" value="<?if($prodid != -1) echo(htmlspecialchars($production['css']))?>" /></div>

<div class="one third padded">Location:</div>
<div class="two thirds padded"><select name="theatre">
<?
foreach($theatres as $theatre) {
	echo("		<option value='$theatre'");
	if(isset($production) && $theatre == $production['theatre'])
		echo(" SELECTED");
	echo(">$theatre</option>");
}
?>
	</select>
</div>
<div class="one third padded">Is the show closed?:</div>
<div class="two thirds padded"><input type="checkbox" name="isclosed" <?if($prodid != -1 && $production['isclosed'] == 1) echo("CHECKED")?>></div>

<div class="one third padded clear">Closing Date (yyyy-mm-dd) *:</div>
<div class="two thirds padded"><input type="text" name="closedate" value="<?if($prodid != -1) echo(htmlspecialchars($production['closedate']))?>"></div>

<div class="one third padded clear">Minimum Group Ticket Size *:</div>
<div class="two thirds padded"><input type="text" name="groupticketsamount" value="<?if($prodid != -1) echo((int)$production['groupticketsamount']); else echo 0?>"></div>

<div class="one third padded clear">Group Tickets Message:</div>
<div class="two thirds padded"><textarea name="groupticketsmessage"><?if($prodid != -1) echo(htmlspecialchars($production['groupticketsmessage']))?></textarea></div>

<div class="one third padded clear">Show Website Location:</div>
<div class="two thirds padded"><input type="text" name="sitelocation" value="<?if($prodid != -1) echo(htmlspecialchars($production['sitelocation']))?>"></div>

<div class="one third padded clear">Show FAQ Location:</div>
<div class="two thirds padded"><input type="text" name="faqlocation" value="<?if($prodid != -1) echo(htmlspecialchars($production['faqlocation']))?>"></div>

<div class="one third padded clear">Accept Sales Booth Reservations:</div>
<div class="two thirds padded"><input type="checkbox" name="acceptsales" <?if($prodid != -1 && $production['acceptsales'] == 1) echo("CHECKED")?>></div>

<div class="one third padded clear">Sales Desk Information:</div>
<div class="two thirds padded"><textarea name="salesinfo"><?if($prodid != -1) echo(htmlspecialchars($production['salesinfo']))?></textarea></div>

<div class="one third padded clear">Accept Direct Debit:</div>
<div class="two thirds padded"><input type="checkbox" name="acceptdd" <?if($prodid != -1 && $production['acceptdd'] == 1) echo("CHECKED")?>></div>

<div class="one third padded clear">Direct Debit Information:</div>
<div class="two thirds padded"><textarea name="ddinfo"><?if($prodid != -1) echo(htmlspecialchars($production['ddinfo']))?></textarea></div>

<div class="one third padded clear">Accept Paypal:</div>
<div class="two thirds padded"><input type="checkbox" name="acceptpaypal" <?if($prodid != -1 && $production['acceptpaypal'] == 1) echo("CHECKED")?>></div>

<div class="one third padded clear">Paypal Account:</div>
<div class="two thirds padded"><input type="text" name="paypalaccount" value="<?if($prodid != -1) echo(htmlspecialchars($production['paypalaccount']))?>"></div>

<div class="one third padded clear">Paypal Information:</div>
<div class="two thirds padded"><textarea name="paypalinfo"><?if($prodid != -1) echo(htmlspecialchars($production['paypalinfo']))?></textarea></div>
</div>

<input type="submit">
</form>

        </section>
      </article>

<?php include('includes/page-footer.php') ?>
