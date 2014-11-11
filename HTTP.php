<?php

class HTTP
{
    /**
     * get a url
     * 
     * @param string $url
     * @param string $header
     * @param bool $decodeJson (default: false)
     * @param bool $decodeGz (default: false)
     * @return false|string|array
     */
    static public function get($url, $header="", $decodeJson=false, $decodeGz=false)
    {
        $opts = array('http' =>
            array(
                'method'  => 'GET',
                'header'=> $header,
            )
        );

        $context  = stream_context_create($opts);
        $content = file_get_contents($url, false, $context);
        
        if($content) {
            if($decodeJson) {
                $json = json_decode($content, true);

                if($json) {
                    return $json;
                } else {
                    Error::add('HTTP get error during json decode from "'.$url.'"');
                    return false;
                }
            } else if($decodeGz) {
                $gz = gzdecode($content);

                if($gz) {
                    return $gz;
                } else {
                    Error::add('HTTP get error during gz decode from "'.$url.'"');
                    return false;
                }
            } else {
                return $content;
            }
        } else {
            Error::add('HTTP get no data from "'.$url.'"');
            return false;
        }
    }
    
    /**
     * post a url
     * 
     * @param string $url
     * @param array $dataPost
     * @param string $header
     * @param bool $decodeJson (default: false)
     * @param bool $methodXmlrpc (default: false)
     * @param bool $transformResponse (default: false)
     * @return boolean|string|array
     */
    static public function post($url, $dataPost, $header="", $decodeJson=false, $methodXmlrpc=false, $transformResponse=false)
    {
        if($methodXmlrpc) {
            $httpPostQuery = xmlrpc_encode_request($methodXmlrpc, $dataPost);
            $header = "Content-Type: text/xml \r\n".$header;
        } else {
            $httpPostQuery = http_build_query($dataPost);
        }
        
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'content' => $httpPostQuery,
                'header' => $header
            )
        );
        
        $context  = stream_context_create($opts);
        $content = file_get_contents($url, false, $context);
        
        if($content) {
            if($transformResponse) {
                /* get data without header */
                $match = array();
                preg_match("/^(.+)HTTP\/1\.1 200 OK.+$/im", $content, $match);
                if(!empty($match[1])) {
                    $content = $match[1];
                }
            }
                    
            if($methodXmlrpc) {
                $response = xmlrpc_decode($content);
                if (xmlrpc_is_fault($response)) {
                    Error::add('HTTP post error during xmlrpc decode from "'.$url.'"');
                    return false;
                } else {
                    return $response;
                }
            } else if($decodeJson) {
                $json = json_decode($content, true);

                if($json) {
                    return $json;
                } else {
                    Error::add('HTTP post error during json decode from "'.$url.'"');
                    return false;
                }
            } else {
                return $content;
            }
        } else {
            Error::add('HTTP post no data from "'.$url.'"');
            return false;
        }
    }
}
