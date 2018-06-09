<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Weight extends Model
{
    protected $table = 'weights';
    protected $fillable = [
        'expense_id',
        'minute_of_day',
        'day_of_week',
        'day_of_month',
        'day_ago',
        'sum',
    ];

    public function expense()
    {
        return $this->belongsTo('App\Expense', 'expense_id');
    }
}
