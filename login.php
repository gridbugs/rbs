<?
/**
 * The login page.
 */

// TODO: Need to make the webmaster and producers emails be generic 

include_once('includes/utilities.php');
include_once('includes/prodmanagement.php');
include_once('includes/frames/prodtheme.php');

if(isset($_GET['p']))
    $prodid = (int)$_GET['p'];
else
    $prodid = (int)$_GET['production'];

$link = db_connect();
if(!production_exists($link, $prodid))
	die('Production does not exist');

$production = get_production($link, $prodid);

$htmlheaders='<link rel="stylesheet" type="text/css" href="css/login.css" />';
print_prod_header($link, $production, $htmlheaders);
?>

<div id="loginform">
<div id="loginblurb">Having trouble with the ticketing system?
<? if($production['faqlocation'] == '') { ?>
Please <a href="mailto:webmin.head@cserevue.org.au">Email the Webmaster</a> and we'll help as soon as we can!
<? } else { ?>
Please read the <a href="<?=$production['faqlocation']?>">FAQ</a> or, if that doesn't answer your question, <a href="mailto:webmin.head@cserevue.org.au">Email the Webmaster</a> and we'll help as soon as we can!
<br>
Please note, group bookings (fifteen or more seats) are eligible for a group discount. Please email <a href="mailto:producers@cserevue.org.au">producers@cserevue.org.au</a> if you'd like to make a group booking.
<? } ?>
</div>

<div id="login">

<h1>Login</h1>
<form method="post" action="logintest.php">
<p class="loginblurb">If you have already made an account here please login below.</p>
<div class="loginfield"><div class="loginlabel">Email Address:</div><input type="text" name="email"></div>
<div class="loginfield"><div class="loginlabel">Password:</div><input type="password" name="pass"></div>
<input type="hidden" name="production" value="<?=$prodid?>">
<div class="loginsubmit"><input type="submit" value="Login"></div>
</form>
</div>
<div id="register">
<h1>Register</h1>
<p class="loginblurb">To order tickets we would like you to enter in your contact details.  We will use these if we need to contact you about a change to the tickets or if you're running late to pick up the tickets.  We won't use these to send out spam or marketing messages.</p>
<form method="post" action="register.php">
<div class="loginfield"><div class="loginlabel">Email Address:</div><input type="text" name="email"></div>
<div class="loginfield"><div class="loginlabel">Password:</div><input type="password" name="pass"></div>
<div class="loginfield"><div class="loginlabel">Repeat Password:</div><input type="password" name="pass_repeat"></div>
<div class="loginfield"><div class="loginlabel">First Name:</div><input type="text" name="fname"></div>
<div class="loginfield"><div class="loginlabel">Last Name:</div><input type="text" name="lname"></div>
<div class="loginfield"><div class="loginlabel">Contact Phone Number:</div><input type="text" name="phone"></div>
<input type="hidden" name="production" value="<?=$prodid?>">
<div class="loginsubmit"><input type="submit" value="Register"></div>
</form>
</div>

</div>

<?
print_prod_footer($link, $production);
?>
