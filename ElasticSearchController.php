<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BrandModel;
use App\Models\CouponModel;
use App\Models\coupondata_all;
use Illuminate\Support\Facades\DB;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
class ElasticSearchController extends Controller
{
    // public function index()
    // {       
    //     $coupondata_all = coupondata_all::where('active',1)->limit(10)->get();
    //     // $dd = coupondata_all::search(13);
    //     return view('elasticsearch',compact('coupondata_all'));
    // }

    public function index(Request $request)
    {
        
        // Elasticsearch client initialization
        $client = ClientBuilder::create()->setHosts(config('elasticsearch.hosts'))->build();
    
        // Get the filters from the AJAX request
        $brandFilters = $request->input('brands');  // Array of selected brands
        $categoryFilters = $request->input('categories');  // Array of selected categories
    
        // Pagination setup
        $page = $request->input('page', 1);
        $perPage = 10;
        $from = ($page - 1) * $perPage;
    
        // Build the search query
        $params = [
            'index' => 'coupondata_all',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [],  // You can add additional search logic if needed
                        'filter' => [],  // The filter clauses will go here
                    ]
                ],
                'size' => $perPage,
                'from' => $from,
                'aggs' => [
                    'brands' => [
                        'terms' => [
                            'field' => 'BrandName.keyword',
                            'size' => 10
                        ]
                    ],
                    'categories' => [
                        'terms' => [
                            'field' => 'CategoryName.keyword',
                            'size' => 10
                        ]
                    ]
                ]
            ]
        ];
    
        // Add brand filters to the query
        if (!empty($brandFilters)) {
            foreach ($brandFilters as $brand) {
                $params['body']['query']['bool']['filter'][] = [
                    'term' => ['BrandName.keyword' => $brand]
                ];
            }
        }
    
        // Add category filters to the query
        if (!empty($categoryFilters)) {
            foreach ($categoryFilters as $category) {
                $params['body']['query']['bool']['filter'][] = [
                    'term' => ['CategoryName.keyword' => $category]
                ];
            }
        }
    
        // Perform the search query
        $response = $client->search($params);
    
        // Extract the coupon data and pagination
        $couponData = array_map(function ($hit) {
            return $hit['_source'];
        }, $response['hits']['hits']);
        $totalResults = $response['hits']['total']['value'];
    
        // Create the paginator for coupon data
        $couponDataPaginator = new LengthAwarePaginator(
            $couponData,
            $totalResults,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('elasticsearch', [
            'couponData' => $couponDataPaginator,
            'brandFacets' => $response['aggregations']['brands']['buckets'] ?? [],
            'categoryFacets' => $response['aggregations']['categories']['buckets'] ?? [],
            'brandFilter' => $brandFilters,
            'categoryFilter' => $categoryFilters,
        ]);
    }
    
    
    public function fetchCoupons(Request $request)
    {
        $brands = $request->input('brands', []);
        $categories = $request->input('categories', []);
        $page = $request->input('page', 1); // Get the current page number from the request
        $perPage = 10; // Number of results per page
    
        $client = ClientBuilder::create()->setHosts(config('elasticsearch.hosts'))->build();
    
        $from = ($page - 1) * $perPage;
    
        // Build the search query
        $params = [
            'index' => 'coupondata_all',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [],  // You can add additional search logic if needed
                        'filter' => [],  // The filter clauses will go here
                    ]
                ],
                'size' => $perPage,
                'from' => $from,
                'aggs' => [
                    'brands' => [
                        'terms' => [
                            'field' => 'BrandName.keyword',
                            'size' => 10
                        ]
                    ],
                    'categories' => [
                        'terms' => [
                            'field' => 'CategoryName.keyword',
                            'size' => 10
                        ]
                    ]
                ]
            ]
        ];
    
        // Apply brand filters if any are provided
        if (!empty($brands)) {
            $params['body']['query']['bool']['filter'][] = [
                'terms' => ['BrandName.keyword' => $brands]
            ];
        }
    
        // Apply category filters if any are provided
        if (!empty($categories)) {
            $params['body']['query']['bool']['filter'][] = [
                'terms' => ['CategoryName.keyword' => $categories]
            ];
        }
    
        // Execute the Elasticsearch search query
        $response = $client->search($params);
    
        // Map hits to coupon data
        $couponData = array_map(function ($hit) {
            return $hit['_source'];
        }, $response['hits']['hits']);
    
        // Total count of all matching results
        $totalCount = $response['hits']['total']['value'];
        return response()->json([
            'couponData' => $couponData,
            'totalCount' => $totalCount,
            'brandFacets' => $response['aggregations']['brands']['buckets'] ?? [],
            'categoryFacets' => $response['aggregations']['categories']['buckets'] ?? [],
            'currentPage' => $page,
            'totalPages' => ceil($totalCount / $perPage)
        ]);
    }
    
}

