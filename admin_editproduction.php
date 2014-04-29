<?
// Allows the user to edit production details and add a new production
// TODO: filtering to prevent XSS

include_once('includes/utilities.php');
$link = db_connect();
include_once('includes/adminauth.php');
include_once('includes/prodmanagement.php');
include_once('includes/perfmanagement.php');
include_once('includes/theatremanagement.php');

$theatres = theatre_list();

if(isset($_SESSION['admin_production'])) {
	$prodid = (int)$_SESSION['admin_production'];
} else {
	$prodid = -1;
}

if(isset($_POST['name'])) {
	// Form has been submitted, lets save
	$message = modify_production($link, $prodid, $_POST);
	if(is_int($message)) {
		$message = "Update of production successful";
		$production = get_production($link, $prodid);
	} else {
		$production = $_POST;
	}
} else if($prodid != -1)
	$production = get_production($link, $prodid);
	
include('includes/groundwork-header.php');
include('includes/page-header.php');
?>

<!--<style type="text/css">
.editprod_header { display: inline-block; border: 1px solid #aaa; width: 300px; text-align: center; margin-bottom: -1px; }
div .editprod_content { border: 1px solid #aaa; }
#editprod_l_prodinfo { border-bottom-color: #fff; }
div #editprod_technical { display: none; }
div #editprod_payment { display: none; }
div #editprod_performances { display: none; }
</style>

<script type="text/javascript">
curcontentname = "prodinfo";
function showContent(contentname) {
	var oldlink = document.getElementById("editprod_l_" + curcontentname);
	var oldcont = document.getElementById("editprod_" + curcontentname);
	var link = document.getElementById("editprod_l_" + contentname);
	var cont = document.getElementById("editprod_" + contentname);
	curcontentname = contentname;
	link.style.borderBottom="1px solid #fff";
	cont.style.display = "block";
	oldlink.style.borderBottom="1px solid #aaa";
	oldcont.style.display = "none";
}
var elem = document.getElementById("scrollUserTable");
</script>
</head>
<body 
>

<h1>Production Details</h1>-->

<h2>Edit production details</h2>

<?if(isset($message)) {?>
	 <p class="success message"><?=$message?></p>
<?}?>

<!--<?
if($prodid >= 0) {
?>
<p><a href="admin_production.php?production=<?=$prodid?>">Production Page</a></p>
<?
} else {
?>
<p><a href="admin_prodlist.php">Back to Production List</a></p>
<?
}
?>-->

<form method="post" action="admin_editproduction.php">

<div class="tabs" id="editprod_frame">
<ul role="tablist">
<li role="tab" aria-controls="#prodinfo">Production Information</li>
<li role="tab" aria-controls="#performances">Performances</li>
<li role="tab" aria-controls="#paymentinfo">Payment Information</li>
<li role="tab" aria-controls="#technicalinfo">Technical Information</li>
</ul>

<div id="prodinfo" role="tabpanel">
<table>
<tr>
	<td>Production ID:</td>
	<td><?if($prodid >= 0) echo($prodid)?></td>
</tr>
<tr>
	<td>Production Name:</td>
	<td><input type="text" name="name" value="<?if($prodid != -1) echo(htmlspecialchars($production['name']))?>"></td>
</tr>
<tr>
	<td>Closing Date:</td>
	<td><input type="text" name="closedate" value="<?if($prodid != -1) echo(htmlspecialchars($production['closedate']))?>"></td>
</tr>
<tr>
	<td>Is the show closed?:</td>
	<td><input type="checkbox" name="isclosed" <?if($prodid != -1 && $production['isclosed'] == 1) echo("CHECKED")?>></td>
</tr>
<tr>
	<td>Location:</td>
	<td><select name="theatre">
<?
foreach($theatres as $theatre) {
	echo("		<option value='$theatre'");
	if(isset($production) && $theatre == $production['theatre'])
		echo(" SELECTED");
	echo(">$theatre</option>");
}
?>
	</select>
	</td>
</tr>
<!--tr>
	<td>Minimum Group Ticket Size:</td>
	<td><input type="text" name="groupticketsamount" value="<?if($prodid != -1) echo((int)$production['groupticketsamount'])?>"></td>
</tr-->
<!--tr>
	<td>Group Tickets Message:</td>
	<td><textarea name="groupticketsmessage"><?if($prodid != -1) echo(htmlspecialchars($production['groupticketsmessage']))?></textarea></td>
</tr-->
</table>
</div>

<div id="performances" role="tabpanel">
<table class="sortable">
<tr>
<th>Title</th>
<th>Date</th>
<th>Start</th>
<th>End</th>
<th>Edit</th>
<th>Delete</th>
</tr>
<?foreach(get_performances($link, $production['id']) as $perf):?>
<tr>
<td><?=$perf['title']?></td>
<td><?=$perf['date']?></td>
<td><?=$perf['starttime']?></td>
<td><?=$perf['finishtime']?></td>
<td><a class="button blue" href="/admin_edit_performance.php?perf=<?=$perf['id']?>"><i class="icon-pencil"></i> Edit</a></td>
<td><a class="button red" href="/admin_delete_performance.php?perf=<?=$perf['id']?>"><i class="icon-remove-sign"></i> Delete</a></td>
</tr>
<?endforeach?>
</table>
<a class="button green" href="admin_add_performance.php"><i class="icon-plus"></i> Add a new performance</a>

</div>

<div id="paymentinfo" role="tabpanel">
<table>
<tr>
	<td>Accept Sales Booth Reservations:</td>
	<td><input type="checkbox" name="acceptsales" <?if($prodid != -1 && $production['acceptsales'] == 1) echo("CHECKED")?>></td>
</tr>
<tr>
	<td>Sales Desk Information:</td>
	<td><textarea name="salesinfo"><?if($prodid != -1) echo(htmlspecialchars($production['salesinfo']))?></textarea></td>
</tr>
<tr>
	<td>Accept Direct Debit:</td>
	<td><input type="checkbox" name="acceptdd" <?if($prodid != -1 && $production['acceptdd'] == 1) echo("CHECKED")?>></td>
</tr>
<tr>
	<td>Direct Debit Information:</td>
	<td><textarea name="ddinfo"><?if($prodid != -1) echo(htmlspecialchars($production['ddinfo']))?></textarea></td>
</tr>
<tr>
	<td>Accept Paypal:</td>
	<td><input type="checkbox" name="acceptpaypal" <?if($prodid != -1 && $production['acceptpaypal'] == 1) echo("CHECKED")?>></td>
</tr>
<tr>
	<td>Paypal Account:</td>
	<td><input type="text" name="paypalaccount" value="<?if($prodid != -1) echo(htmlspecialchars($production['paypalaccount']))?>"></td>
</tr>
<tr>
	<td>Paypal Information:</td>
	<td><textarea name="paypalinfo"><?if($prodid != -1) echo(htmlspecialchars($production['paypalinfo']))?></textarea></td>
</tr>

</table>
</div>

<div id="technicalinfo" role="tabpanel">
<table>
<tr>
	<td>Show Header:</td>
	<td><textarea name="header"><?if($prodid != -1) echo(htmlspecialchars($production['header']))?></textarea></td>
</tr>
<tr>
	<td>Show Footer:</td>
	<td><textarea name="footer"><?if($prodid != -1) echo(htmlspecialchars($production['footer']))?></textarea></td>
</tr>
<tr>
	<td>CSS File Location:</td>
	<td><input type="text" name="css" value="<?if($prodid != -1) echo(htmlspecialchars($production['css']))?>"></td>
</tr>
<tr>
	<td>Show Website Location:</td>
	<td><input type="text" name="sitelocation" value="<?if($prodid != -1) echo(htmlspecialchars($production['sitelocation']))?>"></td>
</tr>
<tr>
	<td>Show FAQ Location:</td>
	<td><input type="text" name="faqlocation" value="<?if($prodid != -1) echo(htmlspecialchars($production['faqlocation']))?>"></td>
</tr>
</table>
</div>

<div id="paymentinfo" role="tabpanel">
<table>
<tr>
	<td>Accept Sales Booth Reservations:</td>
	<td><input type="checkbox" name="acceptsales" <?if($prodid != -1 && $production['acceptsales'] == 1) echo("CHECKED")?>></td>
</tr>
<tr>
	<td>Sales Desk Information:</td>
	<td><textarea name="salesinfo"><?if($prodid != -1) echo(htmlspecialchars($production['salesinfo']))?></textarea></td>
</tr>
<tr>
	<td>Accept Direct Debit:</td>
	<td><input type="checkbox" name="acceptdd" <?if($prodid != -1 && $production['acceptdd'] == 1) echo("CHECKED")?>></td>
</tr>
<tr>
	<td>Direct Debit Information:</td>
	<td><textarea name="ddinfo"><?if($prodid != -1) echo(htmlspecialchars($production['ddinfo']))?></textarea></td>
</tr>
<tr>
	<td>Accept Paypal:</td>
	<td><input type="checkbox" name="acceptpaypal" <?if($prodid != -1 && $production['acceptpaypal'] == 1) echo("CHECKED")?>></td>
</tr>
<tr>
	<td>Paypal Account:</td>
	<td><input type="text" name="paypalaccount" value="<?if($prodid != -1) echo(htmlspecialchars($production['paypalaccount']))?>"></td>
</tr>
<tr>
	<td>Paypal Information:</td>
	<td><textarea name="paypalinfo"><?if($prodid != -1) echo(htmlspecialchars($production['paypalinfo']))?></textarea></td>
</tr>

</table>
</div>

<input type="submit" />

</div>

</form>
<? include('includes/page-footer.php')?>
