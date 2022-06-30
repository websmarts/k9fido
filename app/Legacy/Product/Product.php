<?php

namespace App\Legacy\Product;

use Carbon\Carbon;
use App\Legacy\Order\Item;
use App\Events\ProductSaved;
use App\Legacy\Traits\UsersQueryFilter;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use UsersQueryFilter;
    

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'k9homes';

    /**
     * The table primary key column.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    public $timestamps = false;

    protected $fillable = [
        'description',
        'size',
        'price',
        'product_code',
        'typeid',
        'qty_break',
        'qty_discount',
        'qty_instock',
        'qty_ordered',
        'special',
        'clearance',
        'can_backorder',
        'status',
        'modified',
        'cost',
        'last_costed_date',
        'supplier',
        'width',
        'height',
        'length',
        'shipping_volume',
        'shipping_weight',
        'actual_weight',
        'shipping_container',
        'display_order',
        'barcode',
        'color_name',
        'color_background_color',
        'color_background_image',
        'notify_when_instock',
        'source',
        'new_product',
        'core_product',
        'low_stock_level',
        'rrp',
        'product_note',
        'shopify_id',
        'shopify_published',
        'shopify_handle',
        'shopify_option1_name',
        'shopify_option1_value',
        'shopify_option2_name',
        'shopify_option2_value',
        'shopify_option3_name',
        'shopify_option3_value',
        'shopify_image_src'
    ];

    static function boot()
    {
        Product::saved(function ($product) {
            

            $product->clientPrices->each(function($clientPrice,$key) use($product) {
                if($product->status !== 'inactive' && ($product->price != $clientPrice->std_price)){
                    // now add the std_price and the calculated client_price and then save
                    $clientPrice->std_price = $product->price;
                    $clientPrice->client_price = $product->price * (1 - $clientPrice->discount);
                    
                    $clientPrice->save();
                    
                    //dd($clientPrice);
                }
            });
            
        });

        
    }

    public function hasField($name=null)
    {
        return in_array($name,$this->fillable);
    }

    public function recentSales($period = -1)
    {
        
        

        $sales = $this->sales($period)->get();

        // sum up the qty * price

        $totalSales = $sales->reduce(function ($totalSales, $item) {

            return $totalSales + ($item->qty * $item->price);
        });

        $totalUnits = $sales->reduce(function ($totalUnits, $item) {

            return $totalUnits + $item->qty;
        });


        return (object) ['sales'=>$totalSales,'units'=>$totalUnits];
    }

    /**
     * Scope a query to only include filtered results.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApplyUserFilter($query)
    {
       return $this->applyFilter($query, $this->table);

    }

    public function bom()
    {
        return $this->hasMany(Bom::class, 'parent_product_code', 'product_code');
    }


    public function sales($period = false)
    {
        
        $query =  $this->hasMany(Item::class,'product_code','product_code');

        if($period > -1) {
            $query->where('updated_at', '>' , Carbon::now()->subDays($period));
        }

        return $query;
    }

    public function clientprices()
    {

        return $this->hasMany(ClientPrice::class, 'product_code', 'product_code');
        //->select(['product_code', 'client_id']);

    }

}
