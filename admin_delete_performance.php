
<?
require_once('includes/utilities.php');
$link = db_connect();
require_once('includes/adminauth.php');
include_once('includes/newutils.php');
require_once('includes/prodmanagement.php');
require_once('includes/perfmanagement.php');

if (!isset($_GET['perf'])) {
    
    die("Needs performance.");
}

$perf_id = $_GET['perf'];

$perf = get_performance($link, $perf_id);

check_can_manage_production($perf['production']);

$db = db_connect_pdo();

$stmt = $db->prepare(<<<EOT
DELETE FROM performance
WHERE id = :perf_id
EOT
);
$stmt->execute(array(':perf_id' => $perf_id));

header('Location: /admin_editproduction.php?tab=performances');
