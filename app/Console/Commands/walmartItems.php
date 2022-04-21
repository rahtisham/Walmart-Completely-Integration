<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Walmart\ItemsManager;
use App\Integration\walmart;
use App\Mail\SendMail;
use App\Models\Walmart\Items;
use App\Models\User;
use App\Models\WalmartMarketPlace;
use Illuminate\Support\Facades\Mail;;
use Illuminate\Support\Facades\Config;

class walmartItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:walmartItems';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $itemsCount = ItemsManager::where('status' , 'Pending')
                                    ->where('module' , 'Items')
                                    ->count();
        if($itemsCount > 0){

            for ($i=0; $i<$itemsCount;  $i++)
            {

                $itemManager = ItemsManager::where('status' , 'Pending')
                                            ->where('module' , 'Items')
                                            ->first();

                if($itemManager) {

                    $client_id = $itemManager->marketPlace->client_id;
                    $client_secret = $itemManager->marketPlace->client_secret;
                    $user_session_id = $itemManager->marketPlace->user_id;
                    $mid = $itemManager->marketPlace->id;

                    // \Log::info("Status has been updated" . $manager);

                    $token = Walmart::getToken($client_id, $client_secret);

                    $total_records = Walmart::getItemTotal($client_id, $client_secret, $token);

                    if($total_records > 0){

                        $per_page = Config::get('constants.walmart.per_page');  // 100 Records on per page
                        $no_of_pages = $total_records / $per_page; // Total record divided into per page

                        for ($j = 0; $j < $no_of_pages; $j++) {

                            $offset = $j * $per_page;
                            $url = "https://marketplace.walmartapis.com/v3/items?offset=" . $offset . "&limit=" . $per_page;
                            $requestID = uniqid();
                            $authorization = base64_encode($client_id . ":" . $client_secret);

                            $curl = curl_init();

                            $options = array(
                                CURLOPT_URL => $url,
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'GET',
                                CURLOPT_HTTPHEADER => array(
                                    'WM_SVC.NAME: Walmart Marketplace',
                                    'Authorization: Basic ' . $authorization,
                                    'WM_QOS.CORRELATION_ID: ' . $requestID,
                                    'WM_SEC.ACCESS_TOKEN: ' . $token,
                                    'Accept: application/json',
                                    'Content-Type: application/json',
                                    'Cookie: TS01f4281b=0130aff232afca32ba07d065849e80b32e6ebaf11747c58191b2b4c9d5dd53a042f7d890988bf797d7007bddb746c3b59d5ee859d0'
                                ),

                                CURLOPT_HTTPGET => true,
                            );

                            curl_setopt_array($curl, $options);
                            $response = curl_exec($curl);
                            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                            curl_close($curl);

                            $response = json_decode($response, true);

                            if (array_key_exists('ItemResponse', $response) &&  count($response['ItemResponse']) > 0) {
                                foreach ($response['ItemResponse'] as $items) {
                                    if ($items['publishedStatus'] === "SYSTEM_PROBLEM") {

                                        $unpublishedReasons = '';
                                        $test = [];
                                        if (array_key_exists('unpublishedReasons', $items)) {
                                            $unpublished = $items['unpublishedReasons']['reason'];
                                            $unpublishedReasons = implode(' ', $unpublished);

                                            $alert_type = '';

                                            if(str_contains($unpublishedReasons, 'intellectual')){
                                                $alert_type = Config::get('constants.walmart.ip_claim');
                                            }
                                            if(str_contains($unpublishedReasons, 'compliance')){
                                                $alert_type = Config::get('constants.walmart.regulatory_compliance');
                                            }
                                            if(str_contains($unpublishedReasons, 'partnered')){
                                                $alert_type = Config::get('constants.walmart.brand_partnership_violation');
                                            }
                                            if(str_contains($unpublishedReasons, 'Safety')){
                                                $alert_type = Config::get('constants.walmart.offensive_product');
                                            }

                                            if($alert_type != ''){

                                                $walmartAlerts = [
                                                    'm_id' => $mid,
                                                    'user_id' => $user_session_id,
                                                    'sku' => $items['sku'] ? $items['sku'] : '',
                                                    'product_name' => isset($items['productName']) ? $items['productName'] : '',
                                                    'reason' => $unpublishedReasons,
                                                    'alert_type' => $alert_type,
                                                    'status' => $items['publishedStatus'] ? $items['publishedStatus'] : '',
                                                    'product_url' => $items['wpid'] ? $items['wpid'] : '',
                                                ];

                                                $insert_alerts = Items::insert_item_alert($walmartAlerts);
                                            }

                                        }

                                    }

                                }
                                //End loop
                            }

                        }
                        // End of for loop

                        $walmartData = Items::all()->groupBy('alert_type');
                        // Get data from DB to send email

                        $user = User::where('id' , '=' , $user_session_id)->get();
                        $email = $user[0]['email'];
                        // match condition to unique user

                        if (!empty($email)) {
                            if (isset($walmartData['ip_claim']) && count($walmartData['ip_claim']) > 0) {
                                $detail = [];
                                foreach ($walmartData['ip_claim'] as $ipClaim) {
                                    $detail[] = [
                                        'productID' => $ipClaim['sku'],
                                        'productName' => $ipClaim['product_name'],
                                        'publishedStatus' => $ipClaim['status'],
                                        'reason' => $ipClaim['reason'],
                                        'AlertType' => $ipClaim['alert_type'] ? 'IP Claim Alert' : '',
                                        'productLink' => "https://www.walmart.com/ip/" . $ipClaim['sku'],
                                        'userEmail' => $email
                                    ];
                                }
                                Mail::to($email)->send(new SendMail($detail));
                            }
                            // IP Claim condition

                            if (isset($walmartData['offensive_product']) && count($walmartData['offensive_product']) > 0) {
                                $detail = [];
                                foreach ($walmartData['offensive_product'] as $offensiveProduct) {
                                    $detail[] = [
                                        'productID' => $offensiveProduct['sku'],
                                        'productName' => $offensiveProduct['product_name'],
                                        'publishedStatus' => $offensiveProduct['status'],
                                        'reason' => $offensiveProduct['reason'],
                                        'AlertType' => $offensiveProduct['alert_type'] ? 'Offensive Product Alert' : '',
                                        'productLink' => "https://www.walmart.com/ip/" . $offensiveProduct['sku'],
                                        'userEmail' => $email
                                    ];
                                }

                                Mail::to($email)->send(new SendMail($detail));
                            }
                            // Offensive Product

                            if (isset($walmartData['regulatory_compliance']) && count($walmartData['regulatory_compliance']) > 0) {
                                $detail = [];
                                foreach ($walmartData['regulatory_compliance'] as $regulatoryCompliance) {
                                    $detail[] = [
                                        'productID' => $regulatoryCompliance['sku'],
                                        'productName' => $regulatoryCompliance['product_name'],
                                        'publishedStatus' => $regulatoryCompliance['status'],
                                        'reason' => $regulatoryCompliance['reason'],
                                        'AlertType' => $regulatoryCompliance['alert_type'] ? 'Regulatory Compliance Alert' : '',
                                        'productLink' => "https://www.walmart.com/ip/" . $regulatoryCompliance['sku'],
                                        'userEmail' => $email
                                    ];
                                }

                                Mail::to($email)->send(new SendMail($detail));
                            }
                            // regulatory compliance Product

                            if (isset($walmartData['brand_partnership_violation']) && count($walmartData['brand_partnership_violation']) > 0) {
                                $detail = [];
                                foreach ($walmartData['brand_partnership_violation'] as $brandPartnershipViolation) {
                                    $detail[] = [
                                        'productID' => $brandPartnershipViolation['sku'],
                                        'productName' => $brandPartnershipViolation['product_name'],
                                        'publishedStatus' => $brandPartnershipViolation['status'],
                                        'reason' => $brandPartnershipViolation['reason'],
                                        'AlertType' => $brandPartnershipViolation['alert_type'] ? 'Walmart Brand Partnership Violation' : '',
                                        'productLink' => "https://www.walmart.com/ip/" . $brandPartnershipViolation['sku'],
                                        'userEmail' => $email
                                    ];
                                }

                                Mail::to($email)->send(new SendMail($detail));
                            }
                            // brand Partnership Violation Product

                        }
                        // Email is here

                    }
                    // End of total items condition



                }

                $manager = ItemsManager::updateStatus($itemManager->id, "completed");
                \Log::info("Walmart Items Has Beem Completed " .$itemManager->id);

            }


        }
        // ItemsManager::where('status', 'Completed')
        //             ->where('module', 'Items')
        //             ->update(['status' => 'Pending']);
    }
}
