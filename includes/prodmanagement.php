<?
/**
 * Utility functions for managing performances.
 */

include_once('utilities.php');

function production_exists($link, $prodid) {
	$prodid = (int)$prodid;

	$query = "select id from production where id = $prodid";
	$results = mysql_query($query, $link);
	return ($results && mysql_num_rows($results) > 0); 
}

/**
 * Gets the information for a specific show.
 * prod is assumed to exist.
 */
function get_production($link, $prodid) {

	$q_prod = "select * from production where id = $prodid";
	$r_prod = mysql_query($q_prod, $link);


	if(!$r_prod)
		return array();

	if($row = mysql_fetch_array($r_prod, MYSQL_ASSOC)) {
		return $row;
	}
	else
		return null;
}

/**
 * Gets a full list of productions.
 */
function get_prodlist($link, $onlyopen=true) {
	$sql = "select * from production p";
	if($onlyopen) {
		$sql .= " where isclosed = 0 ";//and (select max(date) from performance pe where pe.production = p.id) >= CURDATE()";
	}
	return sql_get_array($link, $sql);
}

/**
 * Get a production list for the admin section
 */
function get_admin_prodlist($link, $adminid=-1) {
	if($adminid == -1) {
		$adminid = $_SESSION['admin_id'];
		$superadmin = $_SESSION['admin_superadmin'];
	} else {
		$adminid = (int)$adminid;
		$rows_superadmin = sql_get_array($link, "select superadmin from admin where admin = $adminid");
		if($row != null && count($row) > 0)
			$superadmin = $rows_superadmin[0]['superadmin'];
	}
	
	if($superadmin)
		$sql = "select * from production order by closedate desc";
	else
		$sql = "select p.* from production p, prodadmin pa where pa.production = p.id and pa.admin = $adminid order by p.closedate desc";

	return sql_get_array($link, $sql);
}

/**
 * This emails the production admins
 */
function email_prod_admins($link, $prodid, $subject, $message) {
	$prodid = (int)$prodid;
	$sql = "select email from admin where superadmin = 1 union select email from admin a, prodadmin p where p.admin = a.id and p.production = $prodid";
	$emails = sql_get_array($link, $sql);
	foreach($emails as $email) {
		send_email($email['email'], $subject, $message, "From: alerts@cserevue.org.au\r\n");
	}
}

/**
 * Adds a new production.  Should be safe to pass the $_POST parameter to it.
 */
function add_production($link, $production) {
	$sql = "INSERT INTO production (";

	$values = "";

	// name
	$sql .= "name";
	if(!isset($production['name']) || $production['name'] == '')
		return "Please enter a name.";
	$values .= "'" . mysql_real_escape_string($production['name']) . "'";

	// These columns have no special handling.  Lets just do them all in one go.
	$stringcols = array("header", "footer", "css", "sitelocation", "faqlocation", "salesinfo", "ddinfo", "paypalinfo", "paypalaccount");
	foreach ($stringcols as $col) {
		$sql .= ", $col";
		if(!isset($production[$col]))
			$values .= ", ''";
		else
			$values .= ", '" . mysql_real_escape_string($production[$col]) . "'";
	}

	// These columns are the checkbox columns.  Again, we can do them all in one go.
	$checkboxcols = array("isclosed", "acceptsales", "acceptdd", "acceptpaypal");
	foreach($checkboxcols as $col) {
		$sql .= ", $col";
		if(!isset($production[$col]) || $production[$col] == 'off')
			$values .= ", 0";
		else
			$values .= ", 1";
	}

	// Now for columns with special handling:

	// closedate
	if(isset($production['closedate']) && $production['closedate'] != '') {
		if(preg_match('/^\d{4}-\d{2}-\d{2}$/', $production['closedate']) !== 1)
			return "Please format the closed date as Year-Month-Day (for example 2010-01-01)";
		$sql .= ", closedate";
		$values .= ", '" . $production['closedate'] . "'";
	}

	// groupticketsamount
	if(isset($production['groupticketsamount'])) {
		$sql .= ", groupticketsamount";
		$values .= ", " . (int)$groupticketsamount;
	}

	// theatre
	$sql .= ", theatre";
	if(!isset($production['theatre']))
		return "Please enter a valid theatre";
	else
		$values .= ", '" . mysql_real_escape_string($production['theatre']) . "'";


	// Complete the SQL command:
	$sql .= ") VALUES (" . $values . ")";

	if(mysql_query($sql, $link)) {
		rbslog("Created production " . $production['name']);
		return mysql_insert_id($link);
	} else
		return "Insert query failed";
}

/**
 * Updates production details.  Should be safe to pass the $_POST parameter to it.
 */
function modify_production($link, $prodid, $production) {
	$sql = "UPDATE production SET ";

	// name
	$sql .= "name = ";
	if(!isset($production['name']) || $production['name'] == '')
		return "Please enter a name.";
	$sql .= "'" . mysql_real_escape_string($production['name']) . "'";

	// These columns have no special handling.  Lets just do them all in one go.
	$stringcols = array("header", "footer", "css", "sitelocation", "faqlocation", "salesinfo", "ddinfo", "paypalinfo", "paypalaccount");
	foreach ($stringcols as $col) {
		$sql .= ", $col = ";
		if(!isset($production[$col]))
			$sql .= "''";
		else
			$sql .= "'" . mysql_real_escape_string($production[$col]) . "'";
	}

	// These columns are the checkbox columns.  Again, we can do them all in one go.
	$checkboxcols = array("isclosed", "acceptsales", "acceptdd", "acceptpaypal");
	foreach($checkboxcols as $col) {
		$sql .= ", $col = ";
		if(!isset($production[$col]) || $production[$col] == 'off')
			$sql .= "0";
		else
			$sql .= "1";
	}

	// Now for columns with special handling:

	// closedate
	if(isset($production['closedate']) && $production['closedate'] != '') {
		if(preg_match('/^\d{4}-\d{2}-\d{2}$/', $production['closedate']) !== 1)
			return "Please format the closed date as Year-Month-Day (for example 2010-01-01)";
		$sql .= ", closedate = '" . $production['closedate'] . "'";
	}

	// groupticketsamount
	if(isset($production['groupticketsamount'])) {
		$sql .= ", groupticketsamount = " . (int)$groupticketsamount;
	}

	// theatre
	$sql .= ", theatre = ";
	if(!isset($production['theatre']))
		return "Please enter a valid theatre";
	else
		$sql .= "'" . mysql_real_escape_string($production['theatre']) . "'";


	// Complete the SQL command:
	$sql .= " WHERE id = " . (int)$prodid . "";

	if(mysql_query($sql, $link)) {
		rbslog("Updated production " . $production['name']);
		return $prodid;
	} else
		return "Update query failed ".mysql_error();
}

/**
 *a HTML drop-down box of all productions for admin login
 */
function production_dropdown($link, $prodid) {
	$prods = get_prodlist($link);
	echo "<select name=\"production\">\n";
	echo "<option value=\"NULL\">Select Production</option>\n";
	foreach($prods as $prod){
		$val = $prod['id'];
		$name = $prod['name'];
		if($val == $prodid){
			echo "<option value=\"$val\" selected=\"selected\">$name</option>\n";
		}else{
			echo "<option value=\"$val\">$name</option>\n";
		}
	}

	echo "</select>\n";

}
?>
