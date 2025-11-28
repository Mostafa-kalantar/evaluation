<?php

use App\Helpers\Classes\Encryptor;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


if (!function_exists('httpRequest')) {
    function httpRequest(string $url, array $body = [], array $headers = [], $method = 'POST', $request_type = 'json')
    {
        $send = match ($request_type) {
            'form' => Http::asForm(),
            'multipart' => Http::asMultipart(),
            default => Http::asJson(),
        };

        $start = microtime(true);
        $response = $send
            ->withoutVerifying()
            ->withHeaders($headers)
            ->$method($url, $body);
        $end = microtime(true);
        if ($method == 'POST' && empty($response)) {
            $response = objectify(['status' => 'error', 'err' => [['code' => 999, 'msg' => 'Webservice error']]]);
        }
        $response = $method === 'POST' ? json_decode($response) : $response->getBody()->getContents();
        if (isset($response->err) && is_object($response->err))
            $response->err = (array)$response->err;
        $response->srver_response_time = $end - $start;
        return $response;
    }
}

if (!function_exists('apiResponse')) {
    function apiResponse($data = null, string $message = null, $errors = null, $status = 'success', $error_code = ResponseAlias::HTTP_NOT_ACCEPTABLE): \Illuminate\Http\JsonResponse
    {
        $status_code = $errors || $status != 'success' ? $error_code : 200;
        if ($errors && !is_array($errors))
            $errors = (array)$errors;
        if ($status_code != 200)
            $status = 'error';
        $response = [
            'status' => $status,
//            'error_code' => $errors ? 1 : 0,
            'message' => ''
        ];
        $response['data'] = $data ?: [];
        if ($status == 'success' && !func_num_args())
            $response['message'] = trans('messages.success_response');

        if ($status == 'success') {
            if ($message)
                $response['message'] = $message;
        } else
            $response['errors'] = $errors;
        return response()->json($response, $status_code);
    }
}

if (!function_exists('enCode')) {
    function enCode(string $string, $type = 'ssl'): string
    {
        $encryptor = new Encryptor();
        return match ($type) {
            'aes' => $encryptor->aesEncrypt($string),
            'url' => $encryptor->base64UrlEncrypt($string),
            default => $encryptor->openSSLEncrypt($string),
        };
    }
}

if (!function_exists('deCode')) {
    function deCode(string $string, $type = 'ssl'): string
    {
        $encryptor = new Encryptor();
        switch ($type) {
            case 'aes':
                return $encryptor->aesDecrypt($string);
            case 'url':
                return $encryptor->base64UrlDecrypt($string);
            default:
                return $encryptor->openSSLDecrypt($string);
        }
    }
}

if (!function_exists('objectify')) {
    function objectify($data)
    {
        return json_decode(json_encode($data));
    }
}
