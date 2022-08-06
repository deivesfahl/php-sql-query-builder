<?php

use DB\Connection;
use DB\QueryBuilder\QueryBuilder;

require __DIR__ . '/vendor/autoload.php';

try {
	$connection = Connection::open('localhost', 3306, 'gpa', 'root', '');

	$queryBuilder = new QueryBuilder($connection);

	// QueryBuilder::enableQueryLog();

	// Get
	$results = $queryBuilder
		->table('user')
		->select()
		->get();

	// First
	// $results = $queryBuilder
	//     ->table('user')
	//     ->select()
	//     ->first();

	// Paginate
	// $results = $queryBuilder
	// 	->table('user')
	// 	->select()
	// 	->paginate(2, [
	// 		'path' => 'http://localhost',
	// 		'perPage' => 2,
	// 		'currentPage' => $_GET['page'] ?? 1,
	// 		// 'listClass' => '',
	// 		// 'listItemClass' => '',
	// 		// 'listItemActiveClass' => '',
	// 		// 'listItemDisabledClass' => '',
	// 		// 'listLinkClass' => '',
	// 		// 'listLinkAriaLabelFirst' => '',
	// 		// 'listLinkLabelFirst' => '',
	// 		// 'listLinkAriaLabelPrevious' => '',
	// 		// 'listLinkLabelPrevious' => '',
	// 		// 'listLinkAriaLabel' => '',
	// 		// 'listLinkAriaLabelNext' => '',
	// 		// 'listLinkLabelNext' => '',
	// 		// 'listLinkAriaLabelLast' => '',
	// 		// 'listLinkLabelLast' => '',
	// 	]);

	// dump($queryBuilder->pagination());

	// Inner Join
	// $results = $queryBuilder
	// 	->table('user_activity')
	// 	->select('user.id, user.name, user_activity.data')
	// 	->join('user', 'user.id', '=', 'user_activity.user_id')
	// 	// ->innerJoin('user', 'user.id', '=', 'user_activity.user_id')
	// 	->get();

	// Left Join
	// $results = $queryBuilder
	// 	->table('user_activity')
	// 	->select('user.id, user.name, user_activity.data')
	// 	->leftJoin('user', 'user.id', '=', 'user_activity.user_id')
	// 	->get();

	// Right Join
	// $results = $queryBuilder
	// 	->table('user_activity')
	// 	->select('user.id, user.name, user_activity.data')
	// 	->rightJoin('user', 'user.id', '=', 'user_activity.user_id')
	// 	->get();

	// Where
	// $results = $queryBuilder
	// 	->table('user')
	// 	->select()
	// 	->where('id', 1)
	// 	// ->where('id', '>=', 1)
	// 	// ->where('id', '<=', 3)
	// 	// ->where('id', '=', 1)
	// 	// ->where('id', '>', 1)
	// 	// ->where('id', '<', 3)
	// 	->get();

	// Or Where
	// $results = $queryBuilder
	// 	->table('user')
	// 	->select()
	// 	->where('id', 1)
	// 	->where('name', 'Administrador')
	// 	->orWhere('name', 'User 1')
	// 	->orWhere('name', 'User 2')
	// 	->get();

	// Like
	// $results = $queryBuilder
	// 	->table('user')
	// 	->select()
	// 	->where('name', 'LIKE', 'Admin%')
	// 	->orWhere('name', 'LIKE', '%2')
	// 	->get();

	// Order By
	// $results = $queryBuilder
	// 	->table('user')
	// 	->select()
	// 	->orderBy('id', 'desc')
	// 	->orderBy('status', 'asc')
	// 	->get();

	// Group By
	// $results = $queryBuilder
	// 	->table('user')
	// 	->select('id, status')
	// 	->groupBy('id')
	// 	->groupBy('status')
	// 	->get();

	// Having
	// $results = $queryBuilder
	// 	->table('user_activity')
	// 	->select('user_id')
	// 	->groupBy('user_id')
	// 	->having('user_id > 1')
	// 	->get();

	// Limit
	// $results = $queryBuilder
	// 	->table('user')
	// 	->select()
	// 	->limit(1)
	// 	->get();

	// dd(QueryBuilder::getQueryLog());

	// Insert
	// $results = $queryBuilder
	// 	->table('user')
	// 	->insert([
	// 		'username' => 'user9999',
	// 		'name' => 'User 9999',
	// 		'email' => 'user9999@server.com',
	// 		'password' => '$argon2id$v=19$m=65536,t=4,p=1$QVh5SE5uTXVzbGdyQnlwQg$f5AR4/9YZShxR6KK/zitHLwx5DJJuQecPZO6ESFwxro',
	// 		'profile' => 2,
	// 		'status' => 1,
	// 		'created_at' => date('Y-m-d H:i:s'),
	// 	]);

	// Insert Get Id
	// $id = $queryBuilder
	// 	->table('user')
	// 	->insertGetId([
	// 		'username' => 'user9999',
	// 		'name' => 'User 9999',
	// 		'email' => 'user9999@server.com',
	// 		'password' => '$argon2id$v=19$m=65536,t=4,p=1$QVh5SE5uTXVzbGdyQnlwQg$f5AR4/9YZShxR6KK/zitHLwx5DJJuQecPZO6ESFwxro',
	// 		'profile' => 2,
	// 		'status' => 1,
	// 		'created_at' => date('Y-m-d H:i:s'),
	// 	]);

	// Update
	// $results = $queryBuilder
	// 	->table('user')
	// 	->where('id', 15)
	// 	->update([
	// 		'username' => 'user9999.editado',
	// 		'name' => 'User 9999 Editado',
	// 		'email' => 'user9999.editado@server.com',
	// 		'password' => '123456',
	// 		'profile' => 1,
	// 		'status' => 2,
	// 		'updated_at' => date('Y-m-d H:i:s'),
	// 	]);

	// Delete
	// $results = $queryBuilder
	// 	->table('user')
	// 	->where('id', 15)
	// 	->delete();

	if (isset($id)) {
		dd($id);
	}

	if (isset($results)) {
		dd($results);
	}
} catch (Throwable $exception) {
	dd($exception->getMessage() . ' - ' . $exception->getFile() . ' - ' . $exception->getLine());
}
