<?php

class AWSObject {
  //CloudFront stream for movies bucket
  static protected $AWS_CF_WEB_STREAM = "https://files.example.com";

  const DEFAULT_URL_EXPIRATION_TIME = 21600;
  
  /**
   * @param  String $video_path
   * @param  String $private_key_filename
   * @param  String $key_pair_id
   * @param  String $policy
   * @return String $stream_name
   */
  function get_cf_custom_policy_stream_name($video_path, $private_key_filename, $key_pair_id, $policy) {
    // the policy contains characters that cannot be part of a URL, so we base64 encode it
    $encoded_policy = $this->url_safe_base64_encode($policy);
    // sign the original policy, not the encoded version
    $signature = $this->cf_rsa_sha1_sign($policy, $private_key_filename);
    // make the signature safe to be included in a url
    $encoded_signature = $this->url_safe_base64_encode($signature);
    // combine the above into a stream name
    $stream_name = $this->create_cf_custom_policy_stream_name($video_path, $encoded_policy, $encoded_signature, $key_pair_id, null);
    // url-encode the query string characters to work around a flash player bug
    return $stream_name;
  }
  
  /**
   * @param  String
   * @param  String $private_key_filename
   * @return String $signature
   */
  function cf_rsa_sha1_sign($policy, $private_key_filename) {

        $signature = "";

        // load the private key
        $fp = fopen($private_key_filename, "r");

        $priv_key = fread($fp, 8192);

        fclose($fp);

        $pkeyid = openssl_get_privatekey($priv_key);

        // compute signature
        openssl_sign($policy, $signature, $pkeyid);

        // free the key from memory
        openssl_free_key($pkeyid);

        return $signature;
    }
  
  /**
   * Helper function to encode string and replace unnecessary character
   * 
   * @param  String $value
   * @return String encodded string
   */
  function url_safe_base64_encode($value) {
    $encoded = base64_encode($value);
    // replace unsafe characters +, = and / with
    // the safe characters -, _ and ~
    return str_replace(
        array('+', '=', '/'),
        array('-', '_', '~'),
        $encoded);
  }
  
  function create_cf_custom_policy_stream_name($stream, $policy, $signature, $key_pair_id, $expires) {
    $result = $stream;
    // if the stream already contains query parameters, attach the new query parameters to the end
    // otherwise, add the query parameters
    $path = "";
    
    $separator = strpos($stream, '?') == FALSE ? '?' : '&';
    // the presence of an expires time means we're using a canned policy
    if($expires) {
      $result .= $path . $separator . "Expires=" . $expires . "&Signature=" . $signature . "&Key-Pair-Id=" . $key_pair_id;
    }
    // not using a canned policy, include the policy itself in the stream name
    else {
      $result .= $path . $separator . "Policy=" . $policy . "&Signature=" . $signature . "&Key-Pair-Id=" . $key_pair_id;
    }
  
    // new lines would break us, so remove them
    return str_replace('\n', '', $result);
  }
  
  function get_cf_custom_policy($video_path, $client_ip, $expires){     
    return '{'.
            '"Statement":['.
                '{'.
                    '"Resource":"'. $video_path . '",'.
                    '"Condition":{'.
                        //'"IpAddress":{"AWS:SourceIp":"' . $client_ip . '/32"},'.
                        '"DateLessThan":{"AWS:EpochTime":' . $expires . '}'.
                    '}'.
                '}'.
            ']' .
          '}';  
  }
  
  public function sign_url($uri, $expires=null) {

    $streamer = self::$AWS_CF_WEB_STREAM;

    $url = $streamer."/$uri";

    $expires = time()+self::DEFAULT_URL_EXPIRATION_TIME;
    
    $ip = NULL;
    
    $customPolicy = $this->get_cf_custom_policy($url, $ip, $expires);
            
    $private_key_file = 'key_files/pk-PUBLICKEYID.pem';

    $public_key_id = 'PUBLICKEYID';
    
    return $this->get_cf_custom_policy_stream_name($url, $private_key_file, $public_key_id, $customPolicy);
  }
}

?>
