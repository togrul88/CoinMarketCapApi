<?php
/*
 (c) Toghrul Aliyev <togrul88@gmail.com>
 This source file is subject to the MIT license that is bundled
 with this source code in the file LICENSE.
*/

class CoinMarketCap {
    
    private $BASE_URL = "https://api.coinmarketcap.com/v2/";
    
    private $address;
    
    private $userAgent = 'ToTAli Request Sender';
    private $connectTimeout = 30;
    private $timeout = 180;
    
    
    /*
     * Endpoint: /listings/
     * Method: GET
     * Description: This endpoint displays all active cryptocurrency listings in one call. Use the "id" field on the Ticker endpoint to query more information on a specific cryptocurrency.
     * Example: https://api.coinmarketcap.com/v2/listings/
     */
    public function getListings() {
        $response = $this->sendCommand($this->buildUrl("listings/"));
        return $response;
    }
    
    /*
     * Endpoint: /ticker/
     * Method: GET
     * Description: This endpoint displays cryptocurrency ticker data in order of rank. The maximum number of results per call is 100. Pagination is possible by using the start and limit parameters.
     *
     * (int) start - return results from rank [start] and above (default is 1)
     * (int) limit - return a maximum of [limit] results (default is 100; max is 100)
     * (string) convert - return pricing info in terms of another currency. 
     */
    public function getTicker($start = 1, $limit = 100, $convert = "BTC") {
        $params = array("start" => $start, "limit" => $limit, "convert" => $convert);
        $response = $this->sendCommand($this->buildUrl("ticker/", $params));
        return $response;
    }
    
    /*
     * Endpoint: /ticker/{id}/
     * Method: GET
     * Description: This endpoint displays ticker data for a specific cryptocurrency. Use the "id" field from the Listings endpoint in the URL.
     *
     * (string) convert - return pricing info in terms of another currency. 
     */
    public function getSpecificTicker($id, $convert = "BTC") {
        $params = array("convert" => $convert);
        $response = $this->sendCommand($this->buildUrl("ticker/".$id."/", $params));
        return $response;
    }
    
    /*
     * Endpoint: /global/
     * Method: GET
     * Description: This endpoint displays the global data found at the top of coinmarketcap.com.
     *
     * (string) convert - return pricing info in terms of another currency. 
     */
    public function getGlobal($convert = "BTC") {
        $params = array("convert" => $convert);
        $response = $this->sendCommand($this->buildUrl("global/", $params));
        return $response;
        
    }
 
    /*
     * Build url. 
     * $method - Possible values: 
     *       listings
     *       ticker
     *       ticker (Specific Currency)
     *       global
     */
    private function buildUrl($method, $params = array()) {
        $url = $this->BASE_URL.$method;
        
        if ($params != null && sizeof($params) > 0) {
            $i = 0;
            foreach ($params as $key => $value) {
                $url .= ($i++ == 0) ? "?{$key}={$value}" : "&{$key}={$value}";
            }
        }
        
        return $url;
    }
    
    /*
     * Send HTTPS command 
     */
    private function sendCommand($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_USERAGENT, "ToTAli Request Sender");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 180);

        $response = curl_exec($ch);

        if(curl_error($ch)) {
	        echo 'Request Error:' . curl_error($ch);
	        curl_close($ch);
	        return null;
        } else {
	        curl_close($ch);
	        return $response;
        }
    }
}

?>
