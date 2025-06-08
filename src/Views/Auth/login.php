<form class="form-signin" method="POST" action="">
    <h1 class="h3 mb-3 font-weight-normal">Sign in to your account</h1>

    <!-- CSRF Token -->
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>">

    <!-- Flash messages -->
    <?php if (has_flash('error')): ?>
    <div class="alert alert-danger"><?= get_flash('error') ?></div>
    <?php endif; ?>
    <?php if (has_flash('success')): ?>
    <div class="alert alert-success"><?= get_flash('success') ?></div>
    <?php endif; ?>

    <!-- Email -->
    <div class="form-group text-left">
        <label for="email">Email address</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="name@example.com" required autofocus>
    </div>

    <!-- Password -->
    <div class="form-group text-left">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
    </div>

    <!-- Remember & Forgot -->
    <div class="d-flex justify-content-end align-items-center mb-3">
        <a href="/forgot-password" class="small">Forgot password?</a>
    </div>

    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    <p class="mt-3 mb-0 text-muted small">
        Don’t have an account? <a href="/signup">Sign up</a>
    </p>
</form>
