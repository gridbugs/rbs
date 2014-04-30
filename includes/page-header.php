<header>
<div class="header-container">
    <? if (isset($production['name'])): ?>
        <h1><a href="admin_production.php">RBS Admin - <?=$production['name']?></a></h1>
    <? else: ?>
        <h1><a href="admin_production.php">RBS Admin</a></h1>
    <?endif?>
	<div class="row">
        <div class="one third">Logged in as: <?=$_SESSION['admin_name']?> (<?=$_SESSION['admin_email']?>)</div>
		<div class="two thirds align-right">
			<a role="button" href="admin_booking.php">New Booking</a>
			<a role="button" href="admin_bookinglist.php">Booking List</a>
			<?if(isset($production['id']) && is_superadmin($production['id'])):?>
			<a role="button" href="admin_editproduction.php">Edit Production Details</a>
			<a role="button" href="admin_manageticketers.php?prod=<?=$production['id']?>">Manage Ticketers</a>
			<?endif?>
			<a role="button" href="logout.php">Logout</a>
		</div>
	</div>
</div>
</header>

<div class="container">
