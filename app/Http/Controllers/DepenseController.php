<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use Illuminate\Http\Request;

class DepenseController extends Controller
{
    public function index()
    {
       
        $depenses = Depense::all();
        return response()->json(array('depenses' => $depenses));
    }

    public function store(Request $request)
    {
        $depense = Depense::create($request->all());
        return response()->json(array('depense' => $depense));
    }

    public function update(Request $request , Depense $depense)
    {
        $depense = Depense::findOrFail($depense->id);
        $depense->update($request->all());
        return response()->json(['message' => 'Depense updated successfully', 'depense' => $depense]);
    }

    public function destroy(Request $request,Depense $depense)
    {
        $depense = Depense::findOrFail($depense->id);
        $depense->delete();
        return response()->json(['message' => 'Depense updated successfully', 'depense' => $depense]);
    }

}
