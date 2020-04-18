<?php

namespace App\Http\Controllers;

use App\Legacy\Category\Category;
use App\Legacy\Product\ProductType;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;



class ShopifyExportController extends Controller
{
    

    protected $fieldKeys = [
        'ID',
        'Handle',
        'Title',
        'Body HTML',
        'Vendor',
        'Type',
        'Tags',
        'Published',
        'Option1 Name',
        'Option1 Value',
        'Option2 Name',
        'Option2 Value',
        'Option3 Name',
        'Option3 Value',
        'Variant ID',
        'Variant SKU',
        'Variant Weight',
        'Variant Weight Unit',
        'Variant Inventory Qty',
        'Variant Inventory Tracker',
        'Variant Inventory Policy',
        'Variant Fulfillment Service',
        'Variant Price',
        'Variant Compare at Price',
        'Variant Requires Shipping',
        'Variant Taxable',
        'Variant Barcode',
        'Variant Cost',
        'Image Src',
        'Image Position',
        'Image Alt Text'
        
    ];

    protected $images; // array of all product images

    protected $imageHost; //host where k9 hosts it product images = 'https://fido.k9homes.com.au/source/';

    protected $pTypeImages; // array of product type images found

    protected $pTypes; // list of product Types to be exported

    protected $pType; // the product type

    protected $pTypeName; // value for the product TYPE field

    protected $pVariants; // list of products for the current pType

    protected $product; // the current product object;

    protected $exportRows; // array of rows to be exported


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->imageHost = 'https://fido.k9homes.com.au/source/';
        // Get the list of product images
        $this->images = collect(Storage::disk('source')->files());

