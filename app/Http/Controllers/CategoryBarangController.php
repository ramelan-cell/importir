<?php
namespace App\Http\Controllers;

use App\Models\CategoryBarang;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class CategoryBarangController extends Controller
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
        return CategoryBarang::get();
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
        $data = $request->only('name');
        $validator = Validator::make($data, [
            'name' => 'required|string',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new CategoryBarang
        $CategoryBarang = CategoryBarang::create([
            'name' => $request->name
        ]);

        //CategoryBarang created, return success response
        return response()->json([
            'success' => true,
            'message' => 'CategoryBarang created successfully',
            'data' => $CategoryBarang
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CategoryBarang  $CategoryBarang
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $CategoryBarang = CategoryBarang::find($id);
    
        if (!$CategoryBarang) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, CategoryBarang not found.'
            ], 400);
        }
    
        return $CategoryBarang;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CategoryBarang  $CategoryBarang
     * @return \Illuminate\Http\Response
     */
    public function edit(CategoryBarang $CategoryBarang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CategoryBarang  $CategoryBarang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CategoryBarang $CategoryBarang)
    {
        //Validate data
        $data = $request->only('name');
        $validator = Validator::make($data, [
            'name' => 'required|string',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update CategoryBarang
        $CategoryBarang = $CategoryBarang->update([
            'name' => $request->name
        ]);

        //CategoryBarang updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'CategoryBarang updated successfully',
            'data' => $CategoryBarang
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategoryBarang  $CategoryBarang
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoryBarang $CategoryBarang)
    {
        $CategoryBarang->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'CategoryBarang deleted successfully'
        ], Response::HTTP_OK);
    }
}