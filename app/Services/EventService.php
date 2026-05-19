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
            'id' => $account->id,
            'balance' => $account->balance,
        ];
    }

    private function depositOperation(Account $account, int $amount)
    {
        $this->accountRepository->update($account, [
            'balance' => $account->balance + $amount
        ]);
    }

    public function deposit(int $accountId, int $amount)
    {
        $account = $this->accountRepository->findById($accountId);

        $this->depositOperation($account, $amount);

        return [
            'destination' => $this->serializeAccount($account),
        ];
    }

    private function withdrawOperation(Account $account, int $amount)
    {
        if (!$account || $account->balance < $amount) {
            throw new AccountNotFoundException($account->id);
        }

        $this->accountRepository->update($account, [
            'balance' => $account->balance - $amount
        ]);
    }

    public function withdraw(int $accountId, int $amount)
    {
        $account = $this->accountRepository->findById($accountId);

        $this->withdrawOperation($account, $amount);
        
        return [
            'origin' => $this->serializeAccount($account),
        ];
    }

    public function transfer(int $originAccountId, int $destinationAccountId, int $amount)
    {
        $originAccount = $this->accountRepository->findById($originAccountId);
        $destinationAccount = $this->accountRepository->firstOrCreate([
            'id' => $destinationAccountId,
        ]);

        $this->withdrawOperation($originAccount, $amount);
        $this->depositOperation($destinationAccount, $amount);

        return [
            'origin' => $this->serializeAccount($originAccount),
            'destination' => $this->serializeAccount($destinationAccount),
        ];
    }
}