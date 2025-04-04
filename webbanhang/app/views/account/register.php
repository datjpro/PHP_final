<?php include 'app/views/shares/header.php'; ?>
<div class="container-fluid">
    <div class="row min-vh-100">
        <!-- Left Side - Promotional Image -->
        <div class="col-lg-6 d-none d-lg-flex p-0 position-relative overflow-hidden">
            <img 
                src="/public/images/registration-banner.jpg" 
                alt="Shop Registration" 
                class="img-fluid w-100 h-100 object-fit-cover"
            >
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
            <div class="position-absolute top-50 start-50 translate-middle text-center text-white">
                <h1 class="display-4 mb-3">Chào mừng đến với cửa hàng của chúng tôi</h1>
                <p class="lead">Tham gia cùng hàng nghìn khách hàng hài lòng</p>
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <div class="bg-white rounded-circle p-2">
                        <i class="fab fa-cc-visa fa-2x text-primary"></i>
                    </div>
                    <div class="bg-white rounded-circle p-2">
                        <i class="fab fa-cc-mastercard fa-2x text-danger"></i>
                    </div>
                    <div class="bg-white rounded-circle p-2">
                        <i class="fab fa-cc-paypal fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Registration Form -->
        <div class="col-lg-6 d-flex align-items-center bg-light">
            <div class="w-100 p-5">
                <div class="text-center mb-5">
                    <img 
                        src="/public/images/logo.png" 
                        alt="Logo cửa hàng" 
                        class="mb-4" 
                        style="max-height: 80px;"
                    >
                    <h2 class="mb-3">Tạo tài khoản của bạn</h2>
                    <p class="text-muted">Bắt đầu hành trình mua sắm ngay hôm nay!</p>
                </div>

                <?php
                if (isset($errors) && !empty($errors)) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                    foreach ($errors as $err) {
                        echo "<p class='mb-1'>$err</p>";
                    }
                    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '</div>';
                }
                ?>

                <form action="/account/save" method="post" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="username" 
                                name="username" 
                                required 
                                placeholder="Chọn tên đăng nhập"
                            >
                            <div class="invalid-feedback">
                                Vui lòng nhập tên đăng nhập
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fullname" class="form-label">Họ và tên</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="fullname" 
                                name="fullname" 
                                required 
                                placeholder="Nhập họ và tên của bạn"
                            >
                            <div class="invalid-feedback">
                                Vui lòng nhập họ và tên
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <div class="input-group">
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="password" 
                                    name="password" 
                                    required 
                                    placeholder="Tạo mật khẩu mạnh"
                                >
                                <button 
                                    class="btn btn-outline-secondary" 
                                    type="button" 
                                    id="togglePassword"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="form-text text-muted">
                                Sử dụng ít nhất 8 ký tự
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="confirmpassword" class="form-label">Xác nhận mật khẩu</label>
                            <div class="input-group">
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="confirmpassword" 
                                    name="confirmpassword" 
                                    required 
                                    placeholder="Nhập lại mật khẩu"
                                >
                                <button 
                                    class="btn btn-outline-secondary" 
                                    type="button" 
                                    id="toggleConfirmPassword"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            id="terms" 
                            required
                        >
                        <label class="form-check-label" for="terms">
                            Tôi đồng ý với <a href="#" class="text-primary">Điều khoản và Điều kiện</a>
                        </label>
                        <div class="invalid-feedback">
                            Bạn phải đồng ý với điều khoản và điều kiện
                        </div>
                    </div>

                    <div class="d-grid">
                        <button 
                            type="submit" 
                            class="btn btn-primary btn-lg"
                        >
                            Tạo tài khoản
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p class="text-muted">
                        Đã có tài khoản? 
                        <a href="/account/login" class="text-primary">Đăng nhập</a>
                    </p>
                    <div class="mt-3">
                        <span class="text-muted me-2">Đăng ký bằng</span>
                        <a href="#" class="btn btn-outline-primary btn-sm me-2">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-outline-danger btn-sm me-2">
                            <i class="fab fa-google"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info btn-sm">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Giữ nguyên phần JavaScript
</script>
<?php include 'app/views/shares/footer.php'; ?>
