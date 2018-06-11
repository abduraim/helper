<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Spending;
use App\Weight;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SpendingController extends Controller
{
    protected static $condition;

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
        $timestamp = Carbon::now();                         // Инициализируем обстоятельства
        $condition = $this->getCondition($timestamp);

        $expenses = Expense::with('weights')->get();
        $spendings = Spending::all()->sortByDesc('created_at')->unique('expense_id');

        foreach ($expenses as &$expense) {

            // Считаем Вес "Дней назад"
            $spending = $spendings->where('expense_id', '=', $expense->id)->first();
            $diffDay = $timestamp->diffInDays($spending->created_at);
            $weightDayAgo = $this->getWeight($diffDay, $expense->weights['day_ago']);

            $weightMinuteOfDay = $this->getWeight($condition['minuteOfDay'], $expense->weights['minute_of_day']);
            $weightDayOfWeek = $this->getWeight($condition['dayOfWeek'], $expense->weights['day_of_week']);
            $weightDayOfMonth = $this->getWeight($condition['dayOfMonth'], $expense->weights['day_of_month']);

            $CommonWeight = $weightMinuteOfDay + $weightDayOfWeek + $weightDayOfMonth + $weightDayAgo;

            $expense['CommonWeight'] = $CommonWeight;

            $expense['dayAgo'] = $diffDay;

            unset($expense['weights']);
        }

        $expenses = $expenses->toArray();                   // Переводим объекты в массив (для сортировки)

        usort($expenses, function ($a, $b) {                // Сортируем передаваемый массив по убыванию
            if ($a['CommonWeight'] == $b['CommonWeight']) {
                return 0;
            }
            return ($a['CommonWeight'] < $b['CommonWeight']) ? 1 : -1;
        });


        $positions = [];                                    // Запоминаем передаваемый порядок и передаем его дальше
        foreach ($expenses as $expense) {
            $positions[] += $expense['id'];
        }
        $order = implode('/', $positions);

        $sum = 260;                                         // Сумма

        return view('spendings.create', ['expenses' => $expenses, 'timestamp' => $timestamp, 'order' => $order]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'sum' => 'required',
        ]);

        foreach ($request['expense'] as $key => $value) {           // Получаем позицию, dayAgo и expense_id
            $serial = explode('/', $key);
            $position = $serial[0];
            $dayAgo = $serial[1];
            $expense_id = $value;
        }

        $sumCondition = round($request['sum'] / 10, 0);

        $condition = $this->getCondition($request['timestamp']);    // Создаем из даты массив обстоятельств

        // Обработка позиций "до"
        if ($position != 0) {
            $order = explode('/', $request['order']);
            for ($i = 0; $i < $position; $i++) {
                $weights = Weight::all()->where('expense_id', '=', $order[$i])->first();

                $minuteOfDayStr = $this->changeWeight($weights->minute_of_day, $condition['minuteOfDay'], -1);
                $dayOfWeekStr = $this->changeWeight($weights->day_of_week, $condition['dayOfWeek'], -1);
                $dayOfMonthStr = $this->changeWeight($weights->day_of_month, $condition['dayOfMonth'], -1);
                $dayAgoStr = $this->changeWeight($weights->day_ago, $dayAgo, -1);
                $sumStr = $this->changeWeight($weights->sum, $sumCondition, -1);

                $weights->update([                                          // Сохраняем измененные веса в БД
                    'minute_of_day' => $minuteOfDayStr,
                    'day_of_week' => $dayOfWeekStr,
                    'day_of_month' => $dayOfMonthStr,
                    'day_ago' => $dayAgoStr,
                    'sum' => $sumStr,
                ]);
            }
        }

        // Увеличение весов выбранного пункта
        $weights = Weight::all()->where('expense_id', '=', $expense_id)->first();

        $minuteOfDayStr = $this->changeWeight($weights->minute_of_day, $condition['minuteOfDay'], 1);
        $dayOfWeekStr = $this->changeWeight($weights->day_of_week, $condition['dayOfWeek'], 1);
        $dayOfMonthStr = $this->changeWeight($weights->day_of_month, $condition['dayOfMonth'], 1);
        $dayAgoStr = $this->changeWeight($weights->day_ago, $dayAgo, 1);
        $sumStr = $this->changeWeight($weights->sum, $sumCondition, 1);

        $weights->update([                                          // Сохраняем измененные веса в БД
            'minute_of_day' => $minuteOfDayStr,
            'day_of_week' => $dayOfWeekStr,
            'day_of_month' => $dayOfMonthStr,
            'day_ago' => $dayAgoStr,
            'sum' => $sumStr,
        ]);

        $spending = Spending::create([                              // Сохранение затраты
            'expense_id' => $expense_id,
            'sum' => $request['sum'],
        ]);

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

    /**
     * Возвращает массив с именнованными обстоятельствами
     * @param $timestamp
     */
    protected function getCondition($timestamp)
    {
        if (!($timestamp instanceof Carbon)) {
            $timestamp = new Carbon($timestamp);
        }

        $minuteOfDay = $timestamp->today()->diffInMinutes();
        $dayOfWeek = $timestamp->dayOfWeekIso - 1;
        $dayOfMonth = $timestamp->day - 1;

        $condition = array(
            'minuteOfDay' => $minuteOfDay,
            'dayOfWeek' => $dayOfWeek,
            'dayOfMonth' => $dayOfMonth,
        );

        return $condition;
    }

    /**
     * Функция возвращает Вес из Строки и текущего обстоятельства
     * @param int $condition
     * @param string $weightStr
     */
    protected function getWeight(int $condition, string $weightStr)
    {
        $weightArray = explode('/', $weightStr);
        return round(1 / (1 + exp(-0.2 * $weightArray[$condition])) * 100, 0);
    }

    protected function changeWeight(string $weightStr, int $condition, int $change)
    {
        $weightArr = explode('/', $weightStr);
        $weightArr[$condition] = $weightArr[$condition] + $change;
        return implode('/', $weightArr);
    }
}
