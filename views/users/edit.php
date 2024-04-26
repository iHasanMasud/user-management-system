<h2>User Profile</h2>
<form method="post">
    <input type="hidden" name="id" value="<?php echo $user->getId(); ?>">

    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo $user->getUsername(); ?>" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $user->getEmail(); ?>" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password">

    <label for="role">Role:</label>
    <select id="role" name="role" required>
        <option value="user" <?php echo $user->getRole() == 'user' ? 'selected' : ''; ?>>User</option>
        <option value="admin" <?php echo $user->getRole() == 'admin' ? 'selected' : ''; ?>>Admin</option>
    </select>

    <button type="submit" name="update">Update User</button>
</form>