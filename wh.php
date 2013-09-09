<?php

include_once('includes/utilities.php');
$link = db_connect();
include_once('includes/userauth.php');

include_once('includes/perfmanagement.php');
include_once('includes/prodmanagement.php');
include_once('includes/frames/prodtheme.php');
include_once('includes/bookingmanagement.php');

$production = get_production($link, $_SESSION['production']);

include_once('includes/theatres/' . $production['theatre'] . '.inc');

$performances = get_performances($link, $_SESSION['production']);

$perfseats = get_seats_selected($link, $_SESSION['user_id']);
$bookedseats = get_seats_selected($link, $_SESSION['user_id'], 1, true);

$htmlheaders = <<<HEADER
<link rel="stylesheet" type="text/css" href="css/booking.css" />
<link rel="stylesheet" type="text/css" href="css/booking_user.css" />
HEADER;

# values here used until a table is made in the database
# to handle specials

class Deal {
    public $price; # price per ticket
    public $name;  # name of deal
    public $minimum_order;

    function __construct($p, $n, $m, $i) {
        $this->price = $p;
        $this->name = $n;
        $this->id = $i;
        $this->minimum_order = $m;
    }

    function __toString() {
        return "$this->name: \$$this->price, min: $this->minimum_order\n";
    }
}

# These should be stored in a database
$deals = array(new Deal(9, "Ticket", 1, "ticket"),
               new Deal(7, "Ticket with Beer", 1, "beer"),
               new Deal(6, "2+ Tickets with Beer", 2, "beer2"));


?>
<form action="/wh_book.php" method="POST">
<?php foreach ($deals as $deal):?>
<input id="<?php echo $deal->id?>" type="radio" name="deal-type"
       value="<?php echo $deal->id?>">
<?php echo $deal->name ?>
<br/>
<?php endforeach?>

Number of Tickets:
<select id="num-tickets">
<?php foreach (range(1, 30) as $i):?>
    <option value="<?php echo $i?>"><?php echo $i?></option>
<?php endforeach ?>
</select><br/>

Night:
<select id="night">

<?
foreach($performances as $performance) {
    ?><option value="<?echo $performance['id']?>"
<?
    if ($performance['isclosed']) {
        echo "disabled='true'";
    }
?>
        ><?echo prettydate($performance['tsdate']) ?></option><?
	}
?>

</select><br/>

<div id="email-addresses">
Email: <input type="text" name="email0">
</div>

<input type="submit" value="Book Now">
</form>

<?php
?>
