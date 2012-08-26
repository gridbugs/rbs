<?
/**
 * Utility functions for managing performances.
 */

include_once('utilities.php');

/**
 * Gets a list of performances for a specific show.
 * Show is assumed to exist.
 */
function get_performances($link, $prod) {
	$prod = (int)$prod;

	$q_performances = "select p.*, UNIX_TIMESTAMP(p.date) tsdate from performance p where production = $prod order by date asc";
	$r_performances = mysql_query($q_performances, $link);
	if(!$r_performances)
		return array();

	$perfs = array();
	while($row = mysql_fetch_array($r_performances, MYSQL_ASSOC)) {
		$perfs[] = $row;
	}

	return $perfs;
}

/**
 * This function gets the information for a performance given an id
 */
function get_performance($link, $performance) {
	$sql = "select * from performance where id = $performance";
	$result = sql_get_array($link, $sql);
	if(!$result)
		return null;
	else {
		return $result[0];
	}
}

function get_closed_segments($link, $perfid) {
	$perfid = (int)$perfid;
	// Get the closed segments for a performance
	$sql = "SELECT segment FROM closedsegment WHERE performance = $perfid";
	$results = sql_get_array($link, $sql);
	$closedsegments = array();
	foreach($results as $result)
		$closedsegments[] = $result['segment'];
	return $closedsegments;
}

?>
