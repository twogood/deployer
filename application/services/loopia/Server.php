<?php

namespace services\loopia;

class Server
{
  // From http://wezfurlong.org/blog/2006/nov/http-post-from-php-without-curl/
  private static function do_post_request($url, $data, $optional_headers = null)
  {
    $params = array('http' => array(
      'method' => 'POST',
      'content' => $data
    ));
    if ($optional_headers !== null) {
      $params['http']['header'] = $optional_headers;
    }
    $ctx = stream_context_create($params);
    $fp = @fopen($url, 'rb', false, $ctx);
    if (!$fp) {
      throw new Exception("Problem with $url, $php_errormsg");
    }
    $response = @stream_get_contents($fp);
    if ($response === false) {
      throw new Exception("Problem reading data from $url, $php_errormsg");
    }
    return $response;
  }

  public function xmlrpc($requestXml)
  {
    $responseXml = self::do_post_request('https://api.loopia.se/RPCSERV', $requestXml);
    return $responseXml;
  }


}
