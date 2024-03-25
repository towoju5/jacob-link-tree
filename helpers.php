<?php

use Controller\Base\Database;
use Firebase\JWT\Key;
use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Url;
use Pecee\Http\Response;
use Pecee\Http\Request;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\JWT;


if (!function_exists("url")) {

    /**
     * Get url for a route by using either name/alias, class or method name.
     *
     * The name parameter supports the following values:
     * - Route name
     * - Controller/resource name (with or without method)
     * - Controller class name
     *
     * When searching for controller/resource by name, you can use this syntax "route.name@method".
     * You can also use the same syntax when searching for a specific controller-class "MyController@home".
     * If no arguments is specified, it will return the url for the current loaded route.
     *
     * @param string|null $name
     * @param string|array|null $parameters
     * @param array|null $getParams
     * @return \Pecee\Http\Url
     * @throws \InvalidArgumentException
     */
    function url(?string $name = null, $parameters = null, ?array $getParams = null): Url
    {
        return Router::getUrl($name, $parameters, $getParams);
    }
}

if (!function_exists("response")) {
    /**
     * @return \Pecee\Http\Response
     */
    function response(): Response
    {
        return Router::response();
    }
}

if (!function_exists("request")) {
    /**
     * @return \Pecee\Http\Request
     */
    function request(): Request
    {
        return Router::request() ?? null;
    }
}

if (!function_exists("input")) {
    /**
     * Get input class
     * @param string|null $index Parameter index name
     * @param string|mixed|null $defaultValue Default return value
     * @param array ...$methods Default methods
     * @return \Pecee\Http\Input\InputHandler|array|string|null
     */
    function input($index = null, $defaultValue = null, ...$methods)
    {
        if ($index !== null) {
            return request()->getInputHandler()->value($index, $defaultValue, ...$methods);
        }

        return request()->getInputHandler();
    }
}

if (!function_exists("redirect")) {
    /**
     * @param string $url
     * @param int|null $code
     */
    function redirect(string $url, ?int $code = null): void
    {
        if ($code !== null) {
            response()->httpCode($code);
        }

        response()->redirect($url);
    }
}

if (!function_exists("csrf_token")) {
    /**
     * Get current csrf-token
     * @return string|null
     */
    function csrf_token(): ?string
    {
        $baseVerifier = Router::router()->getCsrfVerifier();
        if ($baseVerifier !== null) {
            return $baseVerifier->getTokenProvider()->getToken();
        }

        return null;
    }
}


if (!function_exists('env')) {
    function env($key, $default = null): ?string
    {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('get_bearer_token')) {
    function get_bearer_token() : string
    {
        $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $bearerToken = '';
        if (preg_match('/Bearer\s+(.*)$/i', $authorizationHeader, $matches)) {
            $bearerToken = $matches[1];
        }
        return $bearerToken;
    }
}

if (!function_exists('get_user')) {
    function get_user($user_id)
    {
        $auth = new Database();
        $user = $auth->select('users', ['id' => $user_id]);
        $u = $user[0];
        unset($u['password']);
        return $u;
    }
}

if (!function_exists('generate_jwt_token')) {
    function generate_jwt_token($user_id)
    {
        $secret_key = env('JWT_SECRET', "5f2f7f6b328995e05214e4892c7e5093359931e13503bcc1b55a26f2ab8e4e7f");
        $issued_at = time();
        $expiration_time = $issued_at + (60 * 60); // valid for 1 hour

        $payload = array(
            'iat' => $issued_at,
            'exp' => $expiration_time,
            'sub' => $user_id,
            'user' => get_user($user_id)
        );

        // Pass the correct arguments to JWT::encode()
        $token = JWT::encode($payload, $secret_key, 'HS256');
        // array_push($payload, $token);
        return $token;
    }
}

if (!function_exists('validate_jwt_token')) {

    function validate_jwt_token($jwt_token)
    {
        try {
            $secret_key = env('JWT_SECRET', "5f2f7f6b328995e05214e4892c7e5093359931e13503bcc1b55a26f2ab8e4e7f");
            $decoded = JWT::decode($jwt_token, new Key($secret_key, 'HS256'));
            return $decoded;
        } catch (ExpiredException $e) {
            throw new Exception('Token expired');
        } catch (SignatureInvalidException $e) {
            throw new Exception('Invalid token signature');
        } catch (BeforeValidException $e) {
            throw new Exception('Token not valid yet');
        } catch (Exception $e) {
            throw new Exception('Invalid token');
        }
    }

}

if (!function_exists('get_success_response')) {
    function get_success_response($data)
    {
        $response = [
            'status' => 'success',
            'message' => 'Request successful',
            'data' => $data
        ];
        return json_encode($response, JSON_PRETTY_PRINT);
    }
}

if (!function_exists('get_error_response')) {
    function get_error_response($data)
    {
        $response = [
            'status' => 'failed',
            'message' => 'Request failed',
            'data' => $data
        ];
        return json_encode($response, JSON_PRETTY_PRINT);
    }
}

if (!function_exists('save_image')) {

    function save_image($file)
    {
        $uploadDirectory = 'uploads';
        if (!file_exists($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        if ($file['error'] === UPLOAD_ERR_OK) {
            // Get file details
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileType = $file['type'];

            // Generate a unique filename to prevent overwriting existing files
            $uniqueFilename = uniqid() . '_' . $fileName;

            // Validate file type
            $allowedFormats = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $allowedFormats)) {
                return ['success' => false, 'message' => 'Invalid file format.'];
            }

            // Validate file size (5 MB)
            if ($fileSize > 5 * 1024 * 1024) {
                return ['success' => false, 'message' => 'File is too large.'];
            }

            // Validate image content
            if (!getimagesize($fileTmpName)) {
                return ['success' => false, 'message' => 'Invalid image file.'];
            }

            // Move the uploaded file to the desired directory
            $uploadPath = $uploadDirectory . '/' . $uniqueFilename;
            if (move_uploaded_file($fileTmpName, $uploadPath)) {
                return $uploadPath;
            }

            return false;
        } else {
            error_log($file['error']);
            return false;
        }
    }

}