<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        .navbar {
            background: linear-gradient(135deg, #2c3e50 0%, #1a2530 100%);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .navbar-brand, .nav-link {
            color: white !important;
        }
        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
        }
        .search-form {
            position: relative;
        }
        .search-input {
            border-radius: 20px;
            padding-right: 40px;
        }
        .search-button {
            position: absolute;
            right: 5px;
            top: 5px;
            border: none;
            background: transparent;
            color: #6c757d;
        }
        .user-actions .btn {
            color: white;
            margin-left: 10px;
        }
        .product-image {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-box-open mr-2"></i>Quản lý sản phẩm
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/Product/"><i class="fas fa-list mr-1"></i>Danh sách sản phẩm</a>
                    </li>
                    <?php if (SessionHelper::isLoggedIn() && $_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/Product/add"><i class="fas fa-plus-circle mr-1"></i>Thêm sản phẩm</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/Category"><i class="fas fa-tags mr-1"></i>Danh mục</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/Product/cart"><i class="fas fa-shopping-cart mr-1"></i>Giỏ hàng</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0 search-form" action="/Product/search" method="GET">
                    <input class="form-control mr-sm-2 search-input" type="search" name="query" placeholder="Tìm kiếm sản phẩm..." aria-label="Search">
                    <button class="search-button" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <div class="user-actions">
                    <ul class="navbar-nav">
                        <li class="nav-item" id="nav-login">
                            <a href="/account/login" class="btn btn-outline-light btn-sm">Đăng nhập</a>
                        </li>
                        <li class="nav-item" id="nav-logout" style="display: none;">
                            <a href="#" class="btn btn-outline-light btn-sm" onclick="logout()">Đăng xuất</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <script>
        function logout() {
            localStorage.removeItem('jwtToken');
            location.href = '/account/login';
        }
        document.addEventListener("DOMContentLoaded", function() {
            const token = localStorage.getItem('jwtToken');
            if (token) {
                document.getElementById('nav-login').style.display = 'none';
                document.getElementById('nav-logout').style.display = 'block';
            } else {
                document.getElementById('nav-login').style.display = 'block';
                document.getElementById('nav-logout').style.display = 'none';
            }
        });
    </script>
    <div class="container mt-4">
        <!-- Content goes here -->
    </div>
</body>
</html>