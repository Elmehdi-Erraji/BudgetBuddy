<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
class DepenseController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $depenses = Depense::where('user_id', $userId)->get();
        return response()->json(array('depenses' => $depenses));
    }

    public function store(Request $request)
    {
        $userId = $request->user()->id;

        $depense = Depense::create([
            'title' => $request->title,
            'description' => $request->description,
            'expense' => $request->expense,
            'image' => $request->image,
            'user_id' => $userId,
        ]);
        return response()->json(array('depense' => $depense));
    }

    public function update(Request $request , Depense $depense)
    {
        if (Gate::denies('update', $depense)) {
            return response()->json(['error' => 'You are not authorized to update this Depense'], 403);
        }

        $depense = Depense::findOrFail($depense->id);
        $depense->update($request->all());
        return response()->json(['message' => 'Depense updated successfully', 'depense' => $depense]);
    }

    public function destroy(Request $request,Depense $depense)
    {
        if (Gate::denies('delete', $depense)) {
            return response()->json(['error' => 'You are not authorized to delete this Depense'], 403);
        }
        $depense = Depense::findOrFail($depense->id);
        $depense->delete();
        return response()->json(['message' => 'Depense updated successfully', 'depense' => $depense]);
    }

}