         //    Active products and images and category info then export
         $this->pTypes = ProductType::where('typeid',">",0)
            ->with(['products' => function($q) {
                $q->where([
                    ['status','=', 'active'],
                    ['shopify_published', '>', 0], 
                ]);
            
             }])
             ->get();
        
    }

    
    
    public function initialExport()
    {


        $n=0; // counts total lines to be exported
        
        foreach($this->pTypes as $this->pType) {


            $this->pTypeImages = $this->getProductSourceImages($this->pType->typeid);

            if(!$this->pTypeName = $this->getTypeName()) {
                continue; // product type not in a category so skip it
            }
            

            $this->pVariants = $this->pType->products->sortBy('product_code');
            
            $vn = 0; // flag to indicate the number of the variant
            foreach ($this->pVariants as $this->product) {

                $this->exportRows[] = $this->makeProductRow($vn++); // $vn = 0 for first line

            }
                

        }  
       
        $this->exportXLS();
    }

    protected function makeProductRow($vn)
    {
        $rowData =[];
        foreach($this->fieldKeys as $key){
            $rowData[] = $this->getValue($key,$vn);
        }
        return $rowData;
    }

    protected function getValue($key, $vn){

        $value ='';
        switch($key) {
            case 'ID':
                $value = $this->product->shopify_id > 0 ? $this->product->shopify_id : "" ;
            break;

            case 'Handle':
                $value = strtolower(preg_replace('/[\s-]+/','_',$this->pType->name));
            break;
                

            case 'Title':
                if($vn == 0){
                    $value = $this->pType->name;
                } 
            break;

            case 'Body HTML':
                if($vn == 0){
                    $value = $this->pType->type_description;
                } 
            break;

            case 'Vendor':
                $value = 'K9Homes';
            break;
            
            case 'Type':
                // $value = $this->pTypeName;// only used for initial export when shopify_type not yet established
                $value = $this->product->shopify_type;
            break;

            case 'Tags':
                $value = $this->product->shopify_tags;
            break;

            case 'Image Src':
                if($vn == 0 && count($this->pTypeImages) > 0 ){// first line of product
                    $value = $this->pTypeImages[0]; // get the first image only
                }
            break;

            case 'Image Alt Text':
                if($vn == 0){
                    $value = $this->pTypeName;
                }
            break;


            case 'Image Position':
                if($vn ==0) {
                    $value = 1;
                }
            break;

            case 'Published':
                $value = $this->product->shopify_published > 0 ? true: false ;
            break;

            case 'Variant ID':
                $value =  !empty($this->product->shopify_variant_id) ? $this->product->shopify_variant_id : "" ;
            break;

            case 'Variant SKU':
                $value = $this->product->product_code;
            break;

            case 'Variant Weight':
                $value =  (int) $this->product->shopify_variant_weight;
            break;

            case 'Variant Weight Unit':
                $value = "g";
            break;

            case 'Variant Inventory Tracker':
                $value = 'Shopify';
            break;

            case 'Option1 Name':
                $value = $this->product->shopify_option1_name;
            break;

            case 'Option1 Value':
                $value = $this->product->shopify_option1_value;
            break;
        
            case 'Option2 Name':
                $value = $this->product->shopify_option2_name;
            break;

            case 'Option2 Value':
                $value = $this->product->shopify_option2_value;
            break;
        
            case 'Option3 Name':
                $value = $this->product->shopify_option3_name;
            break;

            case 'Option3 Value':
                $value =  $this->product->shopify_option3_value;
            break;
        
            case 'Variant Inventory Policy':
                $value =  $this->product->can_backorder === "n" ? "deny" : "continue";
            break;

            case 'Variant Fulfillment Service':
                $value = 'manual';
            break;

            case 'Variant Inventory Qty':
                $value = $this->product->qty_instock > 0 ? $this->product->qty_instock : 0 ;
            break;

            case 'Variant Price':
                $value = number_format($this->product->price *2 / 100 , 2);
            break;

            case 'Variant Compare at Price':
                $value = number_format($this->product->price *2 / 100 , 2);
            break;
            
            case 'Variant Requires Shipping':
                $value = true;
            break;

            case 'Variant Taxable':
                $value = false;
            break;

            case 'Variant Barcode':
                $value = ' '.$this->product->barcode;
            break;

            case 'Variant Cost':
                $value = number_format($this->product->cost / 100 ,2);
            break;

          
                
        }
        return $value;
        

    }

    public function exportXLS()
    {
       
       //dd($data);

       if(!count($this->exportRows)){
           dd ('Oops, looks like we have no rows to export!');
       }

       

        Excel::create('K9ExportForShopify', function($excel) {

            $excel->sheet('Products', function($sheet) {
    
                // Sheet manipulation
                $sheet->row(1,$this->fieldKeys);
             

                foreach($this->exportRows as $row){
                   
                    $sheet->appendRow($row);
                }


            
        
            });
    
           })->download('xlsx');
           exit;
           
    }



    protected function getProductSourceImages($typeID)
    {
        $pImages =[];
        foreach($this->images as $img){
            // echo $img;
            if ($img == $typeID .'.jpg'){
                $pImages[] = $this->imageHost . $img;
            }

            // if ($img == $typeID .'_2.jpg'){
            //     $pImages[] = $imageHost . $img;
            // }

            // if ($img == $typeID .'_3.jpg'){
            //     $pImages[] = $imageHost . $img;
            // }

            // if ($img == $typeID .'_4.jpg'){
            //     $pImages[] = $imageHost . $img;
            // }


        }
        return $pImages;
    }

    protected function getTypeName()
    {
        // Determine the spotify TYPE value
        if(!$category = $this->pType->categories->first()){
            return false; // Not in any category
        }

        // hack to add ALL category names to the SHOPIFY TYPE field
        $categoryName ='';
        foreach($this->pType->categories as $category){
            $typeValue = $category->name;
            if($category->parent_id > 0){
                $parent = Category::find($category->parent_id);
                $typeValue = $parent->name . ': '. $typeValue;
            }
            $categoryName .= $typeValue .' ';

        }
        

        return trim($categoryName);

    }

    
}
