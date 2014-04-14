<?
require_once('includes/utilities.php');
$link = db_connect();
require_once('includes/adminauth.php');
require_once('includes/prodmanagement.php');

$prod = get_production($link, $_GET['prod']);

check_can_manage_production($prod['id']);
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
            <h2>Manage ticketers for <?=$prod['name']?></h2>
        
            <h3>Current Ticketers</h3>
            <ul>
            <?foreach(production_get_ticketers($prod['id']) as $user):?>
                <li><?=$user['name']?> (<?=$user['email']?>)
                <?if($user['can_manage']):?>
                (manager)
                <?elseif($user['superadmin']):?>
                (superadmin)
                <?else:?>
                <a href='/admin_remove_ticketer.php?prod=<?=$prod['id']?>&admin=<?=$user['id']?>'>Delete</a>
                <?endif?>
                </li>
            <?endforeach?>
            </ul>
            
            <h3>Add Ticketer</h3>
            <form method="post" action="admin_add_ticketer.php">
            <label>Email:</label>
            <input type="text" name="email">
            <input type="hidden" name="prod" value="<?=$prod['id']?>">
            <input type="submit" value="Add">
            </form>
        </section>
      </article>
<?php include('includes/page-footer.php') ?>
</body>
</html>
