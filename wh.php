<?php



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
<?php foreach ($deals as $deal):?>
<input id="<?php echo $deal->id?>" type="radio" name="deal-type"
       value="<?php echo $deal->id?>">
<?php echo $deal->name ?>
<br/>
<?php endforeach?>

<select id="num-tickets">
<?php foreach (range(1, 30) as $i):?>
    <option value="<?php echo $i?>"><?php echo $i?></option>
<?php endforeach ?>
</select>

<select id="night">
<?php foreach (range(1, 4) as $i):?>
    <option value="<?php echo $i?>"><?php echo $i?></option>
<?php endforeach ?>
</select>



<?php
?>
