<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\productCodes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Gloudemans\Shoppingcart\Facades\Cart;
use Session;


class PosController extends Controller
{

    public function index()
    {

        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }
        session()->forget('item_details');
        
        $carts = Cart::content();
        foreach ($carts->toArray() as $key => $val) {
           
        
            $vendor_id = $val['options'][$val['id']]['vendor_id'];
           
            $getProductCodeID =$val['id'];
            $productCodeRes = productCodes::where('id', $getProductCodeID)->first();
            // dd($productCodeRes->vendor_id);
        }


        $query = productCodes::filter(request(['search']));
        // if(isset($productCodeRes->vendor_id)){

        //     $query->where('vendor_id',$productCodeRes->vendor_id);
        // }
        

        $products  =$query->sortable()->paginate($row)->appends(request()->query());
        
        // $carts = Cart::content();
        //    dd( $carts->toArray()->id );


        $customers = User::role('customer')->get()->sortBy('name');

        //  dd(  $products->toArray() );

        return view('pos.index', [
            'products' => $products,
            'customers' => $customers,
            'carts' => $carts,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index_old()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        $products = Product::with(['category', 'unit'])
            ->filter(request(['search']))
            ->sortable()
            ->paginate($row)
            ->appends(request()->query());

        $customers = User::role('customer')->get()->sortBy('name');

        $carts = Cart::content();
        // dd( $carts );

        return view('pos.index', [
            'products' => $products,
            'customers' => $customers,
            'carts' => $carts,
        ]);
    }

    /**
     * Handle add product to cart.
     */
    public function addCartItem(Request $request)
    {
        $rules = [
            'id' => 'required',
            'name' => 'required|string',
            // 'price' => 'required|numeric',
            // 'vendor_id' => 'required|numeric',
        ];

        $validatedData = $request->validate($rules);
        // dd( $validatedData);

         $result = Product::with('productCode')->where('product_code', $validatedData['id'])->get();
       
         if(!empty($result)){
            $total=0;
            foreach($result as $key=>$value){
            $temp['vendor_id']=$value['productCode']['vendor_id'];
            $temp['item_price']=$value['selling_price'];
            $temp['item_name']=$value['product_name'];
            $temp['item_id']=$value['id'];
            $temp['item_qty']=$value['stock'];

            $final[$value['id']]=$temp;
            $total_temp= $value['selling_price']*$value['stock'];

                $total +=  $total_temp;
            }
         
            


         }else{
            return Redirect::route('pos.index')->with('error', 'There is no iteam available in this product code please check!');


         }
      


        $data = Cart::add([
            'id' => $validatedData['id'],
            'name' => $validatedData['name'],
            'qty' => 1,
            'price' => $total,
            "options" => $final,
        ]);

        return Redirect::route('pos.index')->with('success', 'Product has been added to cart!');



    }
    public function addCartItem_old(Request $request)
    {
        $rules = [
            'id' => 'required',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'vendor_id' => 'required|numeric',
        ];

        $validatedData = $request->validate($rules);
        // dd(Session::get('item_details'));

        $a = Session::get('item_details');
        $narray =  array('vendor_id' => $validatedData['vendor_id']);
        array_push($a, $narray);
        // dd();
        $data = Cart::add([
            'id' => $validatedData['id'],
            'name' => $validatedData['name'],
            // 'associatedModel' => array('vendor_id' => $validatedData['vendor_id']),
            // 'delivery' => $validatedData['vendor_id'],
            'qty' => 1,
            'price' => $validatedData['price'],
            "options" => $a,
        ]);


        // Cart::associate( $data->rowId, $validatedData['vendor_id']);
        //     $item = Cart::get( $data->rowId);
        //    $item->options->merge(['vendor_id' => $validatedData['vendor_id']]);

        return Redirect::route('pos.index')->with('success', 'Product has been added to cart!');
        // return Redirect::back()->with('success', 'Product has been added to cart!');
    }

    /**
     * Handle update product in cart.
     */
    public function updateCartItem(Request $request, $rowId)
    {
        $rules = [
            'qty' => 'required|numeric',
        ];

        $validatedData = $request->validate($rules);

        Cart::update($rowId, $validatedData['qty']);

        return Redirect::back()->with('success', 'Product has been updated from cart!');
    }

    /**
     * Handle delete product from cart.
     */
    public function deleteCartItem(String $rowId)
    {
        Cart::remove($rowId);

        return Redirect::back()->with('success', 'Product has been deleted from cart!');
    }

    /**
     * Handle create an invoice.
     */
    public function createInvoice(Request $request)
    {
        $rules = [
            'customer_id' => 'required|string',
            'vehicle_number'=> 'required|string'
        ];
     
      

        $validatedData = $request->validate($rules);
     
        $customer = User::with('CustomerDetails')->where('id', $validatedData['customer_id'])->first();
        $carts = Cart::content();

        if(empty($carts->toArray())){
            return Redirect::back()->with('success', 'Please add at least one product in your cart!');

        }

        foreach ($carts->toArray() as $key => $val) {
            // dd( $val['options']['item_details']);
            $vendor_id = $val['options'][$val['id']]['vendor_id'];

   
            $getProductCodeID = explode(" ", $val['id']);
            $productCodeData = productCodes::where('id', $getProductCodeID[0])->first();
        
            $carts[$key]->product_code =  $productCodeData['product_code'];
           
        }
        $supplier = User::with('SupplierDetails')->where('id', $vendor_id)->first();
        $vehicle_number=$validatedData['vehicle_number'];
        return view('pos.create', [
            'customer' => $customer,
            'carts' => $carts,
            'supplier' => $supplier,
            'vehicle_number'=> $vehicle_number
        ]);
    }


    public function edit($id)
    {
        // dd(Session::get('item_details'));

        $product = productCodes::with('product')->find($id);
        // $carts = Cart::content();
        return view('pos.edit', compact('product'));
    }

    public function manageQty(Request $request)
    {



        $item_details =  Session::get('item_details');
        $item_details['item_details'][$request['item_id']]['item_qty'] = $request['current_qty'];

        $item_details['item_details'][$request['item_id']]['item_id'] = $request['item_id'];

        Session::put('item_details',   $item_details);
    }



    public function send()
    {
        $directory_path = storage_path('pdf/orders/');
        if (!File::exists($directory_path)) {

            File::makeDirectory($directory_path, $mode = 0755, true, true);
        }

        $order = User::find(3);
        $oid = "";
        $invoice_date = "";


        $filename = 'Invoice_' . config('app.name') . '_Order_No # ' . $oid . ' Date_' . $invoice_date . '.pdf';
        $pdf = PDF::loadView('emails.visitor_email', array('order' => $order))->save($directory_path . $filename);;
        return $pdf->download($filename);
    }

    public function downloadFile(Request $request)
    {
       
        $challan_name= $request['challan_name'];
        $myFile = storage_path("pdf/".$challan_name);
      
        return response()->download($myFile);
    }
}
