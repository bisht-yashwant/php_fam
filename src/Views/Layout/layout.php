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

<!-- Header -->
<?php if($this->navbar){ ?>
    <header class="navbar navbar-expand flex-column flex-md-row bd-navbar">
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm w-100">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="/">
                    <img src="https://flowbite.com/docs/images/logo.svg" alt="Logo" class="me-2" style="height: 30px;">
                    <?= App\Core\Config::get('app_name') ?>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarMain">
                    <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
                        <?php foreach ($this->menuItems ?? [] as $item): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $item['active'] ? 'active text-primary' : '' ?>" href="<?= $item['url'] ?>">
                                    <?= htmlspecialchars($item['label']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="d-flex">
                        <?php if (login_check()): ?>
                            <a href="/logout" class="btn btn-outline-dark mr-2">Log out</a>
                        <?php else: ?>
                            <a href="/login" class="btn btn-outline-primary mr-2">Log in</a>
                            <a href="/signup" class="btn btn-primary">Get started</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
<?php } ?>

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
