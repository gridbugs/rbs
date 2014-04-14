<?
/**
 * This is used for managing prices and setting prices for bookings.
 */

include_once('utilities.php');
include_once('newutils.php');

/**
 * Get the prices for a specific performance.
 */
function get_prices($link, $performance, $isadmin=false) {
	$performance = (int)$performance;
    $sql = "SELECT * FROM price WHERE performance = $performance";
    if (!$isadmin){
        $sql .= " AND NOT admin_only";
    }
    $sql .= " ORDER BY id ASC";
	return sql_get_array($link, $sql);
}


function get_price_by_id($link, $id){
    $sql = "SELECT * FROM price WHERE id=$id";
    return sql_get_array($link, $sql);
}


/**
 * Sets a price for the booked seat.  If the price is invalid for that performance, no change is made.
 */
function set_price_user($link, $user, $bookedseat, $price) {
	$bookedseat = (int)$bookedseat;
	$price = (int)$price;
	$user = (int)$user;

	// This SQL statement will only update if the price exists for the performance
	$sql = "UPDATE bookedseat bs, booking b, performance p, price pr";
	$sql .= " SET bs.price = $price";
	$sql .= " WHERE bs.booking = b.id AND b.performance = p.id AND pr.performance = p.id";
	$sql .= " AND pr.id = $price AND bs.id = $bookedseat AND b.user = $user";
	return mysql_query($sql, $link);
}


/**
 * Sets a price for the booked seat.  Same as the above function but without permissions checking.  For admin users.
 */
function set_price($link, $bookedseat, $price) {
	$bookedseat = (int)$bookedseat;
	$price = (int)$price;

	$sql = "UPDATE bookedseat bs";
	$sql .= " SET bs.price = $price";
	$sql .= " WHERE bs.id = $bookedseat";
	return mysql_query($sql, $link);
}

function set_prices_from_string($perf_id, $prices_str) {
    $db = db_connect_pdo();
    
    // start by removing all the prices
    $stmt = $db->prepare(<<<EOF
DELETE FROM price
WHERE performance = :perf_id
EOF
);
    $stmt->execute(array(':perf_id' => $perf_id));

    $prices_str = preg_replace('/[\s\n]/', '', $prices_str);
    $prices = split(',', $prices_str);

    foreach ($prices as $price) {
        $parts = split('=', $price);
        $name = $parts[0];
        $amount = $parts[1];

        echo "inserting ". $name . " -> " . $amount;

        $stmt = $db->prepare(<<<EOF
INSERT INTO price(performance, name, price)
VALUES (:perf_id, :name, :price)
EOF
);
    
        $stmt->execute(array(':perf_id' => $perf_id, ':name' => $name, ':price' => $amount));
    }
}

function get_prices_to_string($perf_id) {
    $db = db_connect_pdo();
    $stmt = $db->prepare(<<<EOT
SELECT *
FROM price
WHERE performance = :perf_id
EOT
);
    $stmt->execute(array(':perf_id' => $perf_id));

    return join(", ", array_map(function($price){return $price['name'].'='.$price['price'];}, $stmt->fetchAll(PDO::FETCH_ASSOC)));
}
