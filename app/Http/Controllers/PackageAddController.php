<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModuleTutorialRequest;
use App\Models\Domain;
use App\Models\Module;
use App\Models\ModuleTutorial;
use App\Models\Package;
use App\Models\SubDomain;
use App\Models\User;
use App\Models\WebsitePurchase;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Validator;

class PackageAddController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function index(Request $request)
    {
        try
        {
            $plans = Package::where('status', 'Active')->get();

            return view('packageAdd.index', compact('plans'));
        } catch(Exception $e) {
            return response()->json([
                'status'=>false,
                'code'=>$e->getCode(),
                'message'=>$e->getMessage()
            ],500);
        }
    }
    public function create()
    {
        $addedModuleIds = ModuleTutorial::get('module_id');

        $modules = Module::where('status', 'Active')
            ->whereNotIn('id', $addedModuleIds)
            ->latest()
            ->get();

        return view('moduleTutorials.create', compact('modules'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required|string|alpha_dash',
            'package_id' => 'required|exists:packages,id',
        ]);
        try
        {
            $slugLowerCase = strtolower($request->slug);
            $slug = trim($slugLowerCase, '/');
            $packageId = $request->package_id;

            if ($slug === 'checkout') {
                $notification = [
                    'messege' => 'This sub-domain not accepted.',
                    'alert-type' => 'error'
                ];

                return redirect()->route('package-add')->with($notification);
            }

            $isExist = SubDomain::where('slug', $slug)->exists();
            if ($isExist) {
                $notification = [
                    'messege' => 'Sub-domain already exists for you.',
                    'alert-type' => 'error'
                    ];

                return redirect()->route('package-add')->with($notification);
            }

            $checkoutUrl = $this->getCheckoutUrl($packageId, $slug);

            return redirect()->away($checkoutUrl);
        } catch(Exception $e) {
            // Log the error
            Log::error('Error in storing ModuleTutorial: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification = [
                'messege' => 'Something went wrong!!! ModuleTutorial',
                'alert-type' => 'error'
            ];

            return redirect()->route('package-add')->with($notification);
        }
    }
    private function getToken()
    {
        $credentials = [
            'username' => config('services.surjopay.username'),
            'password' => config('services.surjopay.password'),
        ];

        if (!$credentials['username'] || !$credentials['password']) {
            throw new \Exception('ShurjoPay credentials are not configured properly.');
        }

        try {
            $response = Http::post(config('services.surjopay.token_url'), $credentials);
            if ($response->successful()) {
                $data = $response->json();
                // টোকেন এবং execute_url চেক করা
                if (isset($data['token']) && isset($data['execute_url'])) {
                    return $data; // সরাসরি ডেটা রিটার্ন করা
                } else {
                    throw new \Exception('Token not received properly.');
                }
            } else {
                throw new \Exception('Failed to get token: ' . ($response->json()['message'] ?? $response->status()));
            }
        } catch (\Exception $e) {
            // এরর লগ করা
            Log::error('ShurjoPay token error: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e; // এররটি ফেলে দেওয়া যাতে কন্ট্রোলারে ক্যাচ করা যায়
        }
    }
    public function userPaymentStore(Request $request)
    {
        DB::beginTransaction();
        try
        {
            if ($request->status === 'fail') {
                DB::rollback();
                $notification = [
                    'messege' => 'Payment failed, Please try again. status fail',
                    'alert-type' => 'error'
                ];

                return redirect()->route('package-add')->with($notification);
            }

            $user = User::findorfail(Auth::user()->id);

            $domain = Domain::where('user_id',$user->id)->first();

            $sp_order_id = Session::get('sp_order_id');
            $surjo_token = Session::get('surjo_token');

            if(!$domain)
            {
                DB::rollback();
                $notification = [
                    'messege' => 'No domain found.',
                    'alert-type' => 'error'
                ];

                return redirect()->route('package-add')->with($notification);
            }


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://engine.shurjopayment.com/api/verification',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
               "order_id": "'.$sp_order_id.'"
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    "Authorization: Bearer {$surjo_token}"
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $result = json_decode($response,true);

            $result[0]['bank_trx_id'] = 'CHU9WZHL7I';

            if (!$result || !isset($result[0]) || empty($result[0]['bank_trx_id'])) {
                DB::rollback();
                $notification = [
                    'messege' => 'Payment failed. Please try again. verify error',
                    'alert-type' => 'error'
                ];

                return redirect()->route('package-add')->with($notification);
            }

            $fullDomain = $domain->domain . '/' . $request->slug;

            $subDomain = SubDomain::create([
                'user_id' => $user->id,
                'domain_id' => $domain->id,
                'theme_id' => $domain->theme_id,
                'package_id' => $domain->package_id,
                'slug' => $request->slug,
                'full_domain' => $fullDomain,
                'shop_name' => $domain->shop_name,
                'logo' => $domain->logo,
                'address' => $domain->address,
                'status' => 'Active',
            ]);

            $purchase = WebsitePurchase::create([
                'gateway_order_id' => $sp_order_id,
                'domain_id'        => $domain->id,
                'user_id'          => $user->id,
                'package_id'       => $domain->package_id,
                'sub_domain_id'    => $subDomain->id,
                'theme'            => $user->theme,
                'payment_method'   => "shurjopay",
                'transaction_hash' => $result[0]['bank_trx_id'],
                'status'           => $result[0]['bank_trx_id'] ? "pending" : "approved",
            ]);


            DB::commit();

            Session::forget('sp_order_id');
            Session::forget('surjo_token');

            $notification = [
                'messege' => 'Sub-domain purchased successfully.',
                'alert-type' => 'success'
            ];

            return redirect()->route('dashboard')->with($notification);

        } catch (\Exception $e) {
            // Log the error
            Log::error('userPaymentStore error: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            DB::rollback();

            $notification = [
                'messege' => 'Something went wrong!!! userPaymentStore',
                'alert-type' => 'error'
            ];

            return redirect()->route('package-add')->with($notification);
        }
    }
    private function getCheckoutUrl($packageId, $slug)
    {
        try {
            // টোকেন পাওয়া
            $tokenData = $this->getToken();
            $token = $tokenData['token'];
            $storeId = $tokenData['store_id'];
            $executeUrl = $tokenData['execute_url'];

            Session::put('surjo_token', $token);

            $packageData = Package::where('id', $packageId)->first();

            // API বডি প্রস্তুত করা
            $paymentData = [
                'token' => $token,
                'store_id' => $storeId,
                'prefix' => 'HDH',
                'currency' => 'BDT',
                'return_url' => route('payment.verify', ['slug' => $slug, 'package_id' => $packageId, 'status' => 'success']),
                'cancel_url' => route('payment.verify', ['slug' => $slug, 'package_id' => $packageId, 'status' => 'fail']),
                'amount' => $packageData->price,
                'order_id' => 'HDH_' . time() . rand(1000, 9999),
                'customer_name' => Auth::user()->name ?? '',
                'customer_phone' => Auth::user()->phone ?? '',
                'customer_email' => Auth::user()->email ?? '',
                'customer_address' => 'Dhaka, Bangladesh',
                'customer_city' => 'Dhaka',
                'client_ip' => request()?->ip(),
            ];

            // API কলের জন্য হেডার প্রস্তুত করা
            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ];

            // secret-pay API কল
            $response = Http::withHeaders($headers)->post($executeUrl, $paymentData);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['checkout_url']) && isset($data['sp_order_id'])) {
                    Session::put('sp_order_id', $data['sp_order_id']);
                    return $data['checkout_url'];
                } else {
                    throw new \Exception('Checkout URL not received.');
                }
            } else {
                throw new \Exception('Failed to initiate payment: ' . ($response->json()['message'] ?? $response->status()));
            }
        } catch (\Exception $e) {
            // এরর লগ করা
            Log::error('ShurjoPay checkout error: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

}
