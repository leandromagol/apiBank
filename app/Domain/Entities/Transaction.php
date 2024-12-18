<?php

namespace App\Domain\Entities;

class Transaction
{
  private int $accountNumber;
  private float $value;
  private string $paymentType;

  public function __construct(int $accountNumber, float $value, string $paymentType)
  {
    $this->accountNumber = $accountNumber;
    $this->value = $value;
    $this->paymentType = $paymentType;
  }

  public function getAccountNumber(): int
  {
    return $this->accountNumber;
  }

  public function getValue(): float
  {
    return $this->value;
  }

  public function getPaymentType(): string
  {
    return $this->paymentType;
  }
}
