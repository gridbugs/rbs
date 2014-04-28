<?

require_once('includes/utilities.php');
$link = db_connect();
require_once('includes/session.php');
require_once('includes/usermanagement.php');

if(!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_pass'])) {
	header('Location: admin_login.php');
	exit;
} else {
    if ($_SESSION['admin_pass'] !== admin_pass($link, $_SESSION['admin_id'])){
    echo "b";
    exit;
        header('Location: admin_login.php');
        exit;
    }
}



/* check if the user actually has admin access to this show */
function check_access_to_production($current_prod) {
    
    if ((int)$_SESSION['admin_superadmin'] != 0) {
        return;
    }


    
    $user = $_SESSION['admin_id'];

    $sql = "select * 
        from production inner join prodadmin on production.id = prodadmin.production
        where prodadmin.admin = $user and production.id = $current_prod";
    
    $res = mysql_query($sql);
    
    if (mysql_num_rows($res) == 0) {
        die("<div class=\"error\"><p>Access denied to production.</p></div>");
    }

}

function can_manage_production($prod) {
    
    if ((int)$_SESSION['admin_superadmin'] != 0) {
        return true;
    }
    
    $user = $_SESSION['admin_id'];

    $sql = "select * 
        from production inner join prodadmin on production.id = prodadmin.production
        where prodadmin.admin = $user and production.id = $prod";
    
    $res = mysql_query($sql);
    
    if (mysql_num_rows($res) == 0) {
        return false;
    } else {
        return true;
    }


}

function check_can_manage_production($prod) {
    if (!can_manage_production($prod)) {
        die("<div class=\"error\"><p>Permission denied to manage production.</p></div>");
    }
}

function check_access_to_performance($performance) {
    
    if ((int)$_SESSION['admin_superadmin'] != 0) {
        return;
    }
    
    $user = $_SESSION['admin_id'];

    $sql = "select * 
            from performance inner join prodadmin on performance.production = prodadmin.production
            where prodadmin.admin = $user and performance.id = $performance";
    
    
    $res = mysql_query($sql);

    if (mysql_num_rows($res) == 0) {
        die("<div class=\"error\"><p>Access denied to performance.</p></div>");
    }

}

?>
