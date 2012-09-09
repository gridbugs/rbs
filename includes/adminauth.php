<?

require_once('includes/utilities.php');
$link = db_connect();
require_once('includes/session.php');
require_once('includes/usermanagement.php');
print_r($_SESSION);

if(!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_pass'])) {
	header('Location: admin_login.php');
	exit;
} else {
    if ($_SESSION['admin_pass'] !== admin_pass($link, $_SESSION['admin_id'])){
        header('Location: admin_login.php');
        exit;
    }
}

?>
