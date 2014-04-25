<?
require_once('includes/utilities.php');
$link = db_connect();
require_once('includes/adminauth.php');
include_once('includes/newutils.php');
require_once('includes/prodmanagement.php');

if (isset($_POST['prod'])) {
    $prod_id = $_POST['prod'];
}
if (isset($_GET['prod'])) {
    $prod_id = $_GET['prod'];
}
if (isset($_POST['email'])) {
    $user_email = $_POST['email'];
}
if (isset($_GET['email'])) {
    $user_email = $_GET['email'];
}

if (!isset($prod_id) ||
    !isset($user_email)) {
    
    die("Needs production and email.");
}

check_can_manage_production($prod_id);

$db = db_connect_pdo();

$stmt = $db->prepare(<<<EOT
SELECT *
FROM admin
WHERE email = :email
EOT
);
$stmt->execute(array(':email' => $user_email));
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $db->prepare(<<<EOT
INSERT INTO prodadmin(admin, production)
VALUES (:user_id, :prod_id)
EOT
);
$stmt->execute(array(':user_id' => $user['id'], ':prod_id' => $prod_id));

header('Location: /admin_manageticketers.php?prod='.$prod_id);
