<?php

namespace App\Services;

use App\Exceptions\AccountNotFoundException;
use App\Models\Account;
use App\Repositories\AccountRepository;

class EventService
{
    protected AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    private function serializeAccount(Account $account)
    {
        return [
            'id' => "$account->id",
            'balance' => $account->balance,
        ];
    }

    private function depositOperation(int $accountId, int $amount)
    {
        $account = $this->accountRepository->findById($accountId);

        $account = $this->accountRepository->updateOrCreate([
            'id' => $accountId,
            'balance' => $account ? $account->balance + $amount : $amount,
        ]);

        return $account;
    }

    public function deposit(int $accountId, int $amount)
    {
        $account = $this->depositOperation($accountId, $amount);

        return [
            'destination' => $this->serializeAccount($account),
        ];
    }

    private function withdrawOperation(int $accountId, int $amount)
    {
        $account = $this->accountRepository->findById($accountId);

        if (!$account || $account->balance < $amount) {
            throw new AccountNotFoundException($accountId);
        }

        $this->accountRepository->update($account, [
            'balance' => $account->balance - $amount
        ]);

        return $account;
    }

    public function withdraw(int $accountId, int $amount)
    {
        $account = $this->withdrawOperation($accountId, $amount);

        return [
            'origin' => $this->serializeAccount($account),
        ];
    }

    public function transfer(int $originAccountId, int $destinationAccountId, int $amount)
    {
        $originAccount = $this->withdrawOperation($originAccountId, $amount);
        $destinationAccount = $this->depositOperation($destinationAccountId, $amount);

        return [
            'origin' => $this->serializeAccount($originAccount),
            'destination' => $this->serializeAccount($destinationAccount),
        ];
    }
}