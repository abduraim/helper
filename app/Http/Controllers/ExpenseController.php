<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Weight;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenses = Expense::all();
        return view('expenses.index', ['expenses' => $expenses]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $expense = Expense::create($request->all());

        /*Создаем новые пустые веса для новой статьи расходов*/
        $emptyWeights = $this->getNewEmptyWeights();
        $newWeight = array_merge(array('expense_id' => $expense->id), $emptyWeights);

        $weight = Weight::create($newWeight);

        return redirect()->route('expenses.index')->with('success', 'Expense successful added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        return view('expenses.show', ['expense' => $expense]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        $expense->update($request->all());

        return redirect('/expenses/' . $expense->id)->with('success', 'Expense success update!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect('/expenses/')->with('success', 'Successfully deleted the Expense!');
    }

    protected function getNewEmptyWeights()
    {
        $minutes = [];
        for ($i = 0; $i < 1440; $i++) {
            $minutes[$i] = 0;
        }
        $mi = implode('/', $minutes);
        unset($minutes);

        $week = [];
        for ($i = 0; $i < 7; $i++) {
            $week[$i] = 0;
        }
        $we = implode('/', $week);
        unset($week);

        $month = [];
        for ($i = 0; $i < 31; $i++) {
            $month[$i] = 0;
        }
        $mo = implode('/', $month);
        unset($month);

        $ago = [];
        for ($i = 0; $i < 732; $i++) {
            $ago[$i] = 0;
        }
        $ag = implode('/', $ago);
        unset($ago);

        $sum = [];
        for ($i = 0; $i < 5000; $i++) {
            $sum[$i] = 0;
        }
        $su = implode('/', $sum);
        unset($sum);

        $result = [
            'minute_of_day' => $mi,
            'day_of_week' => $we,
            'day_of_month' => $mo,
            'day_ago' => $ag,
            'sum' => $su,
        ];

        return $result;
    }
}
