<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Barang::leftJoin('category_barangs', 'category_barangs.id', 'barangs.category_id')
            ->select('barangs.*', 'category_barangs.name as category_name')
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate data
        $data = $request->only('name', 'category_id', 'quantity');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'category_id' => 'required',
            'quantity' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new Barang
        $Barang = $this->user->Barangs()->create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'quantity' => $request->quantity
        ]);

        //Barang created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Barang created successfully',
            'data' => $Barang
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Barang  $Barang
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Barang = $this->user->Barangs()->find($id);

        if (!$Barang) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Barang not found.'
            ], 400);
        }

        return $Barang;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Barang  $Barang
     * @return \Illuminate\Http\Response
     */
    public function edit(Barang $Barang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Barang  $Barang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Barang $Barang)
    {
        //Validate data
        $data = $request->only('name', 'category_id', 'price', 'quantity');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'category_id' => 'required',
            'quantity' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update Barang
        $Barang = $Barang->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'quantity' => $request->quantity
        ]);

        //Barang updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'Barang updated successfully',
            'data' => $Barang
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Barang  $Barang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Barang $Barang)
    {
        $Barang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang deleted successfully'
        ], Response::HTTP_OK);
    }

    public function laporanStok()
    {
        return Barang::leftJoin('category_barangs', 'category_barangs.id', 'barangs.category_id')
            ->select('barangs.name', 'category_barangs.name as category_name', 'barangs.quantity as stok')
            ->get();
    }
}
