<?php

namespace App\Domain\Entities;

class Account
{
  private int $accountNumber;
  private float $balance;

  public function __construct(int $accountNumber, float $balance = 0.0)
  {
    $this->accountNumber = $accountNumber;
    $this->balance = $balance;
  }

  public function getAccountNumber(): int
  {
    return $this->accountNumber;
  }

  public function getBalance(): float
  {
    return $this->balance;
  }

  public function setBalance(float $balance): void
  {
    $this->$balance = $balance;
  }
}
