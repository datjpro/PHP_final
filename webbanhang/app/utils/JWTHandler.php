<?php
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHandler
{
    private $secretKey;

    /**
     * Constructor: Khởi tạo với khóa bí mật
     */
    public function __construct()
    {
        $this->secretKey = "Leon@123"; // Thay thế bằng khóa bí mật của bạn
    }

    /**
     * Tạo JWT
     * 
     * @param array $data Dữ liệu cần mã hóa
     * @return string JWT đã mã hóa
     */
    public function encode(array $data): string
    {
        $issuedAt = time(); // Thời gian phát hành
        $expirationTime = $issuedAt + 3600; // Thời gian hết hạn (1 giờ)

        $payload = [
            'iat' => $issuedAt,        // Thời gian phát hành
            'exp' => $expirationTime,  // Thời gian hết hạn
            'data' => $data            // Dữ liệu cần mã hóa
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    /**
     * Giải mã JWT
     * 
     * @param string $jwt JWT cần giải mã
     * @return array|null Dữ liệu đã giải mã hoặc null nếu không hợp lệ
     */
    public function decode(string $jwt): ?array
    {
        try {
            $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));
            return (array) $decoded->data;
        } catch (Exception $e) {
            // Xử lý lỗi nếu JWT không hợp lệ
            return null;
        }
    }
}
?>