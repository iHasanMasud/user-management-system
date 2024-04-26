<header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <?php if ($authentication->isLoggedIn()): ?>
                <?php if ($authentication->isAdmin()): ?>
                    <li><a href="index.php?action=users">Users</a></li>
                <?php else: ?>
                    <li><a href="index.php?action=profile">Profile</a></li>
                <?php endif; ?>
                <li><a href="index.php?action=logout">Logout</a></li>
            <?php else: ?>
                <li><a href="index.php?action=login">Login</a></li>
                <li><a href="index.php?action=register">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>