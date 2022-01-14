<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Barang;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class BarangMasukController extends Controller
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
        return BarangMasuk::leftJoin('barangs', 'barangs.id', 'barang_masuks.barang_id')
            ->select('barang_masuks.*', 'barangs.name as barang_name')
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
        $data = $request->only('tanggal_masuk', 'nama_supplier', 'alamat', 'barang_id', 'qty', 'harga');
        $validator = Validator::make($data, [
            'tanggal_masuk' => 'required|string',
            'nama_supplier' => 'required',
            'alamat' => 'required',
            'barang_id' => 'required',
            'qty' => 'required',
            'harga' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new BarangMasuk
        $BarangMasuk = BarangMasuk::create([
            'tanggal_masuk' => $request->tanggal_masuk,
            'nama_supplier' => $request->nama_supplier,
            'alamat' => $request->alamat,
            'barang_id' => $request->barang_id,
            'qty' => $request->qty,
            'harga' => $request->harga
        ]);

        $jml_qty_exits = Barang::where('id', $request->barang_id)->sum('quantity');

        $updateQty = Barang::where('id', $request->barang_id);
        $updateQty->update(array('quantity' => $jml_qty_exits + $request->qty));

        //BarangMasuk created, return success response
        return response()->json([
            'success' => true,
            'message' => 'BarangMasuk created successfully',
            'data' => $BarangMasuk
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BarangMasuk  $BarangMasuk
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $BarangMasuk = BarangMasuk::find($id);

        if (!$BarangMasuk) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, BarangMasuk not found.'
            ], 400);
        }

        return $BarangMasuk;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BarangMasuk  $BarangMasuk
     * @return \Illuminate\Http\Response
     */
    public function edit(BarangMasuk $BarangMasuk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BarangMasuk  $BarangMasuk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BarangMasuk $BarangMasuk)
    {
        //Validate data
        $data = $request->only('tanggal_masuk', 'nama_supplier', 'alamat', 'barang_id', 'qty', 'harga');
        $validator = Validator::make($data, [
            'tanggal_masuk' => 'required|string',
            'nama_supplier' => 'required',
            'alamat' => 'required',
            'barang_id' => 'required',
            'qty' => 'required',
            'harga' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update BarangMasuk
        $BarangMasuk = $BarangMasuk->update([
            'tanggal_masuk' => $request->tanggal_masuk,
            'nama_supplier' => $request->nama_supplier,
            'alamat' => $request->alamat,
            'barang_id' => $request->barang_id,
            'qty' => $request->qty,
            'harga' => $request->harga
        ]);

        $jml_qty_exits = Barang::where('id', $request->barang_id)->sum('quantity');

        $updateQty = Barang::where('id', $request->barang_id);
        $updateQty->update(array('quantity' => $jml_qty_exits +  $request->qty));

        //BarangMasuk updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'BarangMasuk updated successfully',
            'data' => $BarangMasuk
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BarangMasuk  $BarangMasuk
     * @return \Illuminate\Http\Response
     */
    public function destroy(BarangMasuk $BarangMasuk)
    {
        $BarangMasuk->delete();

        return response()->json([
            'success' => true,
            'message' => 'BarangMasuk deleted successfully'
        ], Response::HTTP_OK);
    }

    public function laporanBarangMasuk(Request $request)
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

        $laporan =  BarangMasuk::leftJoin('barangs', 'barangs.id', 'barang_masuks.barang_id')
            ->select('barang_masuks.tanggal_masuk', 'barang_masuks.nama_supplier', 'barang_masuks.alamat', 'barangs.name as barang_name', 'barang_masuks.qty', 'barang_masuks.harga');

        if (!empty($request->tgl_awal) && !empty($request->tgl_akhir)) {
            $laporan = $laporan->where('tanggal_masuk', '>=', $request->tgl_awal)->where('tanggal_masuk', '<=', $request->tgl_akhir);
        }

        return $laporan->get();
    }
}
