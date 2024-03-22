<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetails;
use App\Models\User; 
use App\Models\productCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Gloudemans\Shoppingcart\Facades\Cart;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use PDF;
use File;

class OrderController extends Controller
{
    /**
     * Display a pending orders.
     */
    public function pendingOrders()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        $orders = Order::where('order_status', 'pending')
            ->sortable()
            ->paginate($row)
            ->appends(request()->query());

        return view('orders.pending-orders', [
            'orders' => $orders
        ]);
    }

    /**
     * Display a pending orders.
     */
    public function completeOrders()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        $orders = Order::where('order_status', 'complete')
            ->sortable()
            ->paginate($row)
            ->appends(request()->query());

        return view('orders.complete-orders', [
            'orders' => $orders
        ]);
    }

    public function dueOrders()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        $orders = Order::where('due', '>', '0')
            ->sortable()
            ->paginate($row)
            ->appends(request()->query());

        return view('orders.due-orders', [
            'orders' => $orders
        ]);
    }

    /**
     * Display an order details.
     */
    public function dueOrderDetails(String $order_id)
    {
        $order = Order::where('id', $order_id)->first();
        $orderDetails = OrderDetails::with('product')
            ->where('order_id', $order_id)
            ->orderBy('id')
            ->get();

        return view('orders.details-due-order', [
            'order' => $order,
            'orderDetails' => $orderDetails,
        ]);
    }

    /**
     * Display an order details.
     */
    public function orderDetails(String $order_id)
    {
        $order = Order::where('id', $order_id)->first();
        $orderDetails = OrderDetails::with('product')
            ->where('order_id', $order_id)
            ->orderBy('id')
            ->get();
            // dd($orderDetails->toArray();
        return view('orders.details-order', [
            'order' => $order,
            'orderDetails' => $orderDetails,
        ]);
    }

    /**
     * Handle create new order
     */
    public function createOrder(Request $request)
    {
        $rules = [
            'customer_id' => 'required|numeric',
            'payment_type' => 'required|string',
            'pay' => 'required|numeric',
            'vehicle_number'=>'required|string',
        ];

        $invoice_no = IdGenerator::generate([
            'table' => 'orders',
            'field' => 'invoice_no',
            'length' => 10,
            'prefix' => 'INV-'
        ]);

        // $getProductCodeID = explode(" ", $val['id']);
        // $getProductCodeID[0];
        $validatedData = $request->validate($rules);

        $validatedData['order_date'] = Carbon::now()->format('Y-m-d');
        $validatedData['vehicle_number'] = $validatedData['vehicle_number'];
        $validatedData['order_status'] = 'pending';
        $validatedData['total_products'] = Cart::count();
        $validatedData['sub_total'] = Cart::subtotal();
        $validatedData['vat'] = Cart::tax();
        $validatedData['invoice_no'] = $invoice_no;
        $validatedData['total'] = Cart::total();
        $validatedData['due'] = ((int)Cart::total() - (int)$validatedData['pay']);
        $validatedData['created_at'] = Carbon::now();

        $order_id = Order::insertGetId($validatedData);

        // Create Order Details
        $contents = Cart::content();
        $oDetails = array();

        foreach ($contents->toArray() as $content) {
         
         
            $oDetails['order_id'] = $order_id;
            $getProductCodeID = explode(" ", $content['id']);
            $oDetails['product_code_id'] = $getProductCodeID[0];
          
           foreach ($content['options'] as $content1) {
          
       
            $oDetails['product_id'] = $content1['item_id'];
            $oDetails['quantity'] = $content1['item_qty'];
            $oDetails['unitcost'] = $content1['item_price'];
            $oDetails['total'] = $content1['item_qty']*$content1['item_price'];//$content->subtotal;
            $oDetails['created_at'] = Carbon::now();
            OrderDetails::insert($oDetails);
         }

           
        }


         /////////////pdf genrate/////////////////////////

         $directory_path = storage_path('pdf/');
         if(!File::exists($directory_path)) {
        
           File::makeDirectory($directory_path, $mode = 0777, true, true);    
        }
         $oid= $order_id;
         ##################

         $customer = User::with('CustomerDetails')->where('id', $validatedData['customer_id'])->first();
         $carts = Cart::content();
         foreach ($carts->toArray() as $key => $val) {
           
             $vendor_id = $val['options'][$val['id']]['vendor_id'];;
             $getProductCodeID = $val['id'];
             $productCodeData = productCodes::where('id', $getProductCodeID[0])->first();
         
             $carts[$key]->product_code =  $productCodeData['product_code'];
            
         }
        
         $supplier = User::with('SupplierDetails')->where('id', $vendor_id)->first();

        
         #####################
        
         $filename ='Invoice_'.config('app.name').'_Order_No_'.$oid.'.pdf';
         $pdf = PDF::loadView('emails.challan_email',array(
            'customer' => $customer,
            'carts' => $carts,
            'supplier' => $supplier,
            'oid'=>$oid,
            'vehicle_number'=> $validatedData['vehicle_number']
         ))->save($directory_path.$filename);

         
     
        //  $data["email"] = "aatmaninfotech@gmail.com";
         $data["title"] = "DELIVERY CHALLAN ";
         $data["body"] = "Please find the attachment of DELIVERY CHALLAN";
         $data["email"] =  $customer->email;
         $data["supplier_email"] =  $supplier->email;
         $data["vehicle_number"] = $validatedData['vehicle_number'];

  
         $files = [
            storage_path('pdf/'.$filename)
         ];
   
   
         Mail::send('emails.challan_email_template', $data, function($message)use($data, $files) {
             $message->to($data["email"], $data["supplier_email"])
                     ->subject($data["title"]);
  
             foreach ($files as $file){
                 $message->attach($file);
             }
             
         });
         /////////////pdf genrate /////////////////////////

         $validatedData['challan_name'] = $filename;
         Order::where('id', $order_id)->update($validatedData);

        // Delete Cart Sopping History
        Cart::destroy();

        return Redirect::route('order.pendingOrders')->with('success', 'Order has been created!');
    }

    /**
     * Handle update a status order
     */
    public function updateOrder(Request $request)
    {
        $order_id = $request->id;

        // Reduce the stock
        $products = OrderDetails::where('order_id', $order_id)->get();

        foreach ($products as $product) {
            Product::where('id', $product->product_id)
                    ->update(['stock' => DB::raw('stock-'.$product->quantity)]);
        }

        Order::findOrFail($order_id)->update(['order_status' => 'complete']);

        return Redirect::route('order.completeOrders')->with('success', 'Order has been completed!');
    }

    /**
     * Handle update a due pay order
     */
    public function updateDueOrder(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'pay' => 'required|numeric'
        ];

        $validatedData = $request->validate($rules);
        $order = Order::findOrFail($validatedData['id']);

        $mainPay = $order->pay;
        $mainDue = $order->due;

        $paidDue = $mainDue - $validatedData['pay'];
        $paidPay = $mainPay + $validatedData['pay'];

        Order::findOrFail($validatedData['id'])->update([
            'due' => $paidDue,
            'pay' => $paidPay
        ]);

        return Redirect::route('order.dueOrders')->with('success', 'Due amount has been updated!');
    }

    /**
     * Handle to print an invoice.
     */
    public function downloadInvoice(Int $order_id)
    {
        $order = Order::with('customer')->where('id', $order_id)->first();
        $orderDetails = OrderDetails::with('product')
                        ->where('order_id', $order_id)
                        ->orderBy('id', 'DESC')
                        ->get();

        return view('orders.print-invoice', [
            'order' => $order,
            'orderDetails' => $orderDetails,
        ]);
    }
}
