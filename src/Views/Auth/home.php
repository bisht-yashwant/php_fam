
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($title) ?></title>
</head>
<body>
    <h1>Welcome to <?= App\Core\Config::get('app_name') ?></h1>
    <?php
        $message = "this is log";
        log_error($message);
        App\Core\Log::info("This is an info log.");
        App\Core\Log::error("Something failed.");

        if (\App\Core\Auth::can('view_users')) {
            echo '<br>'.'You have permission to view_users.';
        }

        if (\App\Core\Auth::can('edit_users')) {
            echo '<br>'.'You have permission to edit_users.';
        }

        if (\App\Core\Auth::can('edit_posts')) {
            echo '<br>'.'You have permission to edit_posts.';
        }

        if (\App\Core\Auth::can('delete_users')) {
            echo '<br>'.'You have permission to delete_users.';
        }

        if (\App\Core\Auth::can('delete_posts')) {
            echo '<br>'.'You have permission to delete_posts.';
        }
    ?>
    <p>This is your first dynamic view!</p>
</body>
</html>
