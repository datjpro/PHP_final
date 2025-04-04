<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');

class CategoryApiController
{
    private $categoryModel;
    private $db;
    
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }
    
    // Lấy danh sách danh mục
    public function index()
    {
        try {
            header('Content-Type: application/json');
            
            // Đảm bảo không có output nào trước khi gửi header
            ob_clean();
            
            $categories = $this->categoryModel->getCategories();
            
            // Kiểm tra dữ liệu trả về
            if ($categories === false) {
                http_response_code(500);
                echo json_encode(['error' => 'Không thể lấy danh sách danh mục']);
                return;
            }
            
            // Log dữ liệu để debug (có thể bỏ khi đã hoạt động)
            error_log('Categories data: ' . print_r($categories, true));
            
            echo json_encode($categories);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
?>
