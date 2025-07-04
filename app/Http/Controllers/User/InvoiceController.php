<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use PDF;
use Auth;

class InvoiceController extends Controller
{


    /**
     * Display the specified resource.
     */
    public function show($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        $user = Auth::user();
        $pdf = \PDF::loadView('users.invoice.invoice', compact('transaction', 'user'));

        return $pdf->stream('comprovante_pix.pdf');

        return $pdf->download('comprovante_pix.pdf');
    }

}
