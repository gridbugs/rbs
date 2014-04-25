<header>
    <? if (isset($production['name'])): ?>
        <h1>RBS Admin - <?=$production['name']?></h1>
    <? else: ?>
        <h1>RBS Admin</h1>
    <?endif?>
		<div class="row">
        <div class="one fifth">Logged in as: <?=$_SESSION['admin_name']?> (<?=$_SESSION['admin_email']?>)</div>

		<div class="three fifths skip-one">
<a role="button" href="admin_booking.php">New Booking</a>
<a role="button" href="admin_bookinglist.php">Booking List</a>
<a role="button" href="admin_editproduction.php">Edit Production Details</a>
<?if(isset($production['id']) && can_manage_production($production['id'])):?>
<a role="button" href="admin_manageticketers.php?prod=<?=$production['id']?>">Manage Ticketers</a>
<?endif?>
<a role="button" href="logout.php">Logout</a>
</div>
</div>
</header>
