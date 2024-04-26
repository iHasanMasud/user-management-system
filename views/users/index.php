<h2>User Management</h2>
<?php if ($_SESSION['role'] == 'admin'): ?>
    <a href="index.php?action=create">Create User</a>
<?php endif; ?>
<form action="index.php" method="get">
    <input type="hidden" name="action" value="search">
    <input type="text" name="keyword" placeholder="Search users...">
    <button type="submit">Search</button>
</form>
<table>
    <thead>
    <tr>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user->getUsername(); ?></td>
            <td><?php echo $user->getEmail(); ?></td>
            <td><?php echo $user->getRole(); ?></td>
            <td>
                <a href="index.php?action=edit&id=<?php echo $user->getId(); ?>">Edit</a>
                <a href="index.php?action=delete&id=<?php echo $user->getId(); ?>">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="pagination">
    <?php
    $action = isset($_GET['action']) ? $_GET['action'] : 'default';

    if ($action === 'search') {
        $totalUsers = $userManager->getTotalUsersByKeyword($_GET['keyword']);
    } else {
        $totalUsers = $userManager->getTotalUsers();
    }
    $totalPages = ceil($totalUsers / 10);
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    ?>
    <?php if ($currentPage > 1): ?>
        <a href="index.php?action=users&page=<?php echo $currentPage - 1; ?>">Previous</a>
    <?php endif; ?>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="index.php?action=users&page=<?php echo $i; ?>" class="<?php echo $currentPage == $i ? 'active' : ''; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>
    <?php if ($currentPage < $totalPages): ?>
        <a href="index.php?action=users&page=<?php echo $currentPage + 1; ?>">Next</a>
    <?php endif; ?>
</div>