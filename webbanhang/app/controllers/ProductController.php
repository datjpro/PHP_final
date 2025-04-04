<?php
// Require SessionHelper and other necessary files
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once 'app/helpers/SessionHelper.php';

class ProductController
{
    private $productModel;
    private $db;
    
    public function __construct()
    {
        // Đảm bảo session đã được khởi động
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }
    
    public function index()
    {
        $products = $this->productModel->getProducts();
        include 'app/views/product/list.php';
    }

    private function isAdmin() {
        // return SessionHelper::isAdmin();
        return true;
        }
        
    
    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }
    
    public function add()
{
    if (!$this->isAdmin()) {
        echo "Bạn không có quyền truy cập chức năng này.";
        return;
    }
    $categories = (new CategoryModel($this->db))->getCategories();
    include_once 'app/views/product/add.php';
}
    
public function save()
{
    if (!$this->isAdmin()) {
        echo "Bạn không có quyền truy cập chức năng này.";
        return;
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? '';
        $category_id = $_POST['category_id'] ?? null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = $this->uploadImage($_FILES['image']);
        } else {
            $image = "";
        }

        $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);

        if (is_array($result)) {
            $errors = $result;
            $categories = (new CategoryModel($this->db))->getCategories();
            include 'app/views/product/add.php';
        } else {
            header('Location: /Product');
        }
    }
}
    
public function edit($id)
{
    if (!$this->isAdmin()) {
        echo "Bạn không có quyền truy cập chức năng này.";
        return;
    }
    $product = $this->productModel->getProductById($id);
    $categories = (new CategoryModel($this->db))->getCategories();
    if ($product) {
        include 'app/views/product/edit.php';
    } else {
        echo "Không thấy sản phẩm.";
    }
}
public function update()
{
    if (!$this->isAdmin()) {
        echo "Bạn không có quyền truy cập chức năng này.";
        return;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category_id = $_POST['category_id'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = $this->uploadImage($_FILES['image']);
        } else {
            $image = $_POST['existing_image'];
        }

        $edit = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);
        if ($edit) {
            header('Location: /Product');
        } else {
            echo "Đã xảy ra lỗi khi lưu sản phẩm.";
        }
    }
}
    
public function delete($id)
{
    if (!$this->isAdmin()) {
        echo "Bạn không có quyền truy cập chức năng này.";
        return;
    }
    if ($this->productModel->deleteProduct($id)) {
        header('Location: /Product');
    } else {
        echo "Đã xảy ra lỗi khi xóa sản phẩm.";
    }
}
    private function uploadImage($file)
    {
        $target_dir = "uploads/";
        // Kiểm tra và tạo thư mục nếu chưa tồn tại
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Kiểm tra xem file có phải là hình ảnh không
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            throw new Exception("File không phải là hình ảnh.");
        }
        
        // Kiểm tra kích thước file (10 MB = 10 * 1024 * 1024 bytes)
        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh có kích thước quá lớn.");
        }
        
        // Chỉ cho phép một số định dạng hình ảnh nhất định
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG và GIF.");
        }
        
        // Lưu file
        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
        }
        
        return $target_file;
    }
    
    public function addToCart($id)
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            echo "Không tìm thấy sản phẩm.";
            return;
        }
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image
            ];
        }
        
        header('Location: /Product/cart');
    }
    
    public function cart()
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        include 'app/views/product/cart.php';
    }
    
    public function checkout()
    {
        include 'app/views/product/checkout.php';
    }
    
    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            
            // Kiểm tra giỏ hàng
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                echo "Giỏ hàng trống.";
                return;
            }
            
            // Bắt đầu giao dịch
            $this->db->beginTransaction();
            try {
                // Lưu thông tin đơn hàng vào bảng orders
                $query = "INSERT INTO orders (name, phone, address) VALUES (:name, :phone, :address)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':address', $address);
                $stmt->execute();
                $order_id = $this->db->lastInsertId();
                
                // Lưu chi tiết đơn hàng vào bảng order_details
                $cart = $_SESSION['cart'];
                foreach ($cart as $product_id => $item) {
                    $query = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                              VALUES (:order_id, :product_id, :quantity, :price)";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':quantity', $item['quantity']);
                    $stmt->bindParam(':price', $item['price']);
                    $stmt->execute();
                }
                
                // Xóa giỏ hàng sau khi đặt hàng thành công
                unset($_SESSION['cart']);
                
                // Commit giao dịch
                $this->db->commit();
                
                // Chuyển hướng đến trang xác nhận đơn hàng
                header('Location: /Product/orderConfirmation');
            } catch (Exception $e) {
                // Rollback giao dịch nếu có lỗi
                $this->db->rollBack();
                echo "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
            }
        }
    }
    
    public function orderConfirmation()
    {
        include 'app/views/product/orderConfirmation.php';
    }
    
    // Phương thức mới để cập nhật số lượng sản phẩm trong giỏ hàng (AJAX)
    public function updateQuantity() {
        // Kiểm tra xem có phải là AJAX request không
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
            echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ']);
            return;
        }
        
        // Lấy thông tin từ request
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
        
        // Kiểm tra tính hợp lệ của dữ liệu
        if ($id <= 0 || $quantity <= 0) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            return;
        }
        
        // Kiểm tra xem sản phẩm có tồn tại trong giỏ hàng không
        if (!isset($_SESSION['cart'][$id])) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng']);
            return;
        }
        
        // Cập nhật số lượng trong session
        $_SESSION['cart'][$id]['quantity'] = $quantity;
        
        // Trả về kết quả thành công
        echo json_encode(['success' => true]);
    }
    
    // Phương thức cải tiến để xóa sản phẩm khỏi giỏ hàng (hỗ trợ AJAX)
    public function removeFromCart($id) {
        // Kiểm tra xem có phải là AJAX request không
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
        
        if (!$id) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => 'ID sản phẩm không hợp lệ']);
                return;
            } else {
                header('Location: /Product/cart');
                return;
            }
        }
        
        // Xóa sản phẩm khỏi giỏ hàng
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        
        if ($isAjax) {
            echo json_encode(['success' => true]);
        } else {
            header('Location: /Product/cart');
        }
    }
    // Add this method to your ProductController class
public function search()
{
    $query = isset($_GET['query']) ? trim($_GET['query']) : '';
    
    if (empty($query)) {
        // If no query provided, redirect to product list
        header('Location: /Product');
        return;
    }
    
    // Get search results from model
    $products = $this->productModel->searchProducts($query);
    
    // Pass search query to view for display purposes
    $searchQuery = htmlspecialchars($query, ENT_QUOTES, 'UTF-8');
    
    // Load view with search results
    include 'app/views/product/search_results.php';
}
}
?>
