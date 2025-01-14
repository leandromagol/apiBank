
<?php

use App\Domain\Entities\Account;
use App\Domain\Services\AccountService;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Exceptions\DomainException;
use App\Domain\Services\TransactionService;
use App\Repository\TransactionRepository;

beforeEach(function () {
  $this->transactionRepository = mock(TransactionRepository::class);
  $this->accountService = mock(AccountService::class);
  $this->transactionService = new TransactionService($this->transactionRepository, $this->accountService);
});

test('Account inexisting transaction', function () {
  $this->accountService->shouldReceive('findAccount')->andThrow(new NotFoundException('Account not found'));
  $this->transactionService->createTransaction(999, 50.00, 'D');
})->throws(NotFoundException::class, 'Account not found');

test('insufficient founds transaction', function () {
  $account = new Account(123, 50.00);
  $this->accountService->shouldReceive('findAccount')->andReturn($account);
  $this->transactionService->createTransaction(123, 100.00, 'D');
})->throws(NotFoundException::class, 'insufficient balance');

test('invalid payment method', function () {
  $account = new Account(123, 200.00);
  $this->accountService->shouldReceive('findAccount')->andReturn($account);

  $this->transactionService->createTransaction(123, 50.00, 'X');
})->throws(DomainException::class, 'Invalid Payment Method');

test('debit transaction successful', function () {
  $account = new Account(123, 200.00);
  $this->accountService->shouldReceive('findAccount')->andReturn($account);

  $this->accountService->shouldReceive('updateBalance')
    ->with($account, 97.00)
    ->once();

  $this->transactionRepository->shouldReceive('create')
    ->with(123, 100.00, 'D')
    ->once();

  $newBalance = $this->transactionService->createTransaction(123, 100.00, 'D');

  expect($newBalance)->toBe(97.00);
});
