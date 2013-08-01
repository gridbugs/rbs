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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>RBS Admin - Production List</title>
    <link rel="apple-touch-icon" href="images/apple-icons/apple-touch-icon-precomposed.png">
    <link rel="apple-touch-icon" sizes="57x57" href="images/apple-icons/apple-touch-icon-57x57-precomposed.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/apple-icons/apple-touch-icon-72x72-precomposed.png">
    <link rel="apple-touch-icon" sizes="114x114" href="images/apple-icons/apple-touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon" sizes="144x144" href="images/apple-icons/apple-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileImage" content="images/apple-icons/apple-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#ffffff">
    <!-- Modernizr -->
    <script src="js/libs/modernizr-2.6.2.min.js"></script>
    <!-- jQuery -->
    <script type="text/javascript" src="js/libs/jquery-1.8.2.min.js"></script>
    <!-- framework css -->
    <link type="text/css" rel="stylesheet" href="css/groundwork.css"><!--[if IE]>
    <link type="text/css" rel="stylesheet" href="css/groundwork-ie.css"><![endif]--><!--[if lt IE 9]>
    <script type="text/javascript" src="js/libs/html5shiv.min.js"></script><![endif]--><!--[if IE 7]>
    <link type="text/css" rel="stylesheet" href="css/font-awesome-ie7.min.css"><![endif]-->
    <script type="text/javascript">
      // extend Modernizr to have datauri test
      (function(){
        var datauri = new Image();
        datauri.onerror = function() {
          Modernizr.addTest('datauri', function () { return false; });
        };
        datauri.onload = function() {
          Modernizr.addTest('datauri', function () { return (datauri.width == 1 && datauri.height == 1); });
          Modernizr.load({
            test: Modernizr.datauri,
            nope: 'css/no-datauri.css'
          });
        };
        datauri.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
      })();
      // fallback if SVG unsupported
      Modernizr.load({
        test: Modernizr.inlinesvg,
        nope: [
          'css/no-svg.css'
        ]
      });
      // polyfill for HTML5 placeholders
      Modernizr.load({
        test: Modernizr.input.placeholder,
        nope: [
          'css/placeholder_polyfill.css',
          'js/libs/placeholder_polyfill.jquery.js'
        ]
      });
      
    </script>
  </head>
<body>
 <header class="white band padded">
      <div class="container">
        <h2>RBS Admin</h2>
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

			<p><a href="admin_newproduction.php">Add New Production</a></p>

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
