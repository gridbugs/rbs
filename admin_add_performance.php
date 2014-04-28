<?
require_once('includes/utilities.php');
$link = db_connect();
require_once('includes/adminauth.php');
include_once('includes/newutils.php');
require_once('includes/prodmanagement.php');
require_once('includes/pricemanagement.php');
include_once('includes/frames/internal.php');


internal_get_post_mplex_simple(function() {
    global $link;
    $prodid = (int)$_SESSION['admin_production'];
    $production = get_production($link, $prodid);
?>
<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]>   <html class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]>   <html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!-->
<html lang="en" class="no-js">
  <!--<![endif]-->
 <head>
  	<title>RBS Admin - Add Performance</title>
  	<?php include ('includes/groundwork-header.php') ?>  
 </head>
<body>
<?php include('includes/page-header.php') ?>
<div class="container">
      <article class="row">
        <section class="padded">
        <h2>Add Performance to <?=$production['name']?></h2>
        <a href="/admin_editproduction.php?tab=performances">Back to Edit Production</a>
<form method="post" action="/admin_add_performance.php">
<input type="hidden" name="prod" value="<?=$production['id']?>">
<table>
<tr>
	<td>Date (yyyy-mm-dd):</td>
	<td><input type="text" name="date" /></td>
</tr>
<tr>
    <td>Start Time:</td>
    <td><input type="text" name="starttime" value="8pm" /></td>
</tr>
<tr>
    <td>Finish Time:</td>
    <td><input type="text" name="finishtime" value="10pm" /></td>
</tr>
<tr>
    <td>Description:</td>
    <td><textarea name="description"></textarea></td>
</tr>
<tr>
    <td>Title:</td>
    <td><input type="text" name="title" /></td>
</tr>
<tr>
    <td>Closed:</td>
    <td><input type="checkbox" name="isclosed" /></td>
</tr>
<tr>
    <td>Closed Message:</td>
    <td><textarea name="closedmessage">Online sales are closed for this show.</textarea></td>
</tr>
<tr>
    <td>Auto Expire:</td>
    <td><input type="checkbox" name="autoexpire" /></td>
</tr>
<tr>
	<td>Deadline (yyyy-mm-dd hh:mm:ss):</td>
	<td><input type="text" name="deadline" /></td>
</tr>
<tr>
	<td>Prices (name0=price0, name1=price1, etc, no dollar signs, whitespace ignored):</td>
    <td><textarea name="price_description">arc=10, student=12, adult=15</textarea></td>
</tr>
<tr>
    <td><input type="submit" value="Add Performance" /></td>
    <td></td>
</tr>


</table>
</form>
        </section>
      </article>
<?php include('includes/page-footer.php') ?>
</body>
</html>

<?

}, function() {

    $prod_id = $_POST['prod'];
    check_can_manage_production($prod_id);
    
    $db = db_connect_pdo();

    $stmt = $db->prepare(<<<EOT
INSERT INTO performance(production, date, starttime, finishtime, description,
                        title, isclosed, closedmessage, autoexpire, paywindow,
                        expiretimeofday, deadline)
VALUES(
    :production,
    :date,
    :starttime,
    :finishtime,
    :description,
    :title,
    :isclosed,
    :closedmessage,
    :autoexpire,
    :paywindow,
    :expiretimeofday,
    :deadline
)
EOT
);
    $isclosed = 0;
    if (isset($_POST['isclosed'])) {
        $isclosed = 1;
    }

    $autoexpire = 0;
    if (isset($_POST['autoexpire'])) {
        $autoexpire = 1;
    }
    if (!$stmt->execute(array(
        ':production' => $_POST['prod'],
        ':date' => $_POST['date'],
        ':starttime' => $_POST['starttime'],
        ':finishtime' => $_POST['finishtime'],
        ':description' => $_POST['description'],
        ':title' => $_POST['title'],
        ':isclosed' => $isclosed,
        ':closedmessage' => $_POST['closedmessage'],
        ':autoexpire' => $autoexpire,
        ':paywindow' => 3600,
        ':expiretimeofday' => 0,
        ':deadline' => $_POST['deadline']
    ))) {
        die ("Failed to add performance.");
    }

    echo "last " . $db->lastInsertId();
    set_prices_from_string($db->lastInsertId(), $_POST['price_description']);
    
    header('Location: /admin_editproduction.php?tab=performances');

    
});


