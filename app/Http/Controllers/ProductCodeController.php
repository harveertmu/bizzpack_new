<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\productCodes;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProductCodeController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        $data = productCodes::with(['vendorDetails'])
        ->filter(request(['search']))
        ->sortable()
        ->paginate($row)
        ->appends(request()->query());
        return view('productCodes.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendors = User::with('SupplierDetails')->role('vendor')->get();
        return view('productCodes.create', compact('vendors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'vendor' => 'required',
            'product_code' => 'required'


        ]);

        $input = $request->all();
        $input['status'] =(isset($input['status'])=='on')?'1':'0';
        $input['vendor_id'] =$input['vendor'];
        $input['product_code'] =$input['product_code'];
        $input['product_code_description'] =$input['description'];
        $input['insert_by'] =Auth::user()->id;
        

        
        $user = productCodes::create($input);
        return Redirect::route('product-code.index')->with('success', 'Product Code has been created!');
      
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vendors = User::with('SupplierDetails')->role('vendor')->get();
        $product = productCodes::find($id);
        // $plan_type = ['1' => 'Basic', '2' => 'Standard', '3' => 'Diamond'];
        return view('productCodes.edit',compact('product','vendors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'vendor' => 'required',
            'product_code' => 'required'

        ]);

        $input = $request->all();

       $input['vendor_id'] =$input['vendor'];
       $input['product_code'] =$input['product_code'];
       $input['product_code_description'] =$input['description'];

        $plan = productCodes::find($id);
        $plan->update($input);
        return Redirect::route('product-code.index')->with('success', 'Product code has been updated!');
   // }
       // return redirect()->route('productCodes.index')
        //->with('success','Plan Update successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        productCodes::find($id)->delete();
        return Redirect::route('product-code.index')->with('success', 'Product code has been deleted!');
    }
}
