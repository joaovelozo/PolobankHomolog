<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servs = Service::all();
        return view('admin.service.index',compact('servs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('admin.service.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'url' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Altere os tipos de imagem conforme necessário
        ]);
    
        $service = new Service();
        $service->title = $validatedData['title'];
        $service->description = $validatedData['description'];
        $service->url = $validatedData['url'];
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $service->image = $imageName;
        }
    
        $service->save();

        $notification = [
            'message' => 'Serviço Cadastrado Com Sucesso!',
            'alert-type' => 'success'
        ];
    
        return redirect()->route('service.index')->with($notification);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $service = Service::findOrFail($id);
    return view('admin.service.show', compact('service'));
}

/**
 * Show the form for editing the specified resource.
 */
public function edit($id)
{
    $service = Service::findOrFail($id);
    return view('admin.service.edit', compact('service'));
}

/**
 * Update the specified resource in storage.
 */
public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
        'url' => 'required|url',
        'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Altere os tipos de imagem conforme necessário
    ]);

    $service = Service::findOrFail($id);
    $service->title = $request->title;
    $service->description = $request->description;
    $service->url = $request->url;

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $imageName);
        $service->image = $imageName;
    }

    $service->save();

    return redirect()->route('service.index')->with('success', 'Service updated successfully!');
}

/**
 * Remove the specified resource from storage.
 */
public function destroy($id)
{
    $service = Service::findOrFail($id);
    $service->delete();

    $notification = [
        'message' => 'Serviço Apagado Com Sucesso!',
        'alert-type' => 'success'
    ];

    return redirect()->route('service.index')->with($notification);
}
}