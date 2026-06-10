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

    private function depositOperation(int $accountId, int $amount)
    {
        $account = $this->accountRepository->findById($accountId) 
            ?? $this->accountRepository->create([
                'id' => $accountId,
                'balance' => 0
            ]);

        $account->deposit($amount);
        
        return $this->accountRepository->update($account, [
            'balance' => $account->balance
        ]);
    }

    public function deposit(int $accountId, int $amount)
    {
        $account = $this->depositOperation($accountId, $amount);

        return [
            'destination' => $account->serialize(),
        ];
    }

    private function withdrawOperation(int $accountId, int $amount)
    {
        $account = $this->accountRepository->findById($accountId);

        if (!$account) {
            throw new AccountNotFoundException($accountId);
        }
        $account->withdraw($amount);
        
        return $this->accountRepository->update($account, [
            'balance' => $account->balance
        ]);
    }

    public function withdraw(int $accountId, int $amount)
    {
        $account = $this->withdrawOperation($accountId, $amount);

        return [
            'origin' => $account->serialize(),
        ];
    }

    public function transfer(int $originAccountId, int $destinationAccountId, int $amount)
    {
        $originAccount = $this->withdrawOperation($originAccountId, $amount);
        $destinationAccount = $this->depositOperation($destinationAccountId, $amount);

        return [
            'origin' => $originAccount->serialize(),
            'destination' => $destinationAccount->serialize(),
        ];
    }
}