<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<div class="card">
    <?php include 'partials/header.php'; ?>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['message']; ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    <main>
        <?php include $content; ?>
    </main>
</div>
<!--<script src="assets/js/script.js"></script>-->
</body>
</html>