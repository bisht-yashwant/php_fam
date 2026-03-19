<?php
namespace App\Core;

class Console
{
    public function run($command, $args = [])
    {
        switch ($command) {
            case 'serve':
                $this->serve($args);
                break;
            case 'db:seed':
                $this->seedDatabase();
                break;
            default:
                echo "Unknown command: $command\n";
                break;
        }
    }

    protected function serve($args)
    {
        $host = 'localhost';
        $port = '8000';

        if (isset($args[0]) && strpos($args[0], ':') !== false) {
            [$host, $port] = explode(':', $args[0]);
        }

        echo "Starting PHP server at http://$host:$port\n";
        // Start the built-in PHP server with your public folder
        passthru("php -S $host:$port -t public");
    }

    protected function seedDatabase()
    {
        try {
            $pdo = \App\Core\Database::connect();

            $sqlFile = __DIR__ . '/../../database/seed.sql';

            if (!file_exists($sqlFile)) {
                echo "SQL file not found: $sqlFile\n";
                return;
            }

            $sql = file_get_contents($sqlFile);
            $pdo->exec($sql);

            echo "[-] Database seeded successfully.\n";

        } catch (\PDOException $e) {
            echo "[X] Database error: " . $e->getMessage() . "\n";
        }
    }

}
