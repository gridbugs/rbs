<?
require_once('includes/utilities.php');
$link = db_connect();
require_once('includes/adminauth.php');
include_once('includes/newutils.php');
require_once('includes/prodmanagement.php');
require_once('includes/perfmanagement.php');
include_once('includes/frames/internal.php');


internal_get_post_mplex_simple(function() {
    global $link;
    $perf = get_performance($link, $_GET['perf']);
?>
<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]>   <html class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]>   <html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!-->
<html lang="en" class="no-js">
  <!--<![endif]-->
 <head>
  	<title>RBS Admin - Manage Ticketers</title>
  	<?php include ('includes/groundwork-header.php') ?>  
 </head>
<body>
<?php include('includes/page-header.php') ?>
<div class="container">
      <article class="row">
        <section class="padded">
<h2>Edit Performance "<?=$perf['title']?>"</h2>
<a href="/admin_editproduction.php?tab=performances">Back to Edit Production</a>
<form method="post" action="/admin_edit_performance.php">
<input type="hidden" name="prod" value="<?=$perf['production']?>">
<input type="hidden" name="perf" value="<?=$perf['id']?>">
<table>
<tr>
	<td>Date (yyyy-mm-dd):</td>
	<td><input type="text" name="date" value="<?=$perf['date']?>"></td>
</tr>
<tr>
    <td>Start Time:</td>
    <td><input type="text" name="starttime" value="<?=$perf['starttime']?>"></td>
</tr>
<tr>
    <td>Finish Time:</td>
    <td><input type="text" name="finishtime" value="<?=$perf['finishtime']?>"></td>
</tr>
<tr>
    <td>Description:</td>
    <td><textarea name="description"><?=$perf['description']?></textarea></td>
</tr>
<tr>
    <td>Title:</td>
    <td><input type="text" name="title" value="<?=$perf['title']?>"></td>
</tr>
<tr>
    <td>Closed:</td>
    <td><input type="checkbox" name="isclosed" <?if($perf['isclosed']) echo "checked"?>></td>
</tr>
<tr>
    <td>Closed Message:</td>
    <td><textarea name="closedmessage"><?=$perf['closedmessage']?></textarea></td>
</tr>
<tr>
    <td>Auto Expire:</td>
    <td><input type="checkbox" name="autoexpire" <?if($perf['autoexpire']) echo "checked"?>></td>
</tr>
<tr>
	<td>Deadline (yyyy-mm-dd hh:mm:ss):</td>
	<td><input type="text" name="deadline" value="<?=$perf['deadline']?>"></td>
</tr>
<tr>
    <td><input type="submit" value="Save Performance"></td>
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
    $perf_id = $_POST['perf'];
    check_can_manage_production($prod_id);
    
    $db = db_connect_pdo();

    $stmt = $db->prepare(<<<EOT
UPDATE performance
SET date = :date,
    starttime = :starttime,
    finishtime = :finishtime,
    description = :description,
    title = :title,
    isclosed = :isclosed,
    closedmessage = :closedmessage,
    autoexpire = :autoexpire,
    paywindow = :paywindow,
    expiretimeofday = :expiretimeofday,
    deadline = :deadline
WHERE id = :perf_id
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
        ':deadline' => $_POST['deadline'],
        ':perf_id' => $perf_id
    ))) {
        die ("Failed to update performance.");
    }
    
    header('Location: /admin_editproduction.php?tab=performances');

    
});


