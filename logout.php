<?
require_once('includes/utilities.php');
$link = db_connect();
require_once('includes/session.php');
require_once('includes/usermanagement.php');
if(isset($_SESSION['production'])) {
	$url = "login.php?production=" . $_SESSION['production'];
	include_once('includes/prodmanagement.php');
	include_once('includes/frames/prodtheme.php');
	$footer = true;

	$link = db_connect();
	$production = get_production($link, $_SESSION['production']);
	print_prod_header($link, $production);
} else {
	$url = "index.php";
	echo('<html><body>');
}

$_SESSION = array();
session_destroy();
?>

<html>
<body>
<h1>Logout</h1>

<p>You have now been logged out.  <a href="/admin_login.php">Click Here</a> to navigate back to the login page.</p>

<?
if(isset($footer))
	print_prod_footer($link, $production);
else
	echo('</body></html>');
?>

