<h2>User Profile</h2>
<form method="post">
    <input type="hidden" name="id" value="<?php echo $user->getId(); ?>">

    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo $user->getUsername(); ?>" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $user->getEmail(); ?>" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password">

    <button type="submit" name="update-profile">Update Profile</button>
</form>

<form method="post">
    <input type="hidden" name="id" value="<?php echo $user->getId(); ?>">
    <button type="submit" name="delete">Delete Account</button>
</form>