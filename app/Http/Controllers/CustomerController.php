<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Models\Banks;
use App\Models\CustomerDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;

use Hash;
use File;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{

    function __construct()
    {
        //  $this->middleware('permission:cusomer-list|cusomer-create|cusomer-edit|cusomer-delete', ['only' => ['index','show']]);
        //  $this->middleware('permission:cusomer-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:cusomer-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:cusomer-delete', ['only' => ['destroy']]);
    }

    public function dashboard()
    {
        $user = Auth::user();
      

        return view('customers.dashboard',compact(['user']));
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

        $customers = User::role('customer')->filter(request(['search']))
            ->sortable()
            ->paginate($row)
            ->appends(request()->query());

        return view('customers.index', [
            'customers' => $customers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $banks = Banks::where('status',0)->get();
        return view('customers.create',compact(['banks']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'photo' => 'image|file|max:1024',
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:customers,email',
            'phone' => 'required|string|max:25|unique:customers,phone',
            'account_holder' => 'max:50',
            'account_number' => 'max:25',
            'bank_name' => 'required|string|max:100',
            'street_address' => 'required|string|max:100',
            'zip' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'company_name' => 'required|string|max:100',
             'gst_number' => 'required|string|max:100'

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
              $user->assignRole('customer');
            
              $input1['user_id']= $user->id;
            //   $input1['name_shop']= $request['shopname'];
            //   $input1['supplier_type_id']= $request['type'];
              $input1['account_holder']= $request['account_holder'];
              $input1['account_number']= $request['account_number'];
              $input1['bank_id']= $request['bank_name'];
             
              CustomerDetails::create($input1);
          }

        return Redirect::route('customers.index')->with('success', 'New customer has been created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $customer = User::with('CustomerDetails')->find($id);
     
        $banks = Banks::where('status',0)->get();


        return view('customers.edit',compact('customer','banks'));
        // return view('customers.edit', [
        //     'customer' => $customer
        // ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $rules = [
            'photo' => 'image|file|max:1024',
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:users,email,'.$id,
            'phone' => 'required|string|max:25|unique:users,phone_number,'.$id,
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


        $user = User::with('CustomerDetails')->find($id);

        /**
         * Handle upload image with Storage.
         */
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

          $user->update($input);
          $customerDetailsId=  $user['CustomerDetails']->id;

          $CustomerDetails = CustomerDetails::find($customerDetailsId);
            $input1['account_holder']= $request['account_holder'];
            $input1['account_number']= $request['account_number'];
            $input1['bank_id']= $request['bank_name'];
            // $input1['address']= $request['address'];

          $CustomerDetails->update($input1);

        return Redirect::route('customers.index')->with('success', 'Customer has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $customer)
    {
        /**
         * Delete photo if exists.
         */
        $user= User::find($customer);
        if($user->photo){
            $path = public_path('suppliers_image/');
            File::delete($path . $user->photo);
        }
        $user->delete();
        

        return Redirect::route('customers.index')->with('success', 'Customer has been deleted!');
    }
}
