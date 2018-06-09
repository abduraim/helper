<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spending extends Model
{
    //

    protected $table = 'spendings';
    protected $fillable = [
        'expense_id',
        'sum',
    ];

    public function expense()
    {
        return $this->belongsTo('App\Expense', 'expense_id');
    }
}
