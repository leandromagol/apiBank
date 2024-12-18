<?php

use App\Domain\Entities\Account;
use App\Domain\Services\AccountService;
use App\Repository\AccountRepository;
use App\Domain\Exceptions\DomainException;
use App\Domain\Exceptions\NotFoundException;

beforeEach(function () {
  $this->accountRepository = mock(AccountRepository::class);
  $this->accountService = new AccountService($this->accountRepository);
});

test('create new account successfully', function () {
  $accountNumber = 123;
  $balance = 100.00;

  $this->accountRepository->shouldReceive('findByAccountNumber')->with($accountNumber)->andReturnNull();
  $this->accountRepository->shouldReceive('create')->with($accountNumber, $balance)->once();

  $account = $this->accountService->createAccount($accountNumber, $balance);
  var_dump($account);

  expect($account)->toBeInstanceOf(Account::class);
  expect($account->getBalance())->toBe($balance);
});

test('error when create an existing account', function () {
  $accountNumber = 123;
  $balance = 100.00;

  $this->accountRepository->shouldReceive('findByAccountNumber')->andReturn(new Account($accountNumber, 50));
  $this->accountService->createAccount($accountNumber, $balance);
})->throws(DomainException::class, 'Account already exists');

test('search inexisting account', function () {
  $this->accountRepository->shouldReceive('findByAccountNumber')->andReturnNull();
  $this->accountService->findAccount(999);
})->throws(NotFoundException::class, 'Account not found');

test('search existing account', function () {
  $accountNumber = 234;
  $this->accountRepository->shouldReceive('findByAccountNumber')->andReturn(new Account($accountNumber, 200.00));
  $account = $this->accountService->findAccount($accountNumber);

  expect($account)->toBeInstanceOf(Account::class);
  expect($account->getBalance())->toBe(200.00);
});
