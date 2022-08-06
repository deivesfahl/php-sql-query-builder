# SQL Query Builder

Construtor de consultas para a linguagem SQL.

# Requisitos

-   PHP 8.0 ou superior

# Instalação

```bash
git clone https://github.com/deivesfahl/php-sql-query-builder && cd php-sql-query-builder

$ composer update
```

# Executando Testes

```bash
$ ./vendor/bin/pest
```

# Utilização

```php
require __DIR__ . '/vendor/autoload.php';

use DB\Connection;
use DB\QueryBuilder\QueryBuilder;

$connection = Connection::open('localhost', 3306, 'database', 'username', 'password');
$queryBuilder = new QueryBuilder($connection);

$users = $queryBuilder->table('user')->select()->get();
```

# Métodos

## Get

```php
$users = $queryBuilder->table('user')->select()->get();
```

## First

```php
$user = $queryBuilder->table('user')->select()->first();
```

## Paginate

```php
$users = $queryBuilder
    ->table('user')
    ->select()
    ->paginate(15, [
        'path' => 'http://localhost',
        'perPage' => 5,
        'currentPage' => $_GET['page'] ?? 1,
        // 'listClass' => 'pagination',
        // 'listItemClass' => 'page-item',
        // 'listItemActiveClass' => 'active',
        // 'listItemDisabledClass' => 'disabled',
        // 'listLinkClass' => 'page-link',
        // 'listLinkAriaLabelFirst' => 'Go to first page',
        // 'listLinkLabelFirst' => 'First',
        // 'listLinkAriaLabelPrevious' => 'Go to previous page',
        // 'listLinkLabelPrevious' => 'Previous',
        // 'listLinkAriaLabel' => 'Go to page %s',
        // 'listLinkAriaLabelNext' => 'Go to next page',
        // 'listLinkLabelNext' => 'Next',
        // 'listLinkAriaLabelLast' => 'Go to last page',
        // 'listLinkLabelLast' => 'Last',
    ]);

var_dump($queryBuilder->pagination());

{
  "total": 3
  "perPage": 2
  "currentPage": 1
  "lastPage": 3
  "path": "http://localhost"
  "firstPageUrl": ""
  "previousPageUrl": ""
  "nextPageUrl": "http://localhost?page=2"
  "lastPageUrl": "http://localhost?page=3"
  "links": '<ul class="pagination">...</ul>'
}
```

## Inner Join

```php
$usersActivities = $queryBuilder
	->table('user_activity')
	->select('user.id, user.name, user_activity.data')
	->join('user', 'user.id', '=', 'user_activity.user_id')
	->get();
```

```php
$usersActivities = $queryBuilder
	->table('user_activity')
	->select('user.id, user.name, user_activity.data')
	->innerJoin('user', 'user.id', '=', 'user_activity.user_id')
	->get();
```

## Left Join

```php
$usersActivities = $queryBuilder
    ->table('user_activity')
    ->select('user.id, user.name, user_activity.data')
    ->leftJoin('user', 'user.id', '=', 'user_activity.user_id')
    ->get();
```

## Right Join

```php
$usersActivities = $queryBuilder
    ->table('user_activity')
    ->select('user.id, user.name, user_activity.data')
    ->rightJoin('user', 'user.id', '=', 'user_activity.user_id')
    ->get();
```

## Where

```php
$users = $queryBuilder
    ->table('user')
    ->select()
    ->where('id', 1)
    ->get();
```

```php
$users = $queryBuilder
    ->table('user')
    ->select()
    ->where('id', '>', 1)
    ->get();
```

## Or Where

```php
$users = $queryBuilder
    ->table('user')
    ->select()
    ->where('id', 1)
    ->orWhere('name', 'Admin')
    ->get();
```

## Like

```php
$users = $queryBuilder
    ->table('user')
    ->select()
    ->where('name', 'LIKE', 'Admin%')
    ->get();
```

## Order By

```php
$users = $queryBuilder
	->table('user')
	->select()
	->orderBy('id', 'desc')
	->orderBy('status', 'asc')
	->get();
```

## Group By

```php
$users = $queryBuilder
    ->table('user')
    ->select('id, status')
    ->groupBy('id, status')
    ->get();
```

```php
$users = $queryBuilder
    ->table('user')
    ->select('id, status')
    ->groupBy('id')
    ->groupBy('status')
    ->get();
```

## Having

```php
$usersActivities = $queryBuilder
    ->table('user_activity')
    ->select('user_id')
    ->groupBy('user_id')
    ->having('user_id > 1')
    ->get();
```

## Limit e Offset

```php
$users->table('user')->select()->limit(2)->offset(2)->get();
```

## Insert

```php
$userCreated = $queryBuilder
    ->table('user')
    ->insert([
        'username' => 'user',
        'name' => 'User',
        'email' => 'user@server.com',
        'password' => '123456',
        'profile' => 2,
        'status' => 1,
        'created_at' => date('Y-m-d H:i:s'),
    ]);
```

## Insert Get Id

```php
$userId = $queryBuilder
    ->table('user')
    ->insertGetId([
        'username' => 'user',
        'name' => 'User',
        'email' => 'user@server.com',
        'password' => '123456',
        'profile' => 2,
        'status' => 1,
        'created_at' => date('Y-m-d H:i:s'),
    ]);
```

## Update

```php
$userUpdated = $queryBuilder
    ->table('user')
    ->where('id', 1)
    ->update([
        'username' => 'user1',
        'name' => 'User 1',
        'email' => 'user1.editado@server.com',
        'password' => '123',
        'profile' => 2,
        'status' => 1,
        'updated_at' => date('Y-m-d H:i:s'),
    ]);
```

```php
$usersUpdated = $queryBuilder
    ->table('user')
    ->update([
        'username' => 'user1',
        'name' => 'User 1',
        'email' => 'user1.editado@server.com',
        'password' => '123',
        'profile' => 2,
        'status' => 1,
        'updated_at' => date('Y-m-d H:i:s'),
    ], true);
```

## Delete

```php
$userDeleted = $queryBuilder->table('user')->where('id', 1)->delete();
```

```php
$usersDeleted = $queryBuilder->table('user')->delete(true);
```

# Query Log

```php
QueryBuilder::enableQueryLog();

$queryBuilder->table('user')->select()->get();

$queryBuilder->table('user')->select()->where('id', 1)->get();

var_dump(QueryBuilder::getQueryLog());

array:2 [
  0 => array:4 [
    "query" => "SELECT * FROM user"
    "queryWithBindings" => "SELECT * FROM user"
    "bindings" => []
    "time" => 0.00033903121948242
  ]
  1 => array:4 [
    "query" => "SELECT * FROM user WHERE id > :id_628aae2e41e74 AND id < :id_628aae2e41e7e"
    "queryWithBindings" => "SELECT * FROM user WHERE id > 1 AND id < 4"
    "bindings" => array:2 [
      "id_628aae2e41e74" => 1
      "id_628aae2e41e7e" => 4
    ]
    "time" => 0.00028181076049805
  ]
]
```
