<?php
/*

// Bắt đầu session
session_start();

// Định nghĩa hằng số đường dẫn gốc
define('ROOT_DIR', __DIR__);

// Cấu hình báo lỗi (chỉ sử dụng khi phát triển)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Tự động load các class cần thiết
spl_autoload_register(function($className) {
    // Xác định các vị trí có thể chứa class
    $locations = [
        'app/models/',
        'app/controllers/',
        'app/config/',
        'app/lib/'
    ];
    
    // Kiểm tra từng vị trí
    foreach ($locations as $location) {
        $file = ROOT_DIR . '/' . $location . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    
    return false;
});

// Xử lý URL
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Xác định controller
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'ProductController'; // Mặc định là ProductController
$controllerFile = ROOT_DIR . '/app/controllers/' . $controllerName . '.php';

// Xác định action
$action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';

// Kiểm tra sự tồn tại của controller và action
if (!file_exists($controllerFile)) {
    // Log lỗi
    error_log("Controller not found: $controllerFile");
    
    // Debug output khi phát triển
    echo "Controller not found: $controllerName<br>";
    echo "Expected file path: $controllerFile<br>";
    echo "Current directory: " . __DIR__ . "<br>";
    
    // Hiển thị danh sách các controller hiện có
    echo "Available controllers:<br>";
    $controllers = glob(ROOT_DIR . '/app/controllers/*Controller.php');
    foreach ($controllers as $controller) {
        echo "- " . basename($controller) . "<br>";
    }
    
    // Chuyển hướng đến controller mặc định trong môi trường production
    // header('Location: /Product');
    exit;
}

// Load controller
require_once $controllerFile;

// Kiểm tra xem class controller có tồn tại không
if (!class_exists($controllerName)) {
    echo "Controller class '$controllerName' not found in file $controllerFile";
    exit;
}

// Khởi tạo controller
$controller = new $controllerName();

// Kiểm tra xem method có tồn tại không
if (!method_exists($controller, $action)) {
    // Log lỗi
    error_log("Action not found: $action in $controllerName");
    
    // Chuyển hướng đến action mặc định
    $action = 'index';
    
    // Kiểm tra lại method mặc định
    if (!method_exists($controller, $action)) {
        echo "Default action '$action' not found in controller '$controllerName'";
        exit;
    }
}

// Gọi action với các tham số còn lại (nếu có)
$params = array_slice($url, 2);
call_user_func_array([$controller, $action], $params);

*/
session_start();
require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';
require_once 'app/controllers/ProductApiController.php';
require_once 'app/controllers/CategoryApiController.php';
// Start session
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);
// Kiểm tra phần đầu tiên của URL để xác định controller
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' :
'ProductController';
// Kiểm tra phần thứ hai của URL để xác định action
$action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';
// Định tuyến các yêu cầu API
if ($controllerName === 'ApiController' && isset($url[1])) {
$apiControllerName = ucfirst($url[1]) . 'ApiController';
if (file_exists('app/controllers/' . $apiControllerName . '.php')) {
require_once 'app/controllers/' . $apiControllerName . '.php';
$controller = new $apiControllerName();
$method = $_SERVER['REQUEST_METHOD'];
$id = $url[2] ?? null;
switch ($method) {
case 'GET':
if ($id) {
$action = 'show';
} else {
$action = 'index';
}
break;
case 'POST':
$action = 'store';
break;
case 'PUT':
if ($id) {
$action = 'update';
}
break;
case 'DELETE':
if ($id) {
$action = 'destroy';
}
break;
default:
http_response_code(405);
echo json_encode(['message' => 'Method Not Allowed']);
exit;
}
if (method_exists($controller, $action)) {
if ($id) {
call_user_func_array([$controller, $action], [$id]);
} else {
call_user_func_array([$controller, $action], []);
}
} else {
http_response_code(404);
echo json_encode(['message' => 'Action not found']);
}
exit;
} else {
http_response_code(404);
echo json_encode(['message' => 'Controller not found']);
exit;
}
}
// Tạo đối tượng controller tương ứng cho các yêu cầu không phải API
if (file_exists('app/controllers/' . $controllerName . '.php')) {
require_once 'app/controllers/' . $controllerName . '.php';
$controller = new $controllerName();
} else {
die('Controller not found');
}
// Kiểm tra và gọi action
if (method_exists($controller, $action)) {
call_user_func_array([$controller, $action], array_slice($url, 2));
} else {
die('Action not found');
}
?>
