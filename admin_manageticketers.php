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
            <table class="sortable">
			<tr>
			<th>Name</th>
			<th>Email</th>
			<th>Remove</th>
            <? if ($_SESSION['admin_superadmin']): ?>
            <th>Manager</th>
            <? endif ?>
			</tr>
            <?foreach(production_get_ticketers($prod['id']) as $user):?>
			<tr>
                <td><?=$user['name']?> <?if($user['can_manage']):?>
                (manager)
                <?elseif($user['superadmin']):?>
                (superadmin)
                <?endif?></td>
				<td><?=$user['email']?></td>
				<td>
				<?if($user['can_manage']):?>
                N/A
				<?else:?>
				<a class="red button" href='/admin_remove_ticketer.php?prod=<?=$prod['id']?>&admin=<?=$user['id']?>'><i class="icon-remove-sign"></i> Delete</a>
                <?endif?>
                </td>

                <? if ($_SESSION['admin_superadmin']): ?>
                <td>
                    <? if ($user['superadmin']): ?>
                        N/A
                    <? elseif ($user['can_manage']):?>
                <a class="red button" href='/admin_demote_to_ticketer.php?prod=<?=$prod['id']?>&admin=<?=$user['id']?>'>Demote to Ticketer</a>
                    <? else: ?>
                <a class="blue button" href='/admin_promote_to_manager.php?prod=<?=$prod['id']?>&admin=<?=$user['id']?>'>Promote to Manager</a>
                    <? endif ?>
                </td>
                <? endif ?>

				</tr>
            <?endforeach?>
            </table>
            
            <h3>Add Ticketer</h3>
            <form method="post" action="admin_add_ticketer.php" class="one third">
            <label>Email:</label>
            <input type="text" name="email">
            <input type="hidden" name="prod" value="<?=$prod['id']?>">
            <input type="submit" value="Add">
            </form>
            
            <h3 class="clear">Other Admins</h3>
            <table class="sortable">
			<tr>
			<th>Name</th>
			<th>Email</th>
			<th>Add</th>
			</tr>
            <?foreach(production_get_non_ticketers($prod['id']) as $user):?>
                <tr>
				<td><?=$user['name']?></td>
				<td><?=$user['email']?></td>
                <td><a class="button green" href='/admin_add_ticketer.php?prod=<?=$prod['id']?>&email=<?=$user['email']?>'><i class="icon-plus"></i> Add</a></td>
                </tr>
            <?endforeach?>
            </table>
        </section>
      </article>
<?php include('includes/page-footer.php') ?>
