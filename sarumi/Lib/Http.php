<?php


/**
 * 简单模拟的浏览器客户端
 */
class Http
{
    protected $request_url ;
    protected $method = '';
    protected $request_headers = array();
    protected $request_body = '';
    protected $request_timeout = 5184000;
    protected $request_connect_timeout = 120;
    protected $request_maxredirs = 5;
    protected $request_cookie = null;
    protected $request_useragent = 'St Http';
    protected $response_headers;
    protected $response_code;
    protected $response_body;
    protected $response_info;
    protected $response;
    protected $curl_handle;


    /**
     * GET HTTP Method
     */
    const HTTP_GET = 'GET';

    /**
     * POST HTTP Method
     */
    const HTTP_POST = 'POST';

    public function __construct($url = null)
    {
        $this->request_url     = $url;
        $this->method  = self::HTTP_GET;
        $this->request_headers = array();
        $this->request_body    = '';
    }

    /**
     * 设置请求URL
     *
     * @param string $url
     * @return \St\Http
     */
    public function setUrl($url)
    {
        $this->request_url = (string)$url;

        return $this;
    }

    /**
     * 设置数据发送模式
     */
    public function setMethod($method)
    {
        $this->method = strtoupper($method);

        return $this;
    }

    /**
     * 添加请求头部内容
     * @param $key
     * @param $value
     * @return $this
     */
    public function addRequestHeader($key, $value)
    {
        $this->request_headers[$key] = $value;

        return $this;
    }


    /**
     * 设置发送内容
     */
    public function setBody($body)
    {
        $this->request_body = $body;

        return $this;
    }

    /**
     * 设置超时
     *
     * @param smallint $time
     * @return \St\Http
     */
    public function setTimeout($time)
    {
        $this->request_timeout = (int)$time;

        return $this;
    }

    /**
     * 设置COOKIE
     *
     * @param string $cookie
     * @return \St\Http
     */
    public function setCookie($cookie)
    {
        $this->request_cookie = (string)$cookie;

        return $this;
    }

    public function setUseragent($ua = null)
    {
        $this->request_useragent = trim($ua);
    }

    /**
     * 获取请求头
     */
    public function getRequestHeader()
    {
        return $this->request_headers;
    }


    /**
     * 获取返回头
     * @param null $header
     * @return mixed
     */
    public function getResponseHeader($header = null)
    {
        if ($header) {
            return $this->response_headers[strtolower($header)];
        }

        return $this->response_headers;
    }

    /**
     * 获取返回数据
     *
     * @return string The response body.
     */
    public function getResponseBody()
    {
        return $this->response_body;
    }

    public function getResponseCode()
    {
        return $this->response_code;
    }

    /**
     * 构造请求信息
     * @return resource
     */
    public function prep()
    {
        $curlHandle = curl_init();
        // Set default options.
        curl_setopt($curlHandle, CURLOPT_URL, $this->request_url);
        curl_setopt($curlHandle, CURLOPT_FILETIME, true);
        curl_setopt($curlHandle, CURLOPT_FRESH_CONNECT, false);
        //curl_setopt($curlHandle, CURLOPT_CLOSEPOLICY, CURLCLOSEPOLICY_LEAST_RECENTLY_USED);
        curl_setopt($curlHandle, CURLOPT_MAXREDIRS, $this->request_maxredirs);
        curl_setopt($curlHandle, CURLOPT_HEADER, true);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, $this->request_timeout);
        curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, $this->request_connect_timeout);
        curl_setopt($curlHandle, CURLOPT_NOSIGNAL, true);
        curl_setopt($curlHandle, CURLOPT_REFERER, $this->request_url);

        if ($this->request_useragent) {
            curl_setopt($curlHandle, CURLOPT_USERAGENT, $this->request_useragent);
        }

        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, TRUE);

        if (!empty($this->cookie)) {
            curl_setopt($curlHandle, CURLOPT_COOKIE, $this->request_cookie);
        }

        // Process custom headers
        if (isset($this->request_headers) && count($this->request_headers)) {
            $temp_headers = array();
            foreach ($this->request_headers as $k => $v) {
                $temp_headers[] = $k . ': ' . $v;
            }
            curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $temp_headers);
        }

        switch ($this->method) {
            case self::HTTP_POST:
                curl_setopt($curlHandle, CURLOPT_POST, 1);

                if (!empty($this->request_body)) {
                    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $this->request_body);
                }
                break;
            default: // Assumed GET
                curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, $this->method);
                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $this->request_body);
                break;
        }

        // Handle open_basedir & safe mode
        if (!ini_get('safe_mode') && !ini_get('open_basedir')) {
            curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, true);
        }

        return $curlHandle;
    }


    /**
     * 发起一个CURL请求,模拟HTTP,并获取返回值
     *
     * @return json|null|string|array|Object
     */
    public function send()
    {
        $curlHandle       = $this->prep();
        $this->response = curl_exec($curlHandle);
        if ($this->response === false)
        {
            throw new \Exception('cURL resource: ' . (string) $curlHandle . '; cURL error: ' . curl_error($curlHandle) . ' (' . curl_errno($curlHandle) . ')');
        }

        $this->processResponse($curlHandle, $this->response);

        curl_close($curlHandle);
        $this->reset();

        return $this->response;
    }

    /**
     * 处理返回信息
     * @param null $curl_handle
     * @param null $response
     * @return bool
     */
    public function processResponse($curlHandle = null, $response = null)
    {
        // Accept a custom one if it's passed.
        if ($curlHandle && $response)
        {
            $this->curl_handle = $curlHandle;
            $this->response = $response;
        }

        // As long as this came back as a valid resource...
        if (is_resource($this->curl_handle))
        {
            // Determine what's what.
            $header_size = curl_getinfo($this->curl_handle, CURLINFO_HEADER_SIZE);
            $this->response_headers = substr($this->response, 0, $header_size);
            $this->response_body = substr($this->response, $header_size);
            $this->response_code = curl_getinfo($this->curl_handle, CURLINFO_HTTP_CODE);
            $this->response_info = curl_getinfo($this->curl_handle);

            // Parse out the headers
            $this->response_headers = explode("\r\n\r\n", trim($this->response_headers));
            $this->response_headers = array_pop($this->response_headers);
            $this->response_headers = explode("\r\n", $this->response_headers);
            array_shift($this->response_headers);

            // Loop through and split up the headers.
            $header_assoc = array();
            foreach ($this->response_headers as $header)
            {
                $kv = explode(': ', $header);
                $header_assoc[strtolower($kv[0])] = isset($kv[1])?$kv[1]:'';
            }

            // Reset the headers to the appropriate property.
            $this->response_headers = $header_assoc;
            $this->response_headers['_info'] = $this->response_info;
            $this->response_headers['_info']['method'] = $this->method;

           return true;
        }

        // Return false
        return false;
    }





    /**
     * 重置
     */
    protected function reset()
    {
        $this->_url        = null;
        $this->_post       = false;
        $this->_postFields = null;
        $this->_timeout    = 1;
    }

}
