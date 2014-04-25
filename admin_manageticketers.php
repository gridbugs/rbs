<?
require_once('includes/utilities.php');
$link = db_connect();
require_once('includes/adminauth.php');
require_once('includes/prodmanagement.php');

$prod = get_production($link, $_GET['prod']);
$production = get_production($link, $_SESSION['admin_production']);

check_can_manage_production($prod['id']);
include ('includes/groundwork-header.php');
include('includes/page-header.php');
?>

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
            
            <br/>

            <h3>Other Admins</h3>
            <ul>
            <?foreach(production_get_non_ticketers($prod['id']) as $user):?>
                <li><?=$user['name']?> (<?=$user['email']?>)
                <a href='/admin_add_ticketer.php?prod=<?=$prod['id']?>&email=<?=$user['email']?>'>Add</a>
                </li>
            <?endforeach?>
            </ul>
        </section>
      </article>
<?php include('includes/page-footer.php') ?>
