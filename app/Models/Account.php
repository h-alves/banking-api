<?php

namespace App\Models;

use App\Exceptions\AccountNotFoundException;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'accounts';

    protected $fillable = [
        'id',
        'balance',
    ];

    protected $casts = [
        'id' => 'integer',
        'balance' => 'integer'
    ];

    public function serialize(): array
    {
        return [
            'id' => "$this->id",
            'balance' => $this->balance,
        ];
    }

    public function deposit(int $amount): self
    {
        $this->balance += $amount;

        return $this;
    }

    public function withdraw(int $amount): self
    {
        if (!$this->canWithdraw($amount)) {
            throw new AccountNotFoundException($this->id);
        }

        $this->balance -= $amount;

        return $this;
    }

    public function canWithdraw(int $amount): bool
    {
        return $this->balance >= $amount;
    }
}
