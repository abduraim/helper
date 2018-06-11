<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = 'expenses';
    protected $fillable = [
      'title',
    ];

    public function weights()
    {
        return $this->hasOne('App\Weight', 'expense_id');
    }

    public function spendings()
    {
        return $this->hasMany('App\Spending', 'expense_id');
    }
}
