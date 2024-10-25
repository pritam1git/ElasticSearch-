<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\coupondata_all;
use App\Providers\ElasticsearchServiceProvider;

class IndexCouponsToElasticsearch extends Command
{
    protected $signature = 'elasticsearch:index-coupons';

    protected $description = 'Index all coupons into Elasticsearch';

    protected $elasticsearchService;

    public function __construct(ElasticsearchServiceProvider $elasticsearchService)
    {
        parent::__construct();
        $this->elasticsearchService = $elasticsearchService;
    }

    public function handle()
    {
        set_time_limit(0); // Allow the script to run indefinitely
    
        $chunkSize = 100;  // Number of records to fetch at once
        $lastId = 1;  // Starting point for the query (ID)
        $totalCouponsProcessed = 0;  // Counter for total coupons processed
    
        // Start the loop from the first ID
        do {
            // Fetch data in chunks, starting from the last processed ID
            $coupons = coupondata_all::where('id', '>=', $lastId)
                ->orderBy('id')
                ->limit($chunkSize)
                ->get();
    
            // If no more coupons, exit the loop
            if ($coupons->isEmpty()) {
                break;
            }
    
            // Prepare an array for batch indexing
            $couponsToIndex = [];
            
            // Process each coupon and prepare for Elasticsearch indexing
            foreach ($coupons as $coupon) {
                // Add coupon to the batch
                $couponsToIndex[] = $coupon;
    
                // Update the last processed ID to the next coupon's ID
                $lastId = $coupon->id + 1;  // Increment to fetch the next set in the next iteration
            }
    
            // Log the number of coupons to be indexed
            // $this->info("Indexing " . count($couponsToIndex) . " coupons starting from ID: $lastId");
    
            // Index all coupons in one batch operation
            try {
                $this->elasticsearchService->indexCouponsBatch($couponsToIndex);
            } catch (\Exception $e) {
                // Log the error
                $this->error("Failed to index coupons: " . $e->getMessage());
                break; // Exit on error
            }
    
            $totalCouponsProcessed += count($couponsToIndex); // Update the total count
    
        } while (count($coupons) === $chunkSize); // Continue while we've fetched a full chunk
    
        // Output the completion message
        $this->info("All coupons have been indexed into Elasticsearch. Total Coupons Processed: {$totalCouponsProcessed}");
    }
    
    
}
