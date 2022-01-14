<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Barang;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class BarangKeluarController extends Controller
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
        return BarangKeluar::leftJoin('barangs', 'barangs.id', 'barang_keluars.barang_id')
            ->select('barang_keluars.*', 'barangs.name as barang_name')
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
        $data = $request->only('tanggal_keluar', 'nama_customer', 'alamat', 'barang_id', 'qty', 'harga');
        $validator = Validator::make($data, [
            'tanggal_keluar' => 'required|string',
            'nama_customer' => 'required',
            'alamat' => 'required',
            'barang_id' => 'required',
            'qty' => 'required',
            'harga' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new BarangKeluar
        $BarangKeluar = BarangKeluar::create([
            'tanggal_keluar' => $request->tanggal_keluar,
            'nama_customer' => $request->nama_customer,
            'alamat' => $request->alamat,
            'barang_id' => $request->barang_id,
            'qty' => $request->qty,
            'harga' => $request->harga
        ]);

        $jml_qty_exits = Barang::where('id', $request->barang_id)->sum('quantity');

        $updateQty = Barang::where('id', $request->barang_id);
        $updateQty->update(array('quantity' => $jml_qty_exits -  $request->qty));

        //BarangKeluar created, return success response
        return response()->json([
            'success' => true,
            'message' => 'BarangKeluar created successfully',
            'data' => $BarangKeluar
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BarangKeluar  $BarangKeluar
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $BarangKeluar = BarangKeluar::find($id);

        if (!$BarangKeluar) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, BarangKeluar not found.'
            ], 400);
        }

        return $BarangKeluar;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BarangKeluar  $BarangKeluar
     * @return \Illuminate\Http\Response
     */
    public function edit(BarangKeluar $BarangKeluar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BarangKeluar  $BarangKeluar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BarangKeluar $BarangKeluar)
    {
        //Validate data
        $data = $request->only('tanggal_keluar', 'nama_customer', 'alamat', 'barang_id', 'qty', 'harga');
        $validator = Validator::make($data, [
            'tanggal_keluar' => 'required|string',
            'nama_customer' => 'required',
            'alamat' => 'required',
            'barang_id' => 'required',
            'qty' => 'required',
            'harga' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update BarangKeluar
        $BarangKeluar = $BarangKeluar->update([
            'tanggal_keluar' => $request->tanggal_keluar,
            'nama_customer' => $request->nama_customer,
            'alamat' => $request->alamat,
            'barang_id' => $request->barang_id,
            'qty' => $request->qty,
            'harga' => $request->harga
        ]);

        $jml_qty_exits = Barang::where('id', $request->barang_id)->sum('quantity');

        $updateQty = Barang::where('id', $request->barang_id);
        $updateQty->update(array('quantity' => $jml_qty_exits -  $request->qty));

        //BarangKeluar updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'BarangKeluar updated successfully',
            'data' => $BarangKeluar
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BarangKeluar  $BarangKeluar
     * @return \Illuminate\Http\Response
     */
    public function destroy(BarangKeluar $BarangKeluar)
    {
        $BarangKeluar->delete();

        return response()->json([
            'success' => true,
            'message' => 'BarangKeluar deleted successfully'
        ], Response::HTTP_OK);
    }

    public function laporanBarangKeluar(Request $request)
    {
        $data = $request->only('tgl_awal', 'tgl_akhir');
        $validator = Validator::make($data, [
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $laporan =  BarangKeluar::leftJoin('barangs', 'barangs.id', 'barang_keluars.barang_id')
            ->select('barang_keluars.tanggal_keluar', 'barang_keluars.nama_customer', 'barang_keluars.alamat', 'barangs.name as barang_name', 'barang_keluars.qty', 'barang_keluars.harga');

        if (!empty($request->tgl_awal) && !empty($request->tgl_akhir)) {
            $laporan = $laporan->where('tanggal_keluar', '>=', $request->tgl_awal)->where('tanggal_keluar', '<=', $request->tgl_akhir);
        }

        return $laporan->get();
    }
}
