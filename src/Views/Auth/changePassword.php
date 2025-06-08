<form class="form-signup" method="POST" action="">
    <h1 class="h3 font-weight-normal">New Password</h1>
    <p class="mb-3">
        Please create an new password that you don't use on any other site.
    </p>
    <!-- CSRF Token -->
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
    
    <!-- Flash messages -->
    <?php if (has_flash('error')): ?>
    <div class="alert alert-danger"><?= get_flash('error') ?></div>
    <?php endif; ?>
    <?php if (has_flash('success')): ?>
    <div class="alert alert-success"><?= get_flash('success') ?></div>
    <?php endif; ?>
    
    <div class="form-group text-left">
        <label for="new-password">Create New Password</label>
        <input type="password" id="new-password" name="new-password" class="form-control" placeholder="Create new password" required>
    </div>
    <div class="form-group text-left">
        <label for="confirm-password">Confirm Password</label>
        <input type="password" id="confirm-password" name="confirm-password" class="form-control" placeholder="Confirm your password" required>
    </div>
    
    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
</form>
