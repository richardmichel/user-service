### user-service
user-service





 'connectionLogQueue' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('LOG_QUEUE_DB_HOST', '127.0.0.1'),
            'port' => env('LOG_QUEUE_DB_PORT', '5432'),
            'database' => env('LOG_QUEUE_DB_DATABASE', 'forge'),
            'username' => env('LOG_QUEUE_DB_USERNAME', 'forge'),
            'password' => env('LOG_QUEUE_DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],
        

git tag -a v1.0.9 -m "version 1.0.9"

git push origin v1.0.1