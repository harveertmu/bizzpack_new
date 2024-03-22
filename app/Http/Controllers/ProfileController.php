<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Mail;
use PDF;
use File;


class ProfileController extends Controller
{


    public function send()
                {
                    $directory_path = storage_path('pdf/orders/');
                    if(!File::exists($directory_path)) {
                   
                      File::makeDirectory($directory_path, $mode = 0755, true, true);    
                   }
                

                    $order=User::find(3);
                    $oid= "";
                    $invoice_date ="";
                    
                   
                    $filename ='Invoice_'.config('app.name').'_Order_No # '.$oid.' Date_'.$invoice_date.'.pdf';
                    $pdf = PDF::loadView('emails.challan_email',array('order'=>$order))->save($directory_path.$filename);;
                 die('success');
                   
                }
    public function sends(){
        $this->generateHtmlToPDF();

        // Mail::send('emails.visitor_email', ['title' => 'DELIVERY CHALLAN', 'content' => 'hj'], function ($message) {

        //     $message->to('arunchetu002@gmail.com')->subject('DELIVERY CHALLAN');
        // });

        dd('fjhgh');
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user()->fill($request->validated());

        $rules = [
            'name' => 'required|max:50',
            'photo' => 'image|file|max:1024',
            'email' => 'required|email|max:50|unique:users,email,'.$user->id,
            'username' => 'required|min:4|max:25|alpha_dash:ascii|unique:users,username,'.$user->id,
            'street_address' => 'required|string|max:100',
            'zip' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'company_name' => 'required|string|max:100',
             'gst_number' => 'required|string|max:100'
        ];

        $validatedData = $request->validate($rules);

        if ($validatedData['email'] != $user->email) {
            $validatedData['email_verified_at'] = null;
        }

        /**
         * Handle upload image
         */
        // if ($file = $request->file('photo')) {
        //     $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
        //     $path = 'public/profile/';

        //     if($user->photo){
        //         Storage::delete($path . $user->photo);
        //     }
        //     $file->storeAs($path, $fileName);
        //     $validatedData['photo'] = $fileName;
        // }
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
              $validatedData['photo']=$fileName;
          }

        $validatedData['company_name']= $request['company_name'];
        $validatedData['gst_number']= $request['gst_number'];
        $validatedData['city']= $request['city'];
        $validatedData['zip']= $request['zip'];
        $validatedData['state']= $request['state'];
        $validatedData['street_address']= $request['street_address'];
        $validatedData['address_apt']= $request['address_apt'];

        User::where('id', $user->id)->update($validatedData);

        return Redirect::route('profile.edit')->with('success', 'Profile has been updated!');
    }

    public function settings(Request $request): View
    {
        return view('profile.settings', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
