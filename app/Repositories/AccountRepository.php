<?php

namespace App\Repositories;

use App\Models\Account;

class AccountRepository
{
    public function reset()
    {
        Account::truncate();
    }

    public function findById(int $accountId)
    {
        return Account::find($accountId);
    }

    public function create(array $data)
    {
        return Account::create($data);
    }

    public function update(Account $account, array $data)
    {
        $account->update($data);
        return $account;
    }

    public function delete(int $accountId)
    {
        return Account::destroy($accountId);
    }

    public function firstOrCreate(array $data)
    {
        return Account::firstOrCreate($data);
    }
}