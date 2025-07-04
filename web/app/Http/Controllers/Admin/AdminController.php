<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    public function loginAdmin()
    {
        return view('admin.auth.admin_login');
    }

    public function AdminProfile()
    {
        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.profile.profile',compact('adminData'));
    }
    //Update Profile Admin
    public function AdminProfileStore(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->document = $request->document;

        if($request->file('avatar')){
            $file = $request->file('avatar');
            @unlink(public_path('uploads/admin_images/'.$data->avatar));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('uploads/admin_images'),$filename);
            $data['avatar'] = $filename;
        }

        $data->save();
        $notification = array(
            'message' => 'Seus Dados Foram Alterados Com Sucesso!',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    //Update Password Admin
    public function AdminChangePassword()
    {
        return view('admin.profile.pass');
    }

    public function AdminUpdatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);
        if (!Hash::check($request->old_password, auth::user()->password)) {
            return back()->with("error", "Old Password Doesn't Match!!");
        }

        // Update The new password 
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)

        ]);

        $notification = array(
            'message' => 'Senha Alterada Com Sucesso!',
            'alert-type' => 'success'
        );
        
        return redirect()->back()->with($notification);

    } // End Mehtod 

    //Manager Admins
    public function AllAdmins()
    {
        $admins = User::where('role','admin')->latest()->get();
        return view('admin.admins.index',compact('admins'));
    }

    public function AddAdmin()
    {
        $roles = Role::all();
        return view('admin.admins.create',compact('roles'));
    }
    public function AdminUserStore(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'document' => [
                'string',
                'unique:users,document',
                function ($attribute, $value, $fail) {
                    $exists = User::where('document', $value)->exists();
                    if ($exists) {
                        $fail('CPF ou CNPJ já existe em nossa base de dados.');
                    }
                },
            ],
            'email' => 'required|email|unique:users',
            'birthdate' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'number' => 'required',
            'zipcode' => 'required',
            'neighborhood' => 'required',
            'city' => 'required',
            'state' => 'required',
            'password' => 'nullable|min:6|confirmed',
            'document_front' => ['required', 'image', 'mimes:jpg,jpeg,png'],
            'document_back' => ['required', 'image', 'mimes:,jpg,jpeg,png'],
            'selfie' => ['required', 'image', 'mimes:jpg,jpeg,png'],
        ]);
          // Salva as imagens enviadas pelo usuário em storage
            $documentFrontPath = $request->file('document_front')->store('uploads/admin/image');
            $documentBackPath = $request->file('document_back')->store('uploads/admin/image');
            $selfiePath = $request->file('selfie')->store('uploads/admin/image');
    
        $cleanPhone = preg_replace('/[^0-9]/', '', $validatedData['phone']);
    
        $user = new User();
        $user->name = $validatedData['name'];
        $user->birthdate = $validatedData['birthdate'];
        $user->phone = $validatedData['phone'];
        $user->address= $validatedData['address'];
        $user->number = $validatedData['number'];
        $user->zipcode = $validatedData['zipcode'];
        $user->neighborhood = $validatedData['neighborhood'];
        $user->city = $validatedData['city'];
        $user->state = $validatedData['state'];
        $user->document = $validatedData['document'];
        $user->email = $validatedData['email'];
        $user->password = isset($validatedData['password']) ? Hash::make($validatedData['password']) : null;
        $user->account = str_pad(mt_rand(0, 99999999-9), 9, '0', STR_PAD_LEFT) . mt_rand(0, 9);
        $user->phone = $cleanPhone;
        $user->role = 'admin'; 
        $user->status = 'active'; 

         // Salva os caminhos das imagens no banco de dados
         $user->document_front = $documentFrontPath;
         $user->document_back = $documentBackPath;
         $user->selfie = $selfiePath;
    
        try {
            $user->save();
            $notification = [
                'message' => 'Administrador Criado Com Sucesso!',
                'alert-type' => 'success'
            ];
        } catch (\Exception $e) {
            // Se a criação falhar, capture a exceção e forneça uma mensagem de erro
            $notification = [
                'message' => 'Erro ao criar gerente: ' . $e->getMessage(),
                'alert-type' => 'error'
            ];
        }
    
        return redirect()->route('all.admin')->with($notification);
    }

    public function EditAdminRole($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();

        return view('admin.admins.edit',compact('user','roles'));
    }

   public function adminUserUpdate(Request $request, $id)
   {
    $user = User::findOrFail($id);
    $user->name = $request->name;
    $user->phone = $request->phone;
    $user->email = $request->email;
    $user->document = $request->document;
    $user->role = 'admin';
    $user->status = 'active';
    $user->update();

    $user->roles()->detach();
    if ($request->roles) {
        $user->assignRole($request->roles);
    }

     $notification = array(
        'message' => 'Administrador Atualizado Com Sucesso!',
        'alert-type' => 'success'
    );

    return redirect()->route('all.admin')->with($notification);
   }

   public function DeleteAdminRole($id){

    $user = User::findOrFail($id);
    if (!is_null($user)) {
        $user->delete();
    }

     $notification = array(
        'message' => 'Administrador Apagado Com Successo',
        'alert-type' => 'success'
    );

    return redirect()->back()->with($notification);

}// End Mehtod 


    
}
        
   

