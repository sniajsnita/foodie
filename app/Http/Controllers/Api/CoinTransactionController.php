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
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'proof' => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $proofPath = $request->file('proof')->store('proofs', 'public');

        $transaction = CoinTransaction::create([
            'type' => 'topup',
            'amount' => $request->amount,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
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
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'proof' => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $proofPath = $request->file('proof')->store('proofs', 'public');

        $transaction = CoinTransaction::create([
            'type' => 'tarik',
            'amount' => $request->amount,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'proof' => $proofPath,
        ]);

        return response()->json([
            'message' => 'Tarik request submitted.',
            'data' => $transaction
        ], 201);
    }

    public function update(Request $request, $id)
{
    $transaction = CoinTransaction::find($id);

    if (!$transaction) {
        return response()->json(['message' => 'Transaction not found'], 404);
    }

    if (
        !$request->hasAny(['amount', 'name', 'email', 'phone']) &&
        !$request->hasFile('proof')
    ) {
        return response()->json([
            'message' => 'At least one field must be provided to update the transaction.'
        ], 422);
    }

    $rules = [
        'amount' => 'sometimes|numeric|min:1',
        'name' => 'sometimes|string',
        'email' => 'sometimes|email',
        'phone' => 'sometimes|string',
        'proof' => 'sometimes|image|max:2048',
    ];

    if ($transaction->type === 'topup') {
        $rules['amount'] = 'sometimes|in:10,50,100';
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    if ($request->hasFile('proof')) {
        $proofPath = $request->file('proof')->store('proofs', 'public');
        $transaction->proof = $proofPath;
    }

    if ($request->has('amount')) {
        $transaction->amount = $request->amount;
    }
    if ($request->has('name')) {
        $transaction->name = $request->name;
    }
    if ($request->has('email')) {
        $transaction->email = $request->email;
    }
    if ($request->has('phone')) {
        $transaction->phone = $request->phone;
    }

    $transaction->save();

    return response()->json([
        'message' => 'Transaction updated successfully',
        'data' => $transaction
    ]);
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
