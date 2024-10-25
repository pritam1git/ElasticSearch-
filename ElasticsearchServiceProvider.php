<?php

namespace App\Providers;

use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;
use App\Models\BrandModel;
use App\Models\coupondata_all;
use App\Models\Category;

class ElasticsearchServiceProvider extends ServiceProvider
{

    protected $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()->setHosts(config('elasticsearch.hosts'))->build();
    }

    // Index a single brand
    public function indexBrand(BrandModel $brand)
    {
        $params = [
            'index' => 'brands', 
            'id'    => $brand->brand_id, 
            'body'  => [
                'brand_name'   => $brand->brand_name,
                'brand_logo'   => $brand->brand_logo,
                'brand_website'=> $brand->brand_website,
                'brand_desc'   => $brand->brand_desc,
                'slug'         => $brand->slug,
                'active'       => (bool) $brand->active,
                'created_at'   => $brand->created_at,
                'updated_at'   => $brand->updated_at,
            ],
        ];
    
        return $this->client->index($params);
    }
    
    public function indexCouponsBatch(array $coupons)
    {
        $params = ['body' => []];
    
        foreach ($coupons as $coupon) {
            $params['body'][] = [
                'index' => [
                    '_index' => 'coupondata_all',
                    '_id' => $coupon->id,         
                ]
            ];
            $params['body'][] = [
                'coupon_code' => $coupon->coupon_code,
                'coupon_desc' => strip_tags($coupon->coupon_desc),  
                'brand_id' => $coupon->brand_id,
                'BrandName' => $coupon->BrandName,                  
                'CategoryName' => $coupon->CategoryName,            
                'brand_image' => $coupon->brand_image,              
                'brand_slug' => $coupon->brand_slug,                
                'cat_slug' => $coupon->cat_slug,                    
                'category_id' => $coupon->category_id,              
                'yes_count' => (int) $coupon->yes_count,         
                'affiliate_link' => $coupon->affiliate_link,     
                'active' => (bool) $coupon->active,              
                'created_at' => $coupon->created_at ? $coupon->created_at->toIso8601String() : null,  
                'updated_at' => $coupon->updated_at ? $coupon->updated_at->toIso8601String() : null,  
            ];
        }
        if (empty($params['body'])) {
            return;
        }
        return $this->client->bulk($params);
    }
    
    public function deleteCoupon(coupondata_all $coupon)
    {
        $params = [
            'index' => 'coupondata_all',
            'id'    => $coupon->id,
        ];

        return $this->client->delete($params);
    }
}
