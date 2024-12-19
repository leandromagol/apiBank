<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Services\TransactionService;
use App\Domain\Services\AccountService;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Exceptions\DomainException;
use App\Repository\TransactionRepository;
use App\Repository\AccountRepository;

class TransactionController
{
  private TransactionService $transactionService;

  public function __construct(TransactionRepository $transactionRepository, AccountRepository $accountRepository)
  {
    $accountService = new AccountService($accountRepository);
    $this->transactionService = new TransactionService($transactionRepository, $accountService);
  }

  public function createTransaction(Request $request, Response $response): Response
  {
    $data = json_decode((string)$request->getBody(), true);
    $paymentMethod = $data['payment_method'] ?? '';
    $accountNumber = (int)($data['account_number'] ?? 0);
    $value = (float)($data['value'] ?? 0);

    try {
      $newBalance = $this->transactionService->createTransaction($accountNumber, $value, $paymentMethod);
      $response->getBody()->write(json_encode([
        'account_number' => $accountNumber,
        'balance' => $newBalance
      ]));
      return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } catch (NotFoundException $e) {
      $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
      return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    } catch (DomainException $e) {
      $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
      return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
  }
}
