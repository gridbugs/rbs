<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]>   <html class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]>   <html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!-->
<html lang="en" class="no-js">
  <!--<![endif]-->
 <head>
 <? if (isset($_SESSION['admin_id']) && !isset($_SESSION['admin_production'])): ?>
    <? if (isset($productio) && isset($production['name'])): ?>
        <title>RBS Admin - <?=$production['name']?></title>
    <? else: ?>
        <title>RBS Admin</title>
    <?endif?>
<? else: ?>
    <title><?=$production['name']?></title>
<? endif ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
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
    <script type="text/javascript" src="js/libs/jquery-1.9.1.min.js"></script>
    <!-- framework css -->
    <link type="text/css" rel="stylesheet" href="css/groundwork.css"><!--[if IE]>
    <link type="text/css" rel="stylesheet" href="css/groundwork-ie.css"><![endif]--><!--[if lt IE 9]>
    <script type="text/javascript" src="js/libs/html5shiv.min.js"></script><![endif]--><!--[if IE 7]>
    <link type="text/css" rel="stylesheet" href="css/font-awesome-ie7.min.css"><![endif]-->
	<link type="text/css" rel="stylesheet" href="css/modifications.css">
	<link type="text/css" rel="stylesheet" href="css/booking.css">
	<? if (isset($production['css']) && !isset($_SESSION['admin_id']) && !isset($_SESSION['admin_production'])): ?>
	<link rel="stylesheet" type="text/css" href="<?=$production['css']?>" />
	<?endif?>
	<link rel="stylesheet" type="text/css" href="css/login.css" />
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
	  <script type="text/javascript" src="js/sorttable.js"></script>
</head>
<body>
