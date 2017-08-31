<?php
namespace CityPay\Lib\Rpc;

use CityPay\Encoding\FormUrl\FormUrlCodec;
use CityPay\Encoding\Json\JsonCodec;
use CityPay\Encoding\Xml\XmlCodec;

define('MIME_TYPE_FORM_URL_ENCODED', 'application/x-www-form-urlencoded; charset=UTF-8');
define('MIME_TYPE_JSON_ENCODED', 'application/json; charset=UTF-8');
define('MIME_TYPE_XML_ENCODED', 'application/xml; charset=UTF-8');

/**
 * 
 */
class HttpsRpc
{
    /**
     *
     */
    const
        CONTENT_TYPE_FORM_URL   = 0x00,
        CONTENT_TYPE_JSON       = 0x01,
        CONTENT_TYPE_XML        = 0x02;

    /**
     * @param $payload
     */
    private static function formUrlDecode(
        $payload
    ) {
        return FormUrlCodec::decode($payload);
    }

    /**
     * Encodes an object as a Form URL encoded string.
     *
     * @param $object
     *      the object to be represented as a Form URL encoded string.
     *
     * @return string
     *      a string containing the Form URL representation of the object.
     */
    private static function formUrlEncode(
        $object
    ) {
        return FormUrlCodec::encode($object);
    }

    /**
     * @param $payload
     */
    private static function jsonDecode(
        $payload
    ) {
        return JsonCodec::decode($payload);
    }

    /**
     * Encodes an object as a JSON string.
     *
     * @param $object
     *      the object to be represented as JSON encoded string.
     *
     * @return string
     *      a string containing the JSON representation of the object.
     */
    private static function jsonEncode($object) {
        return JsonCodec::encode($object);
    }

    /**
     * @param $payload
     */
    private static function xmlDecode($payload) {
        return XmlCodec::decode($payload);
    }

    /**
     * Encodes an associative array as an XML document.
     *
     * @param $object
     *      the object to be encoded as an XML document.
     *
     * @return string
     *      a string containing the XML document.
     */
    private static function xmlEncode(
        $object
    ) {
        return XmlCodec::encode($object);
    }

    /**
     * @param $contentType
     * @param $payload
     * @return string
     */
    static function sanitizePayload(
        $contentType,
        $payload
    ) {
        switch ($contentType) {
            case HttpsRpc::CONTENT_TYPE_FORM_URL:
                return '<sanitized-form-url-content>';

            case HttpsRpc::CONTENT_TYPE_JSON:
                return '<sanitized-json-content>';

            case HttpsRpc::CONTENT_TYPE_XML:
                return '<sanitized-xml-content>';

            default:
                return '<unrecognized-content-type>';
        }
    }

    /**
     * Invokes the http rpc process
     * @param $url
     * @param $contentType
     * @param $payload
     * @param $acceptMimeType
     * @param $responsePayload
     * @return int
     *      integer representing the HTTP response code.
     */
    public static function invoke(
        $url,
        $contentType,
        $payload,
        $responseContentType,
        &$responsePayload
    ) {
        switch ($contentType) {
            case self::CONTENT_TYPE_FORM_URL:
                $contentType = MIME_TYPE_FORM_URL_ENCODED;
                $encodedPayload = self::formUrlEncode($payload);
                break;

            case self::CONTENT_TYPE_JSON:
                $contentType = MIME_TYPE_JSON_ENCODED;
                $encodedPayload = self::jsonEncode($payload);
                break;

            case self::CONTENT_TYPE_XML:
                $contentType = MIME_TYPE_XML_ENCODED;
                $encodedPayload = self::xmlEncode($payload);
                break;

            default:
                //
                //  TODO: Throw an exception.
                //
        }

        switch ($responseContentType) {
            case self::CONTENT_TYPE_FORM_URL:
                $acceptMimeType = MIME_TYPE_FORM_URL_ENCODED;
                break;

            case self::CONTENT_TYPE_JSON:
                $acceptMimeType = MIME_TYPE_JSON_ENCODED;
                break;

            case self::CONTENT_TYPE_XML:
                $acceptMimeType = MIME_TYPE_XML_ENCODED;
                break;

            default:
                //
                //  TODO: Throw an exception.
                //
        }

        $curl_stderr = fopen('php://temp', 'w+');
        $curl_opts = array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $encodedPayload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Accept: '.$acceptMimeType,
                'Content-Type: '.$contentType,
                'Content-Length: '.strlen($encodedPayload)
            ),
            CURLOPT_VERBOSE => true,
            CURLOPT_SSL_VERIFYHOST => 1,
            CURLOPT_SSL_VERIFYPEER => 1,
            CURLOPT_STDERR => $curl_stderr
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $curl_opts);
        $httpsResponse = curl_exec($ch);
        if (empty($httpsResponse))
        {
            error_log(__METHOD__.': Post request to ['.']');
            error_log(
                __METHOD__
                    .': Payload (sanitized) ['
                    .self::sanitizePayload($contentType, $payload)
                    .']'
            );
            error_log(__METHOD__.': Empty response');

            //
            //  The following code was introduced to assist in the diagnosis
            //  of SSL problems encountered on Windows platforms that are otherwise
            //  invisible: typically caused by problems encountered by the relevant
            //  PHP / cURL implementation being unable to access the certificate
            //  authority bundle.
            //
            rewind($curl_stderr);
            $req_stderr = stream_get_contents($curl_stderr, 4096);
            fclose($curl_stderr);
            $req_errno = curl_errno($ch);
            $req_error = curl_error($ch);
            curl_close($ch);
            error_log(__METHOD__.': Request curl error - '.$req_stderr);
            error_log(__METHOD__.': Request error no - '.$req_errno);
            error_log(__METHOD__.': Request error - '.$req_error);

            //
            //  TODO: Throw an exception
            //
            throw new \Exception("error".$req_stderr);
        }

        $httpsResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        switch ($responseContentType) {

            case self::CONTENT_TYPE_FORM_URL:
                $responsePayload = self::formUrlDecode($httpsResponse);
                break;

            case self::CONTENT_TYPE_JSON:
                $responsePayload = self::jsonDecode($httpsResponse);
                break;

            case self::CONTENT_TYPE_XML:
                $responsePayload = self::xmlDecode($httpsResponse);
                break;

            default:
                //
                //  TODO: Throw an exception.
                //
        }

        return $httpsResponseCode;
    }
}