<?php

namespace App\Http\Controllers;

use App\Transaction;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $transactions = new Transaction();
        if (count($request->all() ) > 0) {
            //Validate filters
            Validator::make($request->except(['limit','offset']), [
                'date' => 'date|date_format:d.m.Y',
                'amount' => 'numeric',
                'customer_id' => 'integer'
            ])->validate();


            $key = 'transaction_index_per_page_'.$request->input('limit', PHP_INT_MAX).'_page_'.$request->input('page',1);
            $transactions = $transactions->newQuery();
            foreach ($request->except(['limit','offset']) as $filterKey => $filterValue) {
                switch ($filterKey) {
                    case 'date':
                        $key .= '_date_'.$filterValue;
                        $date = Carbon::parse($filterValue);
                        $transactions
                            ->whereDay('date','=',$date->day)
                            ->whereMonth('date','=',$date->month)
                            ->whereYear('date','=',$date->year);
                        break;
                    case 'customer_id':
                        $key .= '_customer_'.$filterValue;
                        $transactions->where($filterKey, '=', $filterValue);
                        break;
                    case 'amount':
                        $key .= '_amount_'.$filterValue;
                        $transactions->where($filterKey, '=', $filterValue);
                        break;
                }
            }
        }

        return response()->json(
            Cache::remember(md5($key), 60*60, function() use ($transactions, $request) {
                return $transactions->paginate($request->input('limit', PHP_INT_MAX));
            })
        );
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
           'customer_id' => 'required',
           'amount' => 'required'
        ]);
        return response(Transaction::create($request->all()));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Transaction::findOrFail($id));
    }

    public function getTransaction($customerId, $transactionId)
    {
        $record = Transaction::whereId($transactionId)
            ->whereCustomerId($customerId)
            ->first();
        if ($record)
            return response()->json($record);
        else
            return response()->json(['message' => 'Not found'], 204);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required'
        ]);
        $transaction = Transaction::findOrFail($id);
        $transaction->update([
            'amount' => $request->input('amount'),
            'date'  => Carbon::now()
        ]);
        return response()->json($transaction);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = Transaction::findOrFail($id);
        return response()->json(
           ($record->delete()) ? 'success' : 'fail'
       );
    }
}
