<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Services\AccountService;
use App\Domain\Exceptions\DomainException;
use App\Domain\Exceptions\NotFoundException;

class AccountController
{
  private AccountService $accountService;

  public function __construct(\App\Repository\AccountRepository $accountRepository)
  {
    $this->accountService = new AccountService($accountRepository);
  }

  public function createAccount(Request $request, Response $response): Response
  {
    $data = json_decode((string)$request->getBody(), true);
    $accountNumber = $data['account_number'] ?? null;
    $balance = $data['balance'] ?? null;

    try {
      $conta = $this->accountService->createAccount((int)$accountNumber, (float)$balance);
      $response->getBody()->write(json_encode([
        'account_number' => $conta->getAccountNumber(),
        'balance' => $conta->getBalance()
      ]));
      return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } catch (DomainException $e) {
      $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
      return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
  }

  public function findAccount(Request $request, Response $response): Response
  {
    $params = $request->getQueryParams();
    $accountNumber = (int)($params['account_number'] ?? 0);

    try {
      $conta = $this->accountService->findAccount($accountNumber);
      $response->getBody()->write(json_encode([
        'account_number' => $conta->getAccountNumber(),
        'balance' => $conta->getBalance()
      ]));
      return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (NotFoundException $e) {
      $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
      return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
  }
}
