<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeUserMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MyBankController extends Controller
{
    public function updateUser(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|integer',
            'document' => 'required|string|nullable',
            'client_id' => 'required|string|nullable',
            'client_secret' => 'required|string|nullable',
            'application_token' => 'required|string|nullable',
            'crypto_token' => 'required|string|nullable'

        ]);

        $user = User::where('account_id', $validated['account_id'])->first();
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        $wasInactive = $user->status === 'inactive';

        $updateData = array_filter([

            'document' => $validated['document'] ?? null,
            'client_id' => $validated['client_id'] ?? null,
            'client_secret' => $validated['client_secret'] ?? null,
            'application_token' => $validated['application_token'] ?? null,
            'crypto_token' => $validated['crypto_token'] ?? null,
            'status' => 'active',

        ]);
        $user->update($updateData);

        //Send Email

        if ($wasInactive && $user->email) {
            Mail::to($user->email)->send(new WelcomeUserMail($user));
        }

        return response()->json([
            'message' => 'Usuário atualizado com sucesso',
            'confirmationUrl' => 'https://webhook.site/543dc354-639e-46cd-bb16-4d9c678810e0',
            'defaultWebhook' => 'https://webhook.site/543dc354-639e-46cd-bb16-4d9c678810e0',
        ]);
    }
}
