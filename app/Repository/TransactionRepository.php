<?php

namespace App\Repository;

use PDO;

class TransactionRepository
{
  private PDO $pdo;

  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }

  public function create(int $accountNumber, float $value, string $paymentMethod): void
  {
    $stmt = $this->pdo->prepare("INSERT INTO transactions (account_number, value, payment_method, date_time) VALUES (:account_number, :value, :payment_method, NOW())");
    $stmt->execute([
      ':account_number' => $accountNumber,
      ':value' => $value,
      ':payment_method' => $paymentMethod
    ]);
  }
}
