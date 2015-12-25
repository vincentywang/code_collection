<?php 

function setCookieLogin($user_credentials) {

    $ch = curl_init();

    $curlConfig = array(
      CURLOPT_URL            => "http://example.dev/Requests/login",
      CURLOPT_POST           => true,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HEADER         => true,
      CURLOPT_POSTFIELDS     => array(
        'username' => $user_credentials['username'],
        'password' => $user_credentials['password']
      )
    );

    curl_setopt_array($ch, $curlConfig);

    $result = curl_exec($ch);

    $curlHeaderSize=curl_getinfo($ch,CURLINFO_HEADER_SIZE);
    $ResponseHeader = mb_substr($result, 0, $curlHeaderSize);
    $ResponseData = mb_substr($result, $curlHeaderSize);
    

    preg_match_all('|Set-Cookie: (.*);|U', $ResponseHeader, $content);

    $ResponseCookie = implode(';', $content[1]);
    
    curl_close($ch);

    foreach ($content[1] as $index => $item) {

        $cookie_info = explode('=', $item);

        if ($cookie_info[1] !== 'deleted') {

            switch ($cookie_info[0]) {
              case 'Zend':
                  setcookie(
                      'CAKEPHP',
                      $cookie_info[1],
                      time()+3600,
                      '/',
                      '.test.com',
                      true,
                      true
                  );
                  break;
              case 'AWSELB':
                  setcookie(
                      'AWSELB',
                      $cookie_info[1],
                      0,
                      '/',
                      '.test.com'
                  );
                  break;
              default:
                  break;    
            }
        }
    }
  }

 ?>