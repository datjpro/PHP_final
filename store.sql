-- Xóa database cũ
DROP DATABASE IF EXISTS my_store;

-- Tạo lại database
CREATE DATABASE my_store;
USE my_store;

-- Tạo bảng danh mục trước
CREATE TABLE category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

-- Tạo bảng sản phẩm sau, tham chiếu đến bảng danh mục
CREATE TABLE product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE CASCADE
);

-- Tạo các bảng khác
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
);

-- Thêm dữ liệu vào bảng category
INSERT INTO category (name, description) VALUES
('Điện thoại', 'Danh mục các loại điện thoại'),
('Laptop', 'Danh mục các loại laptop'),
('Máy tính bảng', 'Danh mục các loại máy tính bảng'),
('Phụ kiện', 'Danh mục phụ kiện điện tử'),
('Thiết bị âm thanh', 'Danh mục loa, tai nghe, micro');

-- Chèn dữ liệu mẫu vào bảng product với image là NULL
INSERT INTO product (name, description, price, image, category_id) VALUES
('iPhone 15 Pro', 'Điện thoại cao cấp mới nhất của Apple với chip A17 Pro', 28990000, NULL, 1),
('Samsung Galaxy S24 Ultra', 'Điện thoại flagship của Samsung với camera 200MP', 31990000, NULL, 1),
('Xiaomi 14', 'Điện thoại cao cấp với chip Snapdragon 8 Gen 3', 19990000, NULL, 1),
('Macbook Pro 16 M3', 'Laptop chuyên đồ họa với chip M3 Max', 69990000, NULL, 2),
('Dell XPS 15', 'Laptop mỏng nhẹ cấu hình mạnh cho doanh nhân', 49990000, NULL, 2),
('ASUS ROG Zephyrus G16', 'Laptop gaming cấu hình mạnh với RTX 4090', 79990000, NULL, 2),
('iPad Pro 13', 'Máy tính bảng cao cấp với chip M2', 29990000, NULL, 3),
('Samsung Galaxy Tab S9 Ultra', 'Máy tính bảng màn hình lớn 14.6 inch', 25990000, NULL, 3),
('Xiaomi Pad 6', 'Máy tính bảng tầm trung màn hình 144Hz', 8990000, NULL, 3),
('Apple AirPods Pro 2', 'Tai nghe không dây chống ồn chủ động', 5990000, NULL, 4),
('Sạc nhanh Anker 65W', 'Sạc nhanh đa cổng cho laptop và điện thoại', 1490000, NULL, 4),
('Ốp lưng iPhone 15 Pro', 'Ốp lưng chính hãng bảo vệ điện thoại', 890000, NULL, 4),
('JBL Flip 6', 'Loa bluetooth chống nước, pin 12 giờ', 2490000, NULL, 5),
('Sony WH-1000XM5', 'Tai nghe chụp tai chống ồn cao cấp', 8490000, NULL, 5),
('Microphone HyperX QuadCast', 'Microphone thu âm chuyên nghiệp cho streamer', 3990000, NULL, 5);