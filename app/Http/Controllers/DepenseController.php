<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

class DepenseController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $depenses = Depense::where('user_id', $userId)->get();
        return response()->json(array('depenses' => $depenses));
    }

/**
 * @OA\Info(
 *     title="depense",
 *     version="1.0",
 *     description="store depense",
 *     @OA\Contact(
 *         email="contact@example.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * @OA\Post(
 *     path="/api/depenses",
 *     tags={"Depenses"},
 *     summary="Create a new Depense",
 *     description="Create a new depense record.",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Depense data",
 *         @OA\JsonContent(
 *             required={"title", "description", "expense"},
 *             @OA\Property(property="title", type="string", example="Test Depense"),
 *             @OA\Property(property="description", type="string", example="This is a test depense"),
 *             @OA\Property(property="expense", type="number", format="float", example=50.25),
 *             @OA\Property(property="image", type="string", example="test.jpg"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Depense created successfully",
 *        
 *     ),
 * )
 */
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
        return response()->json(['depense' => $depense], 201);
    }

    public function show($id)
    {
        $depense = Depense::findOrFail($id);
        return response()->json($depense);
    }

    /**
 * @OA\Put(
 *     path="/api/depenses/{depense}",
 *     tags={"Depenses"},
 *     summary="Update a Depense",
 *     description="Update an existing depense record.",
 *     @OA\Parameter(
 *         name="depense",
 *         in="path",
 *         required=true,
 *         description="ID of the depense to be updated",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Depense data",
 *         @OA\JsonContent(
 *             required={"title", "description", "expense"},
 *             @OA\Property(property="title", type="string", example="Updated Depense"),
 *             @OA\Property(property="description", type="string", example="Updated description"),
 *             @OA\Property(property="expense", type="number", format="float", example=75.50),
 *             @OA\Property(property="image", type="string", example="updated.jpg"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Depense updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Depense updated successfully"),
 *            
 *         ),
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="You are not authorized to update this Depense",
 *     ),
 * )
 */
    public function update(Request $request , Depense $depense)
    {
    //     if (Gate::authorize('update', $depense)) {
    //         return response()->json(['error' => 'You are not authorized to update this Depense'], 403);
    //     }

        $depense = Depense::findOrFail($depense->id);
        $depense->update($request->all());
        return response()->json(['message' => 'Depense updated successfully', 'depense' => $depense]);
    }

    /**
 * @OA\Delete(
 *     path="/api/depenses/{depense}",
 *     tags={"Depenses"},
 *     summary="Delete a Depense",
 *     description="Delete an existing depense record.",
 *     @OA\Parameter(
 *         name="depense",
 *         in="path",
 *         required=true,
 *         description="ID of the depense to be deleted",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Depense deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Depense deleted successfully"),
 *             
 *         ),
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="You are not authorized to delete this Depense",
 *     ),
 * )
 */

    public function destroy(Request $request,Depense $depense)
    {
        // if (Gate::authorize('delete', $depense)) {
        //     return response()->json(['error' => 'You are not authorized to delete this Depense'], 403);
        // }
        $depense = Depense::findOrFail($depense->id);
        $depense->delete();
        return response()->json(['message' => 'Depense deleted successfully']);
    }

}
