<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Savings extends Model
{
    use HasFactory;

    /**
     * Primary Key of the Tables
     * 
     * @var string
     */
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_id',
        'balance',
        'saving_at',
        'last_transaction_type',
        'last_transaction_date'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }
}
