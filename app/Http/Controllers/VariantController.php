<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Variant;

class VariantController extends Controller
{   

	public function __construct()
    {
        $this->middleware('auth_check');
    }

    public function addVariant($id)
	{   
	    $product = Product::findOrFail($id);

	    $variants = Variant::where('product_id', $product->id)->get();

	    // Group by variant_name, and restructure each group
	    // $structured = $variants->groupBy('variant_name')->map(function ($group, $variantName) {
	    //     return [
	    //         'variant_name' => $variantName,
	    //         'variant_values' => $group->pluck('variant_value')->filter()->unique()->values()->toArray()
	    //     ];
	    // })->values()->toArray(); // convert collection to array



	    $structured = $variants->groupBy('variant_name')->map(function ($group, $variantName) {
    	    return [
    	    	'variant_id' => $group->first()->id,
    	        'variant_name' => $variantName,
    	        'variant_values' => $group->map(function ($item) {
    	            return [
    	                'id' => $item->id,
    	                'value' => $item->variant_value
    	            ];
    	        })->filter(function ($item) {
    	            return !empty($item['value']);
    	        })->unique('value')->values()->toArray()
    	    ];
    	})->values()->toArray();

	    //return $structured;

	    return view('products.add_variant', [
	        'product' => $product,
	        'variantVals' => $structured
	    ]);
	}

    public function storeVariants(Request $request)
	{   
		try
		{
			$productId = $request->input('product_id');
			$variants = $request->input('variants');

			$dataToInsert = [];

			foreach ($variants as $name => $values) {
			    foreach ($values as $value) {
			        $dataToInsert[] = [
			            'product_id'     => $productId,
			            'variant_name'   => $name,
			            'variant_value'  => $value,
			            'created_at'     => now(),
			            'updated_at'     => now(), 
			        ];
			    }
			}

			DB::table('variants')->insertOrIgnore($dataToInsert);

			return response()->json(['status'=>true,'message' => 'Variants added/updated successfully']);

		}catch(Exception $e){
			return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
		}
	}




}
