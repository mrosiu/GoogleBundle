<?php

namespace AntiMattr\GoogleBundle\Service;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use \RuntimeException;

class CurlManager
{
    const HTTP_METHOD_GET = 'GET';


    const INTERNAL_SERVER_EXCEPTION_CODE    = 500;
    const NOT_FOUND_EXCEPTION_CODE          = 404;

    protected $curlHandler;

    public function getResponseHttpCode()
    {
        return curl_getinfo($this->curlHandler, CURLINFO_HTTP_CODE);
    }

    public function get($url, array $params = array())
    {
        return $this->call(
            sprintf(
                '%s?%s',
                $url,
                http_build_query(
                    array_merge(
                        $params,
                        array(
                            'sensor' => 'true'
                        )
                    )
                )
            )
        );
    }

    protected  function call($url, $method = self::HTTP_METHOD_GET)
    {
        $this->curlHandler = curl_init($url);
        curl_setopt($this->curlHandler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curlHandler, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($this->curlHandler, CURLOPT_CUSTOMREQUEST, $method);

        $response = curl_exec($this->curlHandler);

        switch ($this->getResponseHttpCode()) {
            case self::NOT_FOUND_EXCEPTION_CODE:
                throw new NotFoundHttpException();
            case self::INTERNAL_SERVER_EXCEPTION_CODE:
                throw new RuntimeException();
            default:
                break;
        }

        return $response;
    }
}
