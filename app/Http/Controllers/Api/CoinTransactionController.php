<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CoinTransaction;
use Illuminate\Support\Facades\Validator;

class CoinTransactionController extends Controller
{
    public function index()
    {
        $transactions = CoinTransaction::orderBy('created_at', 'desc')->get();
        $topup = CoinTransaction::where('type', 'topup')->sum('amount');
        $tarik = CoinTransaction::where('type', 'tarik')->sum('amount');
        $totalCoin = $topup - $tarik;

        return response()->json([
            'message' => 'List of coin transactions',
            'data' => $transactions,
            'totalCoin' => $totalCoin
        ]);
    }

    public function show($id)
    {
        $transaction = CoinTransaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        return response()->json([
            'message' => 'Transaction detail',
            'data' => $transaction
        ]);
    }

    public function topup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|in:10,50,100  ',
            'proof' => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $proofPath = $request->file('proof')->store('proofs', 'public');

        $transaction = CoinTransaction::create([
            'type' => 'topup',
            'amount' => $request->amount,
            'proof' => $proofPath,
        ]);

        return response()->json([
            'message' => 'Top up request submitted.',
            'data' => $transaction
        ], 201);
    }

    public function tarik(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'proof' => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $proofPath = $request->file('proof')->store('proofs', 'public');

        $transaction = CoinTransaction::create([
            'type' => 'tarik',
            'amount' => $request->amount,
            'proof' => $proofPath,
        ]);

        return response()->json([
            'message' => 'Tarik request submitted.',
            'data' => $transaction
        ], 201);
    }

    public function destroy($id)
    {
        $transaction = CoinTransaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $transaction->delete();

        return response()->json([
            'message' => 'Transaction deleted successfully'
        ]);
    }
}
