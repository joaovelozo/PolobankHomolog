<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class ProfileController extends Controller
{
    public function getUserProfile(Request $request)
    {
        $user = $request->user();
        return response()->json(['user' => $user], 200);
    }
    public function updateUserProfile(Request $request)
    {
        $user = $request->user();

        // Valida apenas os campos que o usuário preencheu
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Atualiza o nome somente se foi enviado
        if ($request->has('name') && !empty($request->name)) {
            $user->name = $request->name;
        }

        // Atualiza o email somente se foi enviado
        if ($request->has('email') && !empty($request->email)) {
            $user->email = $request->email;
        }

        // Atualiza o avatar somente se um novo arquivo foi enviado
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('uploads/user_images'), $filename);

            // Remove o avatar antigo, se existir
            if (!empty($user->avatar)) {
                @unlink(public_path('uploads/user_images/' . $user->avatar));
            }

            // Salva o novo nome do arquivo do avatar
            $user->avatar = $filename;
        }

        // Salva as alterações no banco de dados
        $user->save();

        return response()->json(['message' => 'Seus Dados Foram Alterados Com Sucesso!'], 200);
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        // Validação das senhas
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Se a validação falhar, retorna os erros
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Verifica se a senha atual está correta
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'A senha atual está incorreta'], 400);
        }

        // Atualiza a senha
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Sua senha foi alterada com sucesso!'], 200);
    }
}



