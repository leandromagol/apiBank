<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Controller\AccountController;
use App\Controller\TransactionController;
use App\Repository\AccountRepository;
use App\Repository\TransactionRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;


$container = require __DIR__ . '/../app/Config/settings.php';
AppFactory::setContainer($container);
AppFactory::setResponseFactory(new \Slim\Psr7\Factory\ResponseFactory());
$app = AppFactory::create();



$accountRepository = $container->get(AccountRepository::class);
$transactionRepository = $container->get(TransactionRepository::class);


$accountCoontroller = new AccountController($accountRepository);
$transactioonController = new TransactionController($transactionRepository, $accountRepository);

$app->get('/', function (Request $request, Response $response, $args) {
  $response->getBody()->write("Hello world!");
  return $response;
});


$app->post('/account', [$accountCoontroller, 'createAccount']);
$app->get('/account', [$accountCoontroller, 'findAccount']);
$app->post('/transaction', [$transactioonController, 'createTransaction']);
$app->run();
