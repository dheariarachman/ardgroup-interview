<?php

namespace App\Http\Controllers;

use App\Models\Savings;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class SavingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page   = $request->query('per_page');
        $date_start = $request->query('date_start');
        $date_end   = $request->query('date_end');
        $user_id    = $request->query('user_id');

        $savings = Savings::query();

        $savings->when(!is_null($user_id), function($query) use ($user_id) {
            return $query->where('user_id', $user_id);
        });

        $savings->when(!is_null($date_start), function($query) use ($date_start, $date_end) {
            return $query->whereBetween('saving_at', [$date_start, $date_end]);
        });

        return response()->json([
            'data' => $savings->paginate($per_page, ['*'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $balance = 0;
        $user_id = null;

        $input = $request->validate([
            'balance'           => 'required|min:0',
            'transaction_type'  => 'required|string',
        ]);

        if ($request->has('user_id')) {
            $user_id = $request->input('user_id');
        } else {
            $user_id= $request->user()->id;
        }

        // Check apakah user pernah menabung 
        $userSaving = Savings::firstWhere('user_id', $user_id);

        // Jika user pernah menabung, buat saldo awal sesuai denngan saldo pada tabungan
        if ($userSaving) {
            $balance = $userSaving->balance;
        }
        
        // Check jenis transaction yang di lakukan
        switch ($input['transaction_type']) {
            case 'setor':
                $balance += $input['balance'];
                break;

            case 'tarik':
                $balance -= $input['balance'];
                break;

            case 'pinjam':
                $balance -= $input['balance'];
                break;

            default:
                return response()->json([
                    'message' => 'Transaksi tersebut tidak dapat dicatat'
                ], 400);
        }

        // Apabila belum terdapat catatan / tabungan,
        // Maka assign user_id agar membuat record baru
        if (!$userSaving) {
            $userSaving->user_id = $user_id;
        }
        
        $userSaving->balance                = $balance;
        $userSaving->saving_at              = date('Y-m-d');
        $userSaving->user_id                = $user_id;
        $userSaving->last_transaction_type  = $input['transaction_type'];
        $userSaving->last_transaction_date  = date('Y-m-d');

        // Jika sudah masuk ke final catatan,
        // Buat history transaksinya
        if ($userSaving->save()) {
            $transaction = new Transaction();
            $transaction->user_id           = $user_id;
            $transaction->description       = $request->input('description');
            $transaction->transaction_type  = $input['transaction_type'];
            $transaction->transaction_at    = date('Y-m-d');
            $transaction->balance           = $input['balance'];
            $transaction->save();
        }

        return response()->json([
            'message' => 'Balance updated successfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Savings  $savings
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $user_id )
    {
        $transactions   = null;

        $data           = Savings::firstWhere('user_id', $user_id);

        if ($data) {
            $transactions = $data->transactions()->paginate($request->query('page_size'), ['*']);
        }

        return response()->json([
            'data' => [
                'detail'    => $data,
                'histories' => $transactions
            ]
        ], 200);
    }
}
