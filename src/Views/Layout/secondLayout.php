<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= App\Core\Config::get('app_name') ?> | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    
    <!-- Optional: Your own CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">

</head>
<body class="bg-light text-dark">

<!-- Main Content -->
<main class="container my-5">
    <?php if (has_flash('success')): ?>
        <?= flash_message('success', get_flash('success')) ?>
    <?php endif; ?>

    <?php if (has_flash('error')): ?>
        <?= flash_message('error', get_flash('error')) ?>
    <?php endif; ?>

    <?php if (has_flash('errors')): ?>
        <?= flash_message('error', get_flash('errors')) ?>
    <?php endif; ?>

    <?= $this->content ?? '<p>No content available.</p>' ?>
</main>

<!-- Bootstrap JS -->
<script src="/assets/js/jquery.min.js"></script>

</body>
</html>
