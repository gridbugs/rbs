<?

/**
 * This is to be included at the head of every single authenticated user page as well as the production user login page
 */
function print_prod_header($link, $production, $htmlheaders = "", $bodyattrs = "") {
?>
<!DOCTYPE html>
<html>
<head>
<title><?=$production['name']?></title>
<link href='http://fonts.googleapis.com/css?family=Muli:300,400' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Bitter:700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="css/global.css" />
<link rel="shortcut icon" type="image/ico" href="<?=$production['sitelocation']?>/favicon.ico" />
<!-- framework css -->
    <link type="text/css" rel="stylesheet" href="css/groundwork.css"><!--[if IE]>
    <link type="text/css" rel="stylesheet" href="css/groundwork-ie.css"><![endif]--><!--[if lt IE 9]>
    <script type="text/javascript" src="js/libs/html5shiv.min.js"></script><![endif]--><!--[if IE 7]>
    <link type="text/css" rel="stylesheet" href="css/font-awesome-ie7.min.css"><![endif]-->
<script type="text/javascript" src="js/global.js" ></script>


<?=$htmlheaders?>


</head>
<body <?=$bodyattrs?>>
<?=$production['header']?>
<?
}

/**
 * This is to be included at the base of every single authenticated user page as well as the production login page
 */
function print_prod_footer($link, $production) {
?>

<?=$production['footer']?>
</body>
</html>
<?
}
?>
