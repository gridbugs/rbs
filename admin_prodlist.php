<?
require_once('includes/utilities.php');
$link = db_connect();
require_once('includes/adminauth.php');
require_once('includes/prodmanagement.php');

if(isset($_SESSION['admin_production']))
	unset($_SESSION['admin_production']);

// If the user only has access to one production, send him straight through.
if($_SESSION['admin_superadmin'] == 0 && count($_SESSION['admin_prodlist']) == 1) {
	header("Location: admin_production.php?production=" . $_SESSION['admin_prodlist'][0]);
	exit;
}

// Grab the production list.
$prodlist = get_admin_prodlist($link);
?>

<html>
<head><title>RBS Admin</title></head>
<body>

<h1>Production List</h1>
<?
foreach($prodlist as $prod) {
	echo("<p>Production " . $prod['id'] . ": ");
	echo("<a href='admin_production.php?production=" . $prod['id'] . "'>" . $prod['name'] . "</a></p>");
}

if($_SESSION['admin_superadmin']) {
?>

<p><a href="admin_newproduction.php">Add New Production</a></p>

<?
}
?>

</body>
</html>
