<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\Banks;
use App\Models\SupplierTypes;
use App\Models\SupplierDetails;
use Hash;
use File;
use Illuminate\Support\Facades\Auth;


class SupplierController extends Controller
{
    function __construct()
    {
        //  $this->middleware('permission:supplier-list|supplier-create|supplier-edit|supplier-delete', ['only' => ['index','show']]);
        //  $this->middleware('permission:supplier-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:supplier-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:supplier-delete', ['only' => ['destroy']]);
    }

    public function dashboard()
    {
        $user = Auth::user();
      

        return view('suppliers.dashboard',compact(['user']));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        $suppliers = User::with('SupplierDetails')->role('vendor')->filter(request(['search']))
            ->sortable()
            ->paginate($row)
            ->appends(request()->query());
            // dd(
                // $suppliers->all());

        return view('suppliers.index', [
            'suppliers' => $suppliers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $supplierTypes = SupplierTypes::where('status',0)->get();
        $banks = Banks::where('status',0)->get();
        
        return view('suppliers.create',compact(['supplierTypes','banks']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // dd($request->all());
        $rules = [
            'photo' => 'image|file|max:1024',
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:users,email',
            'phone' => 'required|string|max:25|unique:users,phone_number',
            'shopname' => 'required|string|max:50',
            'type' => 'required|string|max:25',
            'account_holder' => 'max:50',
            'account_number' => 'max:25',
            'bank_name' => 'max:25',
            'street_address' => 'required|string|max:100',
            'zip' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'company_name' => 'required|string|max:100',
             'gst_number' => 'required|string|max:100'
           // 'address' => 'required|string|max:100',
        ];

        $validatedData = $request->validate($rules);

        /**
         * Handle upload an image
         */
        if ($file = $request->file('photo')) {
          //  $path = 'public/suppliers/';
          $path = public_path('profile_image/');
            if (!file_exists($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('profile_image'), $fileName);

            /**
             * Store an image to Storage.
             */
            // $path = 'public/suppliers_image/';
            // $file->storeAs('uploads', $fileName, 'public');
            // $file->storeAs($path, $fileName);
            $input['photo']=$fileName;
        }
    
        $input['name']=$request['name'];
        $input['email']=$request['email'];
        $input['phone_number']= $request['phone'];
        $input['password'] = Hash::make('welcome@123');
        $input['username']=$request['name'].rand(100000,999999);
        $input['company_name']= $request['company_name'];
        $input['gst_number']= $request['gst_number'];
        $input['city']= $request['city'];
        $input['zip']= $request['zip'];
        $input['state']= $request['state'];
        $input['street_address']= $request['street_address'];
        $input['address_apt']= $request['address_apt'];
         

        $user=User::create($input);
  
        if($user->id){
            $user->assignRole('vendor');
          
            $input1['user_id']= $user->id;
            $input1['name_shop']= $request['shopname'];
            $input1['supplier_type_id']= $request['type'];
            $input1['account_holder']= $request['account_holder'];
            $input1['account_number']= $request['account_number'];
            $input1['bank_id']= $request['bank_name'];
            // $input1['address']= $request['address'];
            SupplierDetails::create($input1);
        }

        return Redirect::route('suppliers.index')->with('success', 'New supplier has been created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $supplier = User::with('SupplierDetails')->find($id);
        $supplierTypes = SupplierTypes::where('status',0)->get();
        $banks = Banks::where('status',0)->get();


        return view('suppliers.edit',compact('supplier','supplierTypes','banks'));
        // return view('suppliers.edit', [
        //     'supplier' => $supplier
        // ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
      
        $rules = [
            'photo' => 'image|file|max:1024',
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:users,email,'.$id,
            'phone' => 'required|string|max:25|unique:users,phone_number,'.$id,
            'shopname' => 'required|string|max:50',
            'type' => 'required|string|max:25',
            'account_holder' => 'max:50',
            'account_number' => 'max:25',
            'bank_name' => 'max:25',
            // 'address' => 'required|string|max:100',
            'street_address' => 'required|string|max:100',
            'zip' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'company_name' => 'required|string|max:100',
             'gst_number' => 'required|string|max:100'
        ];

        $validatedData = $request->validate($rules);

        /**
         * Handle upload image with Storage.
         */
        // if ($file = $request->file('photo')) {
        //     $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
        //     $path = 'public/suppliers/';

        //     /**
        //      * Delete an image if exists.
        //      */
        //     if($supplier->photo){
        //         Storage::delete($path . $supplier->photo);
        //     }

        //     // Store an image to Storage
        //     $file->storeAs($path, $fileName);
        //     $validatedData['photo'] = $fileName;
        // }

        $input['name']=$request['name'];
        $input['email']=$request['email'];
        $input['phone_number']=$request['phone'];
        $input['company_name']= $request['company_name'];
        $input['gst_number']= $request['gst_number'];
        $input['city']= $request['city'];
        $input['zip']= $request['zip'];
        $input['state']= $request['state'];
        $input['street_address']= $request['street_address'];
        $input['address_apt']= $request['address_apt'];

        $user = User::with('SupplierDetails')->find($id);
            /////////////////////////
            if ($file = $request->file('photo')) {
                //  $path = 'public/suppliers/';
                $path = public_path('profile_image/');
                  if (!file_exists($path)) {
                      File::makeDirectory($path, 0777, true, true);
                  }

                    if($user->photo){
                        File::delete($path . $user->photo);
                    }
                  $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
                  $file->move(public_path('profile_image'), $fileName);
                  $input['photo']=$fileName;
              }
    
            ////////////////////////
        $user->update($input);
    
          $supplierDetailsId=  $user['SupplierDetails']->id;

          $SupplierDetails = SupplierDetails::find($supplierDetailsId);
            $input1['name_shop']= $request['shopname'];
            $input1['supplier_type_id']= $request['type'];
            $input1['account_holder']= $request['account_holder'];
            $input1['account_number']= $request['account_number'];
            $input1['bank_id']= $request['bank_name'];
            // $input1['address']= $request['address'];

          $SupplierDetails->update($input1);
  

        
       // Supplier::where('id', $supplier->id)->update($validatedData);

        return Redirect::route('suppliers.index')->with('success', 'Supplier has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($supplier)
    {
        
        /**
         * Delete photo if exists.
         */
        // if($supplier->photo){
        //     Storage::delete('public/suppliers/' . $supplier->photo);
        // }

        // Supplier::destroy($supplier->id);
        $user= User::find($supplier);
        if($user->photo){
            $path = public_path('profile_image/');
            File::delete($path . $user->photo);
        }
        $user->delete();
        

        return Redirect::route('suppliers.index')->with('success', 'Supplier has been deleted!');
    }
}
