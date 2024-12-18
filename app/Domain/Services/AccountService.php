<?php

namespace App\Domain\Services;

use App\Domain\Entities\Account;
use App\Domain\Exceptions\DomainException;
use App\Domain\Exceptions\NotFoundException;
use App\Repository\AccountRepository;

class AccountService
{
  private AccountRepository $accountRepository;

  public function __construct(AccountRepository $accountRepository)
  {
    $this->accountRepository = $accountRepository;
  }

  public function createAccount(int $accountNumber, float $balance): Account
  {
    $exists = $this->accountRepository->findByAccountNumber($accountNumber);
    if ($exists) {
      throw new DomainException('Account already exists');
    }

    $this->accountRepository->create($accountNumber, $balance);
    return new Account($accountNumber, $balance);
  }

  public function findAccount(int $accountNumber): Account
  {
    $account = $this->accountRepository->findByAccountNumber($accountNumber);
    if (!$account) {
      throw new NotFoundException('Account not found');
    }
    return $account;
  }

  public function updateBalance(Account $account, float $newBalance): void
  {
    $this->accountRepository->updateBalance($account->getAccountNumber(), $newBalance);
  }
}
