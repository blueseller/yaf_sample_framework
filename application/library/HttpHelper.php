<?php
class HttpHelper{
    /**
     * http GET请求
     * @param  string url address
     * @param  integer timeout data,ms
     * @param  array http header data
     * @return array request result
     */
    public static function curl($url, $timeout = 500, $header = array()) {
        $new_ch = curl_init();
        curl_setopt($new_ch, CURLOPT_URL, $url);
        curl_setopt($new_ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($new_ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.)");
        curl_setopt($new_ch, CURLOPT_TIMEOUT_MS, $timeout);
        curl_setopt($new_ch, CURLOPT_RETURNTRANSFER, true);
        if(!empty($header) && is_array($header)){
            curl_setopt($new_ch, CURLOPT_HTTPHEADER, $header);
        }
        
        $result = curl_exec($new_ch);
        $http_status = curl_getinfo($new_ch,CURLINFO_HTTP_CODE);
        if(curl_errno($new_ch)) {
            return false;
        }
        curl_close($new_ch);
        if($http_status != 200){
            return false;
        }
        return $result;
    }

    /**
     * http GET请求
     * @param  string url address
     * @param  array post data
     * @param  integer timeout data, ms
     * @param  array http header data
     * @return array request result
     */
	public static function curlPost($url, $curlPost, $timeout = 600, $header = '') {
		$b = self::microtime_float() ;
		$new_ch = curl_init();
		curl_setopt($new_ch,CURLOPT_URL, $url );
		curl_setopt($new_ch,CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($new_ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.)");
		curl_setopt($new_ch,CURLOPT_TIMEOUT_MS,   $timeout);
		if(!empty($header) && is_array($header)){
			curl_setopt($new_ch, CURLOPT_HTTPHEADER, $header);
		}
		curl_setopt($new_ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($new_ch, CURLOPT_POST, true);
		curl_setopt($new_ch, CURLOPT_POSTFIELDS, $curlPost);
		$result = curl_exec($new_ch);
		$http_status = curl_getinfo($new_ch,CURLINFO_HTTP_CODE);
		$e = self::microtime_float() ;
		if(curl_errno($new_ch)){
			return false;
		}
		curl_close($new_ch);
		if($http_status != 200){
			return false;
		}
		return $result;
	}
}