<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Spending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SpendingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $spendings = Spending::with('expense')->orderBy('created_at', 'desc')->get();

        return view('spendings.index', ['spendings' => $spendings]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $expenses = Expense::all();
        return view('spendings.create', ['expenses' => $expenses]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $spending = Spending::create($request->all());

        return redirect()->route('expenses.index')->with('success', 'Spending successful added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Spending  $spending
     * @return \Illuminate\Http\Response
     */
    public function show(Spending $spending)
    {
        $expense = $spending->expense;
        return view('spendings.show', ['spending' => $spending, 'expense' => $expense]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Spending  $spending
     * @return \Illuminate\Http\Response
     */
    public function edit(Spending $spending)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Spending  $spending
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Spending $spending)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Spending  $spending
     * @return \Illuminate\Http\Response
     */
    public function destroy(Spending $spending)
    {
        //
    }
}
