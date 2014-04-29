<?
require_once('includes/utilities.php');
$link = db_connect();
require_once('includes/adminauth.php');
include_once('includes/newutils.php');
require_once('includes/prodmanagement.php');

if (!isset($_GET['prod']) ||
    !isset($_GET['admin'])) {
    
    die("Needs production and email.");
}

$prod_id = $_GET['prod'];
$user_id = $_GET['admin'];
check_is_superadmin();

$db = db_connect_pdo();

$stmt = $db->prepare(<<<EOT
UPDATE prodadmin
SET can_manage = 1
WHERE admin = :user_id AND production = :prod_id
EOT
);
$stmt->execute(array(':user_id' => $user_id, ':prod_id' => $prod_id));

header('Location: /admin_manageticketers.php?prod='.$prod_id);
