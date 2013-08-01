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
?>

<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]>   <html class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]>   <html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!-->
<html lang="en" class="no-js">
  <!--<![endif]-->
 <head>
  	<title>RBS Admin - Production List</title>
  	<?php include ('includes/groundwork-header.php') ?>  
 </head>
<body>
 <header class="white band padded">
      <div class="container">
        <h1>RBS Admin</h2>
        <nav class="nav inline menu gap-top">
          <ul>
            <li><a href="./home.html"><i class="icon-home"></i></a></li>
            <li class="menu"><a href="#">Layouts</a>
              <ul>
                <li><a href="./layout-1.html">Layout 1</a></li>
                <li><a href="./layout-2.html">Layout 2</a></li>
                <li><a href="./layout-3.html">Layout 3</a></li>
                <li><a href="./layout-4.html">Layout 4</a></li>
                <li><a href="./layout-5.html">Layout 5</a></li>
              </ul>
            </li>
            <li><a href="./kitchen-sink.html">Kitchen Sink</a></li>
          </ul>
        </nav>
      </div>
    </header>

<div class="container">
      <article class="row">
        <section class="padded">
          <h1>Production List</h1>
			<?
			foreach($prodlist as $prod) {
				echo("<p>Production " . $prod['id'] . ": ");
				echo("<a href='admin_production.php?production=" . $prod['id'] . "'>" . $prod['name'] . "</a></p>");
			}

			if($_SESSION['admin_superadmin']) {
			?>

			<p><a href="admin_newproduction.php" class="medium button">Add New Production</a></p>

			<?
			}
			?>

        </section>
      </article>
    </div>
    <footer class="footer align-right">
      <p>&copy 2013 CSE Revue</p>
    </footer>
    <!-- scripts-->
    <script type="text/javascript" src="js/plugins/jquery.cycle2.js"></script>
    <script type="text/javascript" src="js/groundwork.all.js"></script>
    <!-- google analytics-->
    <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-XXXXXXXX-X']);
      _gaq.push(['_trackPageview']);
      (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
      
    </script>

</body>
</html>
