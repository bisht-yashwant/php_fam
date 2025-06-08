<form class="form-signup" method="POST" action="">
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
    
    <!-- Name -->
    <div class="form-group text-left">
        <label for="email">Your name</label>
        <input type="name" name="name" id="name" placeholder="Name" required class="form-control" required autofocus>
    </div>
    
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
    
    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
    <p class="mt-3 mb-0 text-muted small">
        Already have an account? <a href="/login">Log in</a>
    </p>
</form>
