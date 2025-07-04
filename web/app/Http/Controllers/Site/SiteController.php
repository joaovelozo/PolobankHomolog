<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\NewLeter;
use App\Models\Remove;
use Illuminate\Http\Request;
use App\Services\StarbankService;
use Auth;

class SiteController extends Controller
{
   public function remove()
   {
    return view('site.remove.index');
   }

   public function boletos()  {
    $starkbankService = new StarbankService();


        $boleto = $starkbankService->listarBoletosPagamento(Auth::user());
        dd($boleto);
   }
   public function removeStore(Request $request)
   {
      {
         $request->validate([
             'name' => 'required|string|max:255',
             'document' => 'required|string|max:255',
             'phone' => 'required|string|max:255',
             'email' => 'required|string|max:255',
             'account' => 'required|string|max:255',
             'description' => 'required|string',
         ]);

         $remove = new Remove();
         $remove->name= $request->input('name');
         $remove->document = $request->input('document');
         $remove->phone = $request->input('phone');
         $remove->email = $request->input('email');
         $remove->account = $request->input('account');
         $remove->description = $request->input('description');



         // Salva o ticket no banco de dados
         $remove->save();

         $notification = [
             'message' => 'Em breve responderemos!',
             'alert-type' => 'success'
         ];

         // Redireciona para uma página de sucesso ou faz qualquer outra ação desejada
         return redirect()->back()->with($notification);
     }
   }

   //Politica de Privacidades
   public function privacy()
   {
    return view('site.privacy.index');
   }
   public function ralbank()
   {
    return view('site.ralbank.index');
   }
   public function libertybank()
   {
    $new = NewLeter::count();
    return view('site.liberty.index',compact('new'));
   }

   public function store(Request $request)
   {
       $validatedData = $request->validate([

           'email' => 'required',
       ]);

       $new = NewLeter::create($validatedData);

       $notification = [
           'message' => 'Seus Dados Foram Enviandos!',
           'alert-type' => 'success'
       ];

       return redirect()->back()->with($notification);
   }
   public function contact()
   {
    return view('site.contact.index');
   }

   public function formContact(Request $request)
   {
    $validatedData = $request->validate([

        'name' => 'required',
        'email' => 'required',
        'phone' => 'required',
        'content' => 'required',
    ]);

    $cont = Contact::create($validatedData);

    $notification = [
        'message' => 'Sua Mensagem Foi Enviada, Em Breve Retornaremos!',
        'alert-type' => 'success'
    ];

    return redirect()->back()->with($notification);
   }

   //User Term
   public function Term()
   {
    return view('site.term.index');
   }
   public function updateClient()
   {
    return view('site.update.index');
   }

}
