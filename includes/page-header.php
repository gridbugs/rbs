<header class="white band padded">
      <div class="container">
        <h1>RBS Admin</h2>
        <div>Logged in as: <?=$_SESSION['admin_name']?> (<?=$_SESSION['admin_email']?>)</div>
        <nav class="nav inline menu gap-top">
          <ul>
            <li><a href="admin_prodlist.php"><i class="icon-home"></i></a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </nav>
      </div>
    </header>
