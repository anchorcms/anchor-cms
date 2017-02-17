<?php

namespace Anchorcms\Services;

use Doctrine\DBAL\{
    Configuration,
    Connection,
    DriverManager,
    DBALException
};
use Doctrine\DBAL\Logging\{
    DebugStack,
    SQLLogger
};

class Installer
{
    protected $paths;

    protected $session;

    protected $logger;

    protected $connection;

    public function __construct(array $paths, $session, SQLLogger $logger = null)
    {
        $this->paths = $paths;
        $this->session = $session;
        $this->logger = null === $logger ? new DebugStack : $logger;
    }

    public function isInstalled(): bool
    {
        return is_dir($this->paths['config']);
    }

    public function getDatabaseConnection(array $params): Connection {
        if (!($this->connection instanceof Connection)) {
            $config = new Configuration;
            $config->setSQLLogger($this->logger);

            $this->connection = DriverManager::getConnection([
                'user' => $params['db_user'],
                'password' => $params['db_password'],
                'host' => $params['db_host'],
                'port' => $params['db_port'],
                'dbname' => $params['db_dbname'],
                'driver' => $params['db_driver'],
            ], $config);
        }

        return $this->connection;
    }

    protected function getLastQuery(): array {
        return end($this->logger->queries);
    }

    public function run(array $input, Auth $auth)
    {
        $input['app_secret'] = bin2hex(random_bytes(32));

        $this->copySampleConfig($input);

        $conn = $this->getDatabaseConnection($input);

        if (!$conn->isConnected()) {
            throw new \Exception('Unable to connect to database using details provided');
        }

        try {
            $this->runSchema($conn, $input);
            $this->setupDatabase($conn, $input, $auth);
        }
        catch(DBALException $exception) {
            $this->tearDown($conn, $input['db_table_prefix']);

            throw $exception;
        }
    }

    protected function tearDown(Connection $conn, string $prefix) {
        $path = $this->paths['config'];

        $pattern = sprintf('%s/*.json', $path);

        foreach (glob($pattern) as $src) {
            unlink($src);
        }

        rmdir($path);

        foreach([
            'categories',
            'custom_fields',
            'meta',
            'page_meta', 'pages',
            'post_meta', 'posts',
            'users', 'user_tokens',
        ] as $table) {
            $conn->query('DROP TABLE IF EXISTS ' . $conn->quoteIdentifier($prefix.$table));
        }
    }

    protected function copySampleConfig(array $input)
    {
        $path = $this->paths['config'];

        if (false === is_dir($path)) {
            mkdir($path, 0700, true);
        }

        $pattern = sprintf('%s/*.json', dirname($path).'/config-samples');

        if ($input['db_path']) {
            $input['db_path'] = $this->paths['storage'].'/'.$input['db_path'];
        }

        foreach (glob($pattern) as $src) {
            $file = pathinfo($src);
            $dst = sprintf('%s/%s', $path, $file['basename']);
            $contents = file_get_contents($src);
            $params = json_decode($contents, true);

            foreach (array_keys($params) as $key) {
                $var = sprintf('%s_%s', $file['filename'], $key);
                if (array_key_exists($var, $input)) {
                    $params[$key] = $input[$var];
                }
            }

            file_put_contents($dst, json_encode($params, JSON_PRETTY_PRINT));
        }
    }

    protected function runSchema(Connection $conn, array $input)
    {
        $path = $this->paths['resources'].'/schema_'.substr($input['db_driver'], 4).'.sql';
        $schema = file_get_contents($path);

        // replace table prefix
        $schema = str_replace('{prefix}', $input['db_table_prefix'], $schema);

        // split into statements
        $statements = array_filter(array_map('trim', explode(';', $schema)));

        foreach ($statements as $sql) {
            $conn->query($sql);
        }
    }

    protected function setupDatabase(Connection $conn, array $input, Auth $auth)
    {
        $conn->insert($input['db_table_prefix'].'categories', [
            'title' => 'Uncategorised',
            'slug' => 'uncategorised',
            'description' => 'Ain\'t no category here.',
        ]);

        $category = $conn->lastInsertId();

        $conn->insert($input['db_table_prefix'].'users', [
            'username' => $input['account_username'],
            'password' => $auth->hashPassword($input['account_password']),
            'email' => $input['account_email'],
            'name' => $input['account_username'],
            'bio' => 'The bouse',
            'status' => 'active',
            'user_role' => 'admin',
        ]);

        $user = $conn->lastInsertId();

        $conn->insert($input['db_table_prefix'].'pages', [
            'parent' => 0,
            'slug' => 'posts',
            'name' => 'Posts',
            'title' => 'My posts and thoughts',
            'content' => 'Welcome!',
            'html' => '<p>Welcome!</p>',
            'status' => 'published',
            'redirect' => '',
            'show_in_menu' => 1,
            'menu_order' => 0,
        ]);

        $page = $conn->lastInsertId();

        $conn->insert($input['db_table_prefix'].'pages', [
            'parent' => 0,
            'slug' => 'about',
            'name' => 'About',
            'title' => 'About Me',
            'content' => 'Welcome to my about me section.',
            'html' => '<p>Welcome to my about me section.</p>',
            'status' => 'published',
            'redirect' => '',
            'show_in_menu' => 1,
            'menu_order' => 1,
        ]);

        $conn->insert($input['db_table_prefix'].'posts', [
            'title' => 'Hello World',
            'slug' => 'hello-world',
            'content' => 'Hello World!'."\n\n".'This is the first post.',
            'html' => '<p>Hello World!</p>'."\n\n".'<p>This is the first post.</p>',
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s'),
            'published' => date('Y-m-d H:i:s'),
            'author' => $user,
            'category' => $category,
            'status' => 'published',
        ]);

        $meta = [
            'home_page' => $page,
            'posts_page' => $page,
            'posts_per_page' => 6,
            'admin_posts_per_page' => 10,
            'sitename' => $input['site_name'],
            'description' => $input['site_description'],
            'theme' => 'default',
            'global_twitter' => 'anchorcms',
        ];

        foreach ($meta as $key => $value) {
            $conn->insert($input['db_table_prefix'].'meta', [
                'meta_key' => $key,
                'meta_value' => $value,
            ]);
        }
    }
}
