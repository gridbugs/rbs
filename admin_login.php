<?
/**
 * The login page.
 */
include_once('includes/utilities.php');
include_once('includes/prodmanagement.php');

$link = db_connect();

?>
<html>
<head><title>RBS Administration Section</title></head>
<body>
<h1>RBS Admin Login Page</h1>
<h2>Login</h2>
<form method="post" action="admin_logintest.php">
<p>Email Address or Username: <input type="text" name="email"></p>
<p>Password: <input type="password" name="pass"></p>
<input type="submit">
</form>
</body>
</html>
