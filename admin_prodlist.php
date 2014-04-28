<?
require_once('includes/utilities.php');
$link = db_connect();
require_once('includes/adminauth.php');
require_once('includes/prodmanagement.php');

if(isset($_SESSION['admin_production']))
	unset($_SESSION['admin_production']);

// If the user only has access to one production, send him straight through.
if($_SESSION['admin_superadmin'] == 0 && count($_SESSION['admin_prodlist']) == 1) {
	header("Location: admin_production.php?production=" . $_SESSION['admin_prodlist'][0]);
	exit;
}

// Grab the production list.
$prodlist = get_admin_prodlist($link);
include ('includes/groundwork-header.php');
include ('includes/superadmin-header.php'); ?>

      <article class="row">
        <section class="padded">
          <h2>Production List</h2>
		  <p>Select the production you wish to make a booking for:</p>
		  <ul>
			<?
			foreach($prodlist as $prod) {
				echo("<li>Production " . $prod['id'] . ": ");
				echo("<a href='admin_production.php?production=" . $prod['id'] . "'>" . $prod['name'] . "</a></li>");
			}?>
			
			</ul>

			</section>
      </article>
<?php include('includes/page-footer.php') ?>