<?php

namespace App\Repository;

use PDO;
use App\Domain\Entities\Account;

class AccountRepository
{
  private PDO $pdo;

  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }

  public function create(int $accountNumber, float $balance): void
  {
    $stmt = $this->pdo->prepare("INSERT INTO accounts (account_number, balance) VALUES (:account_number, :balance)");
    $stmt->execute([':account_number' => $accountNumber, ':balance' => $balance]);
  }

  public function findByAccountNumber(int $accountNumber): ?Account
  {
    $stmt = $this->pdo->prepare("SELECT account_number, balance FROM accounts WHERE account_number = :account_number");
    $stmt->execute([':account_number' => $accountNumber]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$data) {
      return null;
    }
    return new Account((int)$data['account_number'], (float)$data['balance']);
  }

  public function updateBalance(int $accountNumber, float $balance): void
  {
    $stmt = $this->pdo->prepare("UPDATE accounts SET balance = :balance WHERE account_number = :account_number");
    $stmt->execute([':balance' => $balance, ':account_number' => $accountNumber]);
  }
}
