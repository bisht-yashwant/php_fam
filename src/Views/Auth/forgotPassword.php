<form class="form-signup" method="POST" action="">
    <h1 class="h3 mb-3 font-weight-normal">Reset Password</h1>
    
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
        <label for="email">Enter your email address and we will send you a link to reset your password</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="name@example.com" required autofocus>
    </div>
    
    <button class="btn btn-lg btn-primary btn-block" type="submit">Send Password Reset Email</button>
    <p class="mt-3 mb-0 text-muted small">
        <a href="/login">Back to Log in</a>
    </p>
</form>
