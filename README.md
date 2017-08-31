# CityPay SDK for PHP
## Structural overview
The CityPay SDK for PHP application programming interface ("**API**") is
structured generally in terms of providing a Request object, invoking some
operation and receiving a Response object that may be processed in the ordinary
flow of the program.

### Supported payment processing flows

The API supports two payment processing flows –

1. the "_**Authorise and Complete**_" payment process where a single request
made using the API coupled with any redirection that may be required to direct
the Customer Browser to the Hosted Form results in both authorization of the
relevant payment, and the necessary completion of the payment request to achieve
the transfer of funds from the Customer to the Merchant; and
2. the "_**Pre-authorise**_" payment process where request made using the API
coupled with any redirection that may be required to direct the Customer
Browser to the Hosted Form only results in obtaining, from the Acquirer, a
pre-authorisation for the relevant payment where a sequent completion step must
be performed to achieve the transfer of funds from the Customer to the Merchant.

## Using the API in a PHP project
The API has been developed in accordance with the PHP Standards Recommendations
and, in particular –

1. [PSR-1](http://www.php-fig.org/psr/psr-1/), the basic coding standard;
2. [PSR-2](http://www.php-fig.org/psr/psr-2/), the coding style guide; 
3. [PSR-3](http://www.php-fig.org/psr/psr-3/), the logger interface standard;
   and
4. [PSR-4](http://www.php-fig.org/psr/psr-4/), the class autoloading standard.

The API has been developed using [Composer](https://getcomposer.org/ "Composer")
to manage dependencies. To import the CityPay SDK for PHP into your project
specify the following in your `composer.json` file --

    {
        "require": {
            "citypay/php-sdk": "1.0.*"
        }
    }
and then run `php composer.phar install`

## Hosted Form payment processing
Payment processing using a Hosted Form is supported by the API using PayLink.
The API requires the Merchant Application to –

1. request a PayLink token using the `saleTransaction()` method of a newly
created and initialized instance of the `PayLinkRequest` class;
2. accept an instance of the `PayLinkToken` class which contains a token
identifier, and a URL;
3. redirect the Customer Browser to the URL provided by the relevant
`PayLinkToken`; and
4. accept notification transmitted back to the Merchant Application indicating
the results of the transaction processing attempt. This latter notification is
made using either or both –
    1. a Postback HTTP or HTTPS request made by PayLink to the Merchant
Application; and
    2. an indirect notification from PayLink through redirection of the Customer
Browser from the PayLink hosted payment form to the URL endpoint provided by
the Merchant Application to the PayLink when the original PayLink token was
requested through configuration of the relevant PayLinkRequest instance.

### Hosted Form "_**Authorise and Complete**_" process

    //
    //  Create an appropriate PaymentRequest object, initialized with the
    //  merchant identifier, the licence key associated with the merchant
    //  identifier, the the amount for which the transaction is to be
    //  processed together with the appropriate customer information,
    //  payment card details, and token configuration.
    //
    $paylinkRequest = (new \CityPay\PayLink\PayLinkRequest())
        ->merchantId("<merchant-id>")
        ->licenceKey("<licence-key>")
        ->identifier("<transaction-identifier>")
        ->amount(5000)
        ->currency("GBP")
        ->cardholder(
            (new \CityPay\PayLink\Cardholder())
                ->title("<title>")
                ->firstName("<firstName>")
                ->lastName("<lastName>")
                ->address(
                    (new Address())
                        ->address1("<addressLine1>")
                        ->address2("<addressLine2>")
                        ->area("<area>")
                )
            )
        ->configuration(
            (new \CityPay\PayLink\Configuration())
                ->postbackPolicy("sync")
                ->postback("http://postback/address")
                ->redirect("http://general/redirect")
                ->redirectSuccess("http://success/redirect")
                ->redirectFailure("http://failure/redirect")
                ->options(
                    Option.BYPASS_CUSTOMER_EMAIL,
                    Option.BYPASS_MERCHANT_EMAIL,
                    Option.BYPASS_AVS_ADDRESS,
                    Option.BYPASS_AVS_POSTCODE,
                    Option.ENFORCE_CSC_REQUIRED
                )
            );

    try {

       $apiMessage = $paylinkRequest->saleTransaction()

    } catch (Exception $e) {


    }

### Hosted Form "Pre-authorise" process

    $paylinkRequest = (new \CityPay\PayLink\PayLinkRequest())
        ->merchantId("<merchant-id>")
        ->licenceKey("<licence-key>")
        ->identifier("<transaction-identifier>")
        ->amount(5000)
        ->currency("GBP")
        ->cardholder(
            (new \CityPay\PayLink\Cardholder())
                ->title("<title>")
                ->firstName("<firstName>")
                ->lastName("<lastName>")
                ->address(
                    (new Address())
                        ->address1("<addressLine1>")
                        ->address2("<addressLine2>")
                        ->area("<area>")
                )
            )
        ->configuration(
            (new \CityPay\PayLink\Configuration())
                ->postbackPolicy("sync")
                ->postback("http://postback/address")
                ->redirect("http://general/redirect")
                ->redirectSuccess("http://success/redirect")
                ->redirectFailure("http://failure/redirect")
                ->options(
                    Option.BYPASS_CUSTOMER_EMAIL,
                    Option.BYPASS_MERCHANT_EMAIL,
                    Option.BYPASS_AVS_ADDRESS,
                    Option.BYPASS_AVS_POSTCODE,
                    Option.ENFORCE_CSC_REQUIRED
                )
            );

    try {

        $apiMessage = paylinkRequest->saleTransaction()

    } catch (Exception $e) {


    }

### Hosted Form Postback processing

The approach to be applied for postback handling in the context of PHP
applications is not presently settled.

## Direct Request payment processing

### Direct Request "_**Authorise and Complete**_" payment process

An "_**Authorise and Complete**_" transaction by direct request involves the
Merchant Application submitting payment card details to PayPost to obtain
authorization for the transaction and to draw the authorized amount down from
the payment card issuer for the purpose of onward settlement to the Merchant.
PayPost supports the processing of transactions that are authenticated using
3D Secure technology, and those that are not.

### Non-3D Secure authenticated payment processing

Non-3D Secure authenticated payment processing data and control flow involves –

1. creation and initialization of an instance of the `PayPostRequest`
class;
2. invoking the `saleTransaction()` method of the newly created `PayPostRequest`
object;
3. processing of the PayPostResponse object returned by the call to
`saleTransaction()`.

Additionally, to support transactions authenticated with 3D Secure the Merchant
Application must expose an endpoint URL, the "_Merchant Terminal URL_",
following redirection by the ACS back to the Merchant Application on conclusion
of the Customer's interactions with the ACS.

#### PHP code sample

    //
    //  Create an appropriate PayPostRequest object, initialized with the
    //  merchant identifier, the licence key associated with the merchant
    //  identifier, the the amount for which the transaction is to be
    //  processed together with the appropriate customer information, and
    //  payment card details.
    //
    $salePayPostRequest = (new \CityPay\PayPost\PayPostRequest())
        ->merchantId("<merchant-id>")
        ->licenceKey("<licence-key>")
        ->identifier("<transaction-identifier>")
        ->amount(<amountInLDF>)
        ->currency("<currency>")
        ->billToName("<customer-name>")
        ->billToPostCode("<postal-code>")
        ->cardNumber("<cardnumber>")
        ->expiryMonth(<expiryMonth>)
        ->expiryYear(<expiryYear>)
        ->csc(<card-security-code>);

    //
    //  Invoke the saleTransaction() method of the relevant PayPostRequest
    //  object, and receive the ApiMessage returned.
    //
    $saleApiMessage = $salePayPostRequest->saleTransaction()

    //
    //  If the "sale transaction" operation was processed correctly, though
    //  not necessarily successfully, the ApiMessage returned will be of type
    //  PayPostResponse. Accordingly, the Merchant Application should test the
    //  return type of the ApiMessage to correctly process the results generated
    //  by the operation.
    //
    if (!($saleApiMessage instanceof \CityPay\PayPost\PayPostResponse)) {
        //
        //  Throw exception.
        //
    }

    //
    //  Ascertain whether the sale transaction was successful by reference
    //  to the salePayPostResponse object, and handle any reported
    //  processing issues.
    //
    if ($saleApiMessage->isAuthorised()) {
        //
        //  The sale transaction was successfully authorized and, subject
        //  to whether sale transactions are authorized and completed by
        //  default, or pre-authorised by default, the Service Provider
        //  may seek settlement.
        //
    } else {
        //
        //  The sale transaction was not successfully authorized.
        //
    }

### 3D Secure authenticated payment processing
The direct request payment processing model provides support for both 3D Secure
transactions and non-3D Secure transaction. Whereas non-3D Secure payment
processing data and control flow involves creation of a PayPostRequest object
followed by a call to the `saleTransaction()` method to obtain a
`PayPostResponse` object, a 3D Secure transaction requires –

1. creation and initialization of an instance of the `AcsPayPostRequest` class;
2. invoking the `saleTransaction()` method of the newly created
`AcsPayPostRequest` object;
3. processing either a `PayPostAuthenticationRequiredResponse` object or a
`PayPostResponse` object depending on whether the payment card issuer supports
3D Secure transactions for the relevant payment card, and whether the payment
card is itself enrolled to the appropriate 3D Secure scheme such that, where –
    1. a `PayPostAuthenticationRequiredResponse` object is received, the
    Merchant Application must –
        1. refer the Customer Browser to the payment card issuer's access
        control server ("ACS");
        2. accept a HTTPS POST call to the URL provided by merchantTermURL that
        accepts the results of the ACS authentication process and forwards them
        to PayPost to obtain authorization for the transaction; OR
    2. a `PayPostResponse` object is received, the Merchant Application
    processes the results of the transaction.

Additionally, to support transactions authenticated with 3D Secure the Merchant
Application must expose an endpoint URL, the "_Merchant Terminal URL_",
following redirection back to the Merchant Application, by the ACS, on
conclusion of the Customer's interactions with the ACS.

#### `AcsPayPostRequest` initialization functions

The attributes of an instance of the `AcsPayPostRequest` class are the same as
those for an instance of the PayPostRequest class, apart from the following
additional attributes.

|Method|Description and purpose|
|:-----|:----------------------|
|`merchantTermURL($merchantTermURL)`|The `$merchantTermURL` parameter is a string containing a valid URL for the endpoint provided by the Merchant Application that is responsible for forwarding the response (an encoded PaRes packet) from the ACS to PayPost confirming the authorization status of the transaction. The URL is provided at the time the transaction request is made to PayPost using the `saleTransaction()` method of the `PayPostRequest` object.|
|`userAgent($userAgent)`|The `$userAgent` string contains the exact content of the HTTP User-Agent header as sent by the cardholder's user agent, the Customer Browser, to the Merchant Application. This value is validated by the ACS when the cardholder authenticates themselves to ensure that no intermediary is performing this action.|
|`acceptHeaders($acceptHeaders)`|The `$acceptHeaders` string contains the exact content of the HTTP Accept header as sent by the cardholder's user agent, the Customer Browser, to the Merchant Application. This value is validated by the ACS when the card holder authenticates themselves to ensure that no intermediary is performing this action.|

#### `PayPostAuthenticationRequiredResponse` methods

If the `ApiMessage` object returned by the call to `saleTransaction()` is an
instance of the `PayPostAuthenticationRequiredResponse` class, the sale
transaction must be authenticated using 3D Secure.

`PayPostAuthenticationRequiredResponse` objects expose the following
attributes –

|Method|Description and purpose|
|:-----|:----------------------|
|`getAcsUrl()`|The `$acsUrl` field of `PayPostAuthenticationRequiredResponse` object contains the URL of the ACS for the purpose of authenticating payment card transactions. To complete the payment card transaction, the Merchant Application must cause the Customer Browser to submit a form to the URL indicated by the `getAcsUrl()` method using a HTTP POST operation. The contents of the form is indicated below.|
|`getPaReq()`|The `$paReq` field of the response object contains an encoded string received from the payment card issuer in response to a 3D Secure transaction authentication request made by PayPost. The Merchant Application must forward this encoded string to the ACS when redirecting the Customer Browser to the ACS.|
|`getMd()`|The `$md` (merchant data) field of the response object contains a session / transaction identifier that must be transmitted to the ACS, by the Merchant Application, to maintain continuity of the transaction process.|

To obtain authentication, the Merchant Application must redirect the Customer
Browser to the ACS by causing the Customer Browser to submit following form
populated with the values received in the
`PayPostAuthenticationRequiredResponse` object and provide the same Merchant
Terminal URL as that provided by the Merchant Application in the originating
`PayPostRequest`.

    <html>
        <head>
            <script type="text/javascript">
            <!--
            function OnLoadEvent() { document.acs.submit(); }
            // -->
            </script>
        </head>
        <body onload="OnLoadEvent();">
            <form name="acs" action="ACSURL" method="POST">
                <input type="hidden" name="PaReq" value="DATA PACKET" />
                <input type="hidden" name="TermUrl" value="Termination URL" />
                <input type="hidden" name="MD" value="Merchant Data" />
            </form>
        </body>
    </html>

#### Processing the ACS redirection to the `merchantTermUrl` endpoint

If 3D Secure authenticated payments are to be supported by the Merchant
Application, the Merchant Application exposes an endpoint to accept and process
a HTTP POST operation to conclude the 3D Secure transaction. The payload
accompanying the HTTP POST request is encoded using the
`application/x-www-form-url-encoded` content type, and contains the following
named values –

|Name|Type|Description or purpose|
|:---|:---|:---------------------|
|`pares`|string|The `paRes` field of the relevant response object contains an encoded string received from the payment card issuer in response to a 3D Secure transaction authentication request made by PayPost. The Merchant Application must forward this encoded string to the ACS when redirecting the Customer Browser to the ACS.|
|`md`|string|The `md` (merchant data) field of the response object contains a session / transaction identifier that must be transmitted to the ACS, by the Merchant Application, to maintain continuity of the transaction process.|

##### PHP code sample

    //
    //  Create an appropriate AcsPayPostRequest object, initialized with the
    //  merchant identifier, the licence key associated with the merchant
    //  identifier, the the amount for which the transaction is to be
    //  processed together with the appropriate customer information, and
    //  payment card details.
    //
    $saleAcsPayPostRequest = (new \CityPay\PayPost\AcsPayPostRequest())
        ->merchantId("<merchant-id>")
        ->licenceKey("<licence-key>")
        ->identifier("<transaction-identifier>")
        ->amount(<amountInLDF>)
        ->currency("<currency>")
        ->billToName("<customer-name>")
        ->billToPostCode("<postal-code>")
        ->cardNumber("<cardnumber>")
        ->expiryMonth(<expiryMonth>)
        ->expiryYear(<expiryYear>)
        ->csc(<card-security-code>)
        ->merchantTermUrl(<merchantTermUrl>)
        ->userAgent(<userAgent>)
        ->acceptHeaders(<acceptHeaders>);

    //
    //  Invoke the saleTransaction() method of the relevant PayPostRequest
    //  object, and receive the ApiMessage returned.
    //
    $saleAcsApiMessage = saleAcsPayPostRequest->saleTransaction()

    //
    //  If the "sale transaction" operation was processed correctly, though
    //  not necessarily successfully, the ApiMessage returned will be one of
    //  two types depending on whether the relevant payment card issuer
    //  supports 3DS authentication for the relevant payment card, and whether
    //  the cardholder is enrolled in the relevant 3DS authentication scheme.
    //  Accordingly, the Merchant Application should test the return type of
    //  the ApiMessage to correctly process the results generated by the
    //  operation.
    //
    if ($saleAcsApiMessage instanceof \CityPay\PayPost\PayPostAuthenticationRequiredResponse) {
        //
        //  Refer the Customer Browser to the ACS server by returning
        //  a HTML form that may be automatically submitted by the
        //  Customer Browser on receipt.
        //
    } else if ($saleAcsApiMessage instanceof \CityPay\PayPost\PayPostResponse) {
        //
        //  Ascertain whether the sale transaction was successful by reference
        //  to the salePayPostResponse object, and handle any reported
        //  processing issues.
        //
        if ($salePayPostResponse->isAuthorised()) {
            //
            //  The sale transaction was successfully authorized and, subject
            //  to whether sale transactions are authorized and completed by
            //  default, or pre-authorised by default, the Service Provider
            //  may seek settlement.
            //
        } else {
            //
            //  The sale transaction was not successfully authorized.
            //
        }
    } else {
        //
        //  Throw exception.
        //
    }

### Direct Request **Pre-authorised** transaction process

The pre-authorised transaction is performed in essentially the same as an
"_**Authorise and Complete**_" transaction. Whether a transaction is performed as a
pre-authorised transaction, or as an _**Authorise and Complete**_ transaction is
determined by configuration of the Merchant profile by the Service Provider.

### Direct Request **Complete** transaction process

    //
    //  Determine the transaction number associated with the pre-authorised
    //  transaction to be completed.
    //
    $transNo = <transNo>

    //
    //  Determine the amount for which the pre-authorised transaction is to
    //  be completed in "lowest denomination form".
    //
    $amount = <amountInLDF>

    //
    //  Create an appropriate PayPostRequest object, initialized with the
    //  merchant identifier, the licence key associated with the merchant
    //  identifier, the transaction number and the amount for which the
    //  transaction is to be completed.
    //
    $completePayPostRequest = (new \CityPay\PayPost\PayPostRequest())
        ->merchantId(<merchant_id>)
        ->licenceKey(<licence_key>)
        ->transNo($transNo)
        ->amount($amount);

    //
    //  Invoke the completeTransaction() method of the relevant
    //  PayPostRequest object, and receive the ApiMessage returned.
    //
    $completeApiMessage = completePayPostRequest.completeTransaction();

    //
    //  If the "complete transaction" operation was processed correctly, though
    //  not necessarily successfully, the ApiMessage returned will be of type
    //  PayPostResponse. Accordingly, the Merchant Application should test the
    //  return type of the ApiMessage to correctly process the results generated
    //  by the operation.
    //
    if (!($completeApiMessage instanceof \CityPay\PayPost\PayPostResponse) {
        //
        //  Throw exception
        //
    }

### Direct Request "Cancel" transaction process

    //
    //  Determine the transaction number associated with the pre-authorized
    //  transaction to be cancelled.
    //
    $transNo = <transNo>

    //
    //  Create an appropriate PayPostRequest object, initialized with the
    //  merchant identifier, the licence key associated with the merchant
    //  identifier, and the transaction number of the pre-authorized
    //  transaction to be cancelled.
    //
    $cancelPayPostRequest = (new \CityPay\PayPost\PayPostRequest())
        ->merchantId(<merchant_id>)
        ->licenceKey(<licence_key>)
        ->transNo($transNo);

    //
    //  Invoke the completeTransaction() method of the relevant
    //  PayPostRequest object, and receive the ApiMessage returned.
    //
    $cancelApiMessage = cancelPayPostRequest.cancelTransaction();

    //
    // If the "cancel transaction" operation was processed correctly,
    // though not necessarily successfully, the ApiMessage returned will
    // be of type PayPostResponse. Accordingly, the Merchant
    // Application should test the //  return type of the ApiMessage to
    // correctly process the results generated //  by the operation.
    //
    if (!($cancelApiMessage instanceof \CityPay\PayPost\PayPostResponse) {
        //
        //  Throw exception
        //
    }

## PCI DSS Compliant Logging support

At present, the API provides partial, experimental support for PCI DSS compliant
logging using the logger interface standard provided by
[PSR-3](http://www.php-fig.org/psr/psr-3).

Unfortunately, PSR-3 does not propose a standard or guidelines for informing
supporting APIs of a suitable PSR-3 compliant logging sink. In particular,
PSR-3 does not make it clear whether APIs should use a singular logging sink,
or whether a structured hierarchy of logging sink ought to be used thereby
enabling fine-grained control over API logging behaviour.

It is expected, in due course, that informing the API of a suitable PSR-3
compliant logging sink factory will be performed through a statically bound
call to `\CityPay\Lib\Logger::setLoggerDelegate()` such that the API is able
to construct appropriate subordinate loggers through subsequent calls to
`\CityPay\Lib\Logger::getLogger()`.
