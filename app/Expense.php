<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = 'expenses';
    protected $fillable = [
      'title',
    ];

    public function weight()
    {
        return $this->hasOne('App\Weight', 'expense_id');
    }
}
