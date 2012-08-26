<?

require_once('session.php');

if(!isset($_SESSION['admin_id'])) {
	header('Location: admin_login.php');
	exit;
}

?>
