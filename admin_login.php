<?
/**
 * The login page.
 */
include_once('includes/utilities.php');
include_once('includes/prodmanagement.php');

$link = db_connect();

include ('includes/groundwork-header.php') ?>  

<header>
<div class="header-container row">
	<div class="one third">
        <h1><a href="admin_login.php">RBS Admin</a></h1>
	</div>
	<div class="two thirds align-right">
	<a role="button" href="/admin_login.php">Login</a>
	</div>
</div>
</header>

<div class="container">

      <article class="row">
        <section class="padded">
			<h2>Login</h2>
			<form method="post" action="admin_logintest.php" class="one third">
			<p>Email Address or Username: <input type="text" name="email" /></p>
			<p>Password: <input type="password" name="pass" /></p>
			<input type="submit">
			</form>
 		</section>
      </article>
    
<?php include('includes/page-footer.php') ?>
