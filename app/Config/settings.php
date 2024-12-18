<?php

namespace App\Config;

use App\Repository\AccountRepository;
use DI\Container;
use PDO;

return (function () {
  $container = new Container();

  $container->set('db', function () {
    $host = 'mysql';
    $dbname = 'bank_database';
    $user = 'root';
    $pass = 'root';

    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
  });

  $container->set(AccountRepository::class, function ($c) {
    return new AccountRepository($c->get('db'));
  });


  return $container;
})();
