<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentInfoRequest;
use App\Models\PaymentInfo;
use App\Models\WebsitePurchase;
use Exception;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use App\Http\Requests\SettingRequest;
use Auth;
use DataTables;
use Hash;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }

    public function appSettings()
    {
    	$setting = setting();
    	return view('settings.app_settings',compact('setting'));
    }

    public function settingApp(Request $request)
    {
        try
        {
            // Fetch current user's setting (not always ID 1)
            $data = Setting::where('user_id', user()->id)->first();

            $defaults = [
                'courier_api_key'       => $data ? $data->courier_api_key : null,
                'courier_secret'        => $data ? $data->courier_secret : null,
                'merchant_id'           => $data ? $data->merchant_id : null,
                'order_note'            => $data ? $data->order_note : null,
                'facebook_pixel_id'     => $data ? $data->facebook_pixel_id : null,
                'pathao_client_id'      => $data ? $data->pathao_client_id : null,
                'pathao_client_secret'  => $data ? $data->pathao_client_secret : null,
                'pathao_access_token'   => $data ? $data->pathao_access_token : null,
                'delivery_charge'       => $data ? $data->delivery_charge : null,
                'facebook_url'          => $data ? $data->facebook_url : null,
                'twitter_url'           => $data ? $data->twitter_url : null,
                'instagram_url'         => $data ? $data->instagram_url : null,
                'youtube_url'           => $data ? $data->youtube_url : null,
                'terms_conditions'      => $data ? $data->terms_conditions : null,
                'refund_policy'         => $data ? $data->refund_policy : null,
                'inside_delivery_charge' => $data ? $data->inside_delivery_charge : null,
                'outside_delivery_charge' => $data ? $data->outside_delivery_charge : null,
            ];

            Setting::updateOrCreate(
                ['user_id' => user()->id],
                [
                    'user_id'              => user()->id,
                    'courier_api_key'      => $request->courier_api_key ?? $defaults['courier_api_key'],
                    'courier_secret'       => $request->courier_secret ?? $defaults['courier_secret'],
                    'merchant_id'          => $request->merchant_id ?? $defaults['merchant_id'],
                    'order_note'           => $request->order_note ?? $defaults['order_note'],
                    'facebook_pixel_id'    => $request->facebook_pixel_id ?? $defaults['facebook_pixel_id'],
                    'pathao_client_id'     => $request->pathao_client_id ?? $defaults['pathao_client_id'],
                    'pathao_client_secret' => $request->pathao_client_secret ?? $defaults['pathao_client_secret'],
                    'pathao_access_token'  => $request->pathao_access_token ?? $defaults['pathao_access_token'],
                    'delivery_charge'      => $request->delivery_charge ?? $defaults['delivery_charge'],
                    'facebook_url' => $request->facebook_url ?? $defaults['facebook_url'],
                    'twitter_url' => $request->twitter_url ?? $defaults['twitter_url'],
                    'instagram_url' => $request->instagram_url ?? $defaults['instagram_url'],
                    'youtube_url' => $request->youtube_url ?? $defaults['youtube_url'],
                    'terms_conditions' => $request->terms_conditions ?? $defaults['terms_conditions'],
                    'refund_policy' => $request->refund_policy ?? $defaults['refund_policy'],
                    'inside_delivery_charge' => $request->inside_delivery_charge ?? $defaults['inside_delivery_charge'],
                    'outside_delivery_charge' => $request->outside_delivery_charge ?? $defaults['outside_delivery_charge'],
                ]
            );

            $notification = [
                'messege'    => 'Successfully updated',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);

        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'code'    => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function passwordChange()
    {
        return view('settings.change_password');
    }

    public function changePassword(Request $request)
    {
        try
        {
            $user = User::findorfail(Auth::user()->id);



            if (!Hash::check($request->current_password, $user->password)) {


                $notification=array(
                             'messege'=>'The current password is not matched',
                             'alert-type'=>'error'
                            );

                return redirect()->back()->with($notification);
            }

            $user->password = Hash::make($request->new_password);
            $user->update();


           $notification=array(
                             'messege'=>'Successfully your has been changed',
                             'alert-type'=>'success'
                            );

            return redirect()->back()->with($notification);

        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function metaPixelSettings()
    {
        $setting = setting();
        return view('settings.meta_pixel',compact('setting'));
    }

    public function setDelveryCharge()
    {
        $setting = setting();
        return view('settings.delivery_charge', compact('setting'));
    }

    public function socialMediaSettings()
    {
        $setting = setting();
        return view('settings.social_media',compact('setting'));
    }

    public function termsCondition()
    {
        $setting = setting();
        return view('settings.terms_conditions',compact('setting'));
    }

    public function refundPolicy()
    {
        $setting = setting();
        return view('settings.refund_policy',compact('setting'));
    }
    public function paymentInfo(Request $request)
    {
        if($request->ajax()){

            $products = PaymentInfo::where('user_id', Auth::user()->id)->select('*')->latest();

            return Datatables::of($products)
                ->addIndexColumn()

                ->addColumn('payment_method', function($row){
                    return $row->payment_method;
                })

                ->addColumn('account_number', function($row){
                    return $row->account_number;
                })

                ->addColumn('payment_type', function($row){
                    return $row->payment_type;
                })

                ->addColumn('action', function($row){

                    $btn = "";
                    $btn .= '&nbsp;';

                    $btn .= ' <a href="'.route('payment-info.edit',$row->id).'" class="btn btn-primary btn-sm action-button edit-payment-info" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                    $btn .= '&nbsp;';


                    $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-payment-info action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';



                    return $btn;
                })
                ->rawColumns(['action','payment_method','account_number','payment_type'])
                ->make(true);
        }
        return view('paymentInfo.index');
    }
    public function createPaymentInfo()
    {
        return view('paymentInfo.create');
    }
    public function StorePaymentInfo(PaymentInfoRequest $request)
    {
        // Check uniqueness of payment_method + account_number combo
        $exists = PaymentInfo::where('payment_method', $request->payment_method)
            ->where('account_number', $request->account_number)
            ->exists();

        if ($exists) {
            $notification=array(
                'messege' => "This account number($request->account_number) already exists for the selected payment method($request->payment_method).",
                'alert-type' => 'error',
            );

            return redirect()->back()->with($notification);
        }

        $bkashLogoUrl = "payment_icon/bikash.jpg";
        $rocketLogoUrl = "payment_icon/rocket.png";
        $nogodLogoUrl = "payment_icon/nagad.png";

        // Set payment icon based on payment method
        $logoUrl = null;
        if ($request->payment_method === 'bKash') {
            $logoUrl = $bkashLogoUrl;
        } elseif ($request->payment_method === 'rocket') {
            $logoUrl = $rocketLogoUrl;
        } else {
            $logoUrl = $nogodLogoUrl;
        }

        try
        {
            $paymentInfo = new PaymentInfo();
            $paymentInfo->user_id = Auth::user()->id;
            $paymentInfo->payment_method = $request->payment_method;
            $paymentInfo->account_number = $request->account_number;
            $paymentInfo->payment_type = $request->payment_type;
            $paymentInfo->instructions = $request->instructions;
            $paymentInfo->logo = $logoUrl;
            $paymentInfo->save();
            $notification=array(
                'messege' => 'Successfully a payment info has been added',
                'alert-type' => 'success',
            );

            return redirect()->route('payment-info.index')->with($notification);
        }catch(Exception $e){
            // Log the error
            Log::error('Error in storing payment info: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification=array(
                'messege'=>'Something went wrong!!!',
                'alert-type'=>'error'
            );
            return redirect()->back()->with($notification);
        }
    }
    public function EditPaymentInfo($id)
    {
        $paymentInfo = PaymentInfo::findorfail($id);
        return view('paymentInfo.edit', compact('paymentInfo'));
    }
    public function UpdatePaymentInfo(PaymentInfoRequest $request, PaymentInfo $paymentInfo)
    {
        // Check uniqueness of payment_method + account_number combo
        $exists = PaymentInfo::where('payment_method', $request->payment_method)
            ->where('account_number', $request->account_number)
            ->where('id', '!=',$paymentInfo->id)
            ->exists();

        if ($exists) {
            $notification=array(
                'messege' => "This account number($request->account_number) already exists for the selected payment method($request->payment_method).",
                'alert-type' => 'error',
            );

            return redirect()->back()->with($notification);
        }

        $bkashLogoUrl = "payment_icon/bikash.jpg";
        $rocketLogoUrl = "payment_icon/rocket.png";
        $nogodLogoUrl = "payment_icon/nagad.png";

        // Set payment icon based on payment method
        $logoUrl = null;
        if ($request->payment_method === 'bKash') {
            $logoUrl = $bkashLogoUrl;
        } elseif ($request->payment_method === 'rocket') {
            $logoUrl = $rocketLogoUrl;
        } else {
            $logoUrl = $nogodLogoUrl;
        }

        try
        {
            $paymentInfo->user_id = Auth::user()->id;
            $paymentInfo->payment_method = $request->payment_method;
            $paymentInfo->account_number = $request->account_number;
            $paymentInfo->payment_type = $request->payment_type;
            $paymentInfo->instructions = $request->instructions;
            $paymentInfo->logo = $logoUrl;
            $paymentInfo->save();
            $notification=array(
                'messege'=>'Successfully the payment info has been updated',
                'alert-type'=>'success',
            );

            return redirect()->route('payment-info.index')->with($notification);

        }catch(Exception $e){
            // Log the error
            Log::error('Error in storing payment info: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification=array(
                'messege'=>'Something went wrong!!!',
                'alert-type'=>'error'
            );
            return redirect()->back()->with($notification);
        }
    }
    public function purchaseHistory(Request $request)
    {
        try
        {
            if($request->ajax()){

                $websitePurchases = WebsitePurchase::with('user', 'domain')
                    ->select('*')
                    ->latest();

                return Datatables::of($websitePurchases)
                    ->addIndexColumn()

                    ->addColumn('domain', function($row){
                        return $row->domain->domain;
                    })


                    ->addColumn('name', function($row){
                        return $row->user->name;
                    })

                    ->addColumn('phone', function($row){
                        return $row->user->phone;
                    })

                    ->addColumn('status', function($row){
                        return '<label class="switch"><input class="' . ($row->status === 'pending' ? 'active-user' : 'decline-user') . '" id="status-user-update"  type="checkbox" ' . ($row->status === 'approved' ? 'checked' : '') . ' data-id="'.$row->id.'"><span class="slider round"></span></label>';
                    })

                    ->addColumn('action', function($row){
                        $url = route('view-purchase-history', ['id' => $row->id]);

                        $btn = '<a href="' . $url . '" class="btn btn-primary btn-sm action-button"><i class="fa fa-eye"></i></a>';

                        return $btn;
                    })

                    ->rawColumns(['name','phone','domain','status', 'action'])
                    ->make(true);
            }
            return view('purchaseHistory.users');
        } catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }

    }

    public function userStatusUpdate(Request $request)
    {
        try
        {
            $webSitePurchase = WebsitePurchase::findorfail($request->id);
            $webSitePurchase->status = $request->status;
            $webSitePurchase->update();
            return response()->json(['status'=>true, 'message'=>"Successfully the purchase status has been updated"]);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function viewPurchaseHistory($id)
    {
        $payment = WebsitePurchase::with('user', 'domain', 'package')
            ->where('id',$id)
            ->firstOrFail();

        return view('purchaseHistory.view_purchase_history', compact('payment'));
    }
    
    public function deletePaymentInfo(PaymentInfo $paymentInfo)
    {
        try
        {
            $paymentInfo->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the payment has been deleted']);
        }catch(Exception $e){
            // Log the error
            Log::error('Error in deleting payment info: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification=array(
                'messege'=>'Something went wrong!!!',
                'alert-type'=>'error'
            );
            return redirect()->back()->with($notification);
        }
    }
}
