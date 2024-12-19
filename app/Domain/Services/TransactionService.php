<?php

namespace App\Domain\Services;

use App\Domain\Services\AccountService;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Exceptions\DomainException;
use App\Repository\TransactionRepository;

class TransactionService
{
  private TransactionRepository $transactionRepository;
  private AccountService $accountService;

  public function __construct(TransactionRepository $transactionRepository, AccountService $accountService)
  {
    $this->transactionRepository = $transactionRepository;
    $this->accountService = $accountService;
  }

  public function createTransaction(int $accountNumber, float $value, string $paymentMethod): float
  {
    $account = $this->accountService->findAccount($accountNumber);
    $actualBalance = $account->getBalance();

    $valueWithTaxes = $this->calculateValueWithTaxes($value, $paymentMethod);

    if ($actualBalance < $valueWithTaxes) {
      throw new NotFoundException('insufficient balance');
    }

    $newBalance = $actualBalance - $valueWithTaxes;
    $this->accountService->updateBalance($account, $newBalance);

    $this->transactionRepository->create($accountNumber, $value, $paymentMethod);
    return $newBalance;
  }

  private function calculateValueWithTaxes(float $value, string $paymentMethod): float
  {
    return match ($paymentMethod) {
      'D' => $value + ($value * 0.03),
      'C' => $value + ($value * 0.05),
      'P' => $value,
      default => throw new DomainException('Invalid Payment Method'),
    };
  }
}
