<?
include_once('includes/utilities.php');
$link = db_connect();
include_once('includes/session.php');
include_once('includes/frames/internal.php');
include_once('includes/newutils.php');
include_once('includes/usermanagement.php');

internal_get_post_mplex(function() {
    
    /* Handle GET request */
    internal_print_header("Sign Up");
?>

<body>
<h1>Sign Up</h1>
<form method="post" action="admin_signup.php">
<div>
<label>Full Name:</label>
<input type="text" name="name">
</div>
<div>
<label>Email:</label>
<input type="text" name="email">
</div>
<div>
<label>Phone:</label>
<input type="text" name="phone">
</div>
<div>
<label>Password:</label>
<input type="password" name="password">
</div>
<div>
<label>Repeat Password:</label>
<input type="password" name="repeat_password">
</div>
<div>
<input type="submit" name="Sign Up">
</div>
</form>
</body>

<?
    internal_print_footer();
}, function($name, $email, $phone, $password, $repeat_password) {

    /* Handle POST request */


    $db = db_connect_pdo();
    $salt = rand_str();
    $stmt = $db->prepare("INSERT INTO admin(name, email, phone, password, salt) VALUES(:name, :email, :phone, :password, :salt)");
    assert($stmt->execute(array(':name' => $name, ':email' => $email, ':phone' => $phone,
        ':password' => password_hash($salt.$password, PASSWORD_DEFAULT), ':salt' => $salt)));

    header("Location: /admin_login.php");
});
?>
