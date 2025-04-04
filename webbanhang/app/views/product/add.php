<?php include 'app/views/shares/header.php'; ?>

<div class="container">
    <h1 class="mt-4 mb-4">Thêm sản phẩm mới</h1>
    <form id="add-product-form">
        <div class="mb-3">
            <label for="name" class="form-label">Tên sản phẩm:</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Mô tả:</label>
            <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Giá:</label>
            <div class="input-group">
                <span class="input-group-text">VNĐ</span>
                <input type="number" id="price" name="price" class="form-control" step="0.01" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Danh mục:</label>
            <select id="category_id" name="category_id" class="form-control" required>
                <!-- Các danh mục sẽ được tải từ API và hiển thị tại đây -->
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
        <a href="/Product" class="btn btn-secondary mt-2">Quay lại danh sách sản phẩm</a>
    </form>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Hàm tải danh mục từ API
        function loadCategories() {
            $.ajax({
                url: '/api/category',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var categorySelect = $('#category_id');
                    categorySelect.empty(); // Xóa các tùy chọn cũ
                    $.each(data, function(index, category) {
                        categorySelect.append($('<option>', {
                            value: category.id,
                            text: category.name
                        }));
                    });
                },
                error: function() {
                    alert('Không thể tải danh mục.');
                }
            });
        }

        // Gọi hàm tải danh mục khi trang được tải
        loadCategories();

        // Xử lý sự kiện submit của form
        $('#add-product-form').submit(function(event) {
            event.preventDefault(); // Ngăn chặn form gửi đi theo cách thông thường

            var formData = {
                name: $('#name').val(),
                description: $('#description').val(),
                price: $('#price').val(),
                category_id: $('#category_id').val()
            };

            $.ajax({
                url: '/api/product',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(formData),
                success: function(data) {
                    if (data.message === 'Product created successfully') {
                        // Sử dụng SweetAlert để hiển thị thông báo thành công
                        Swal.fire({
                            title: 'Thành công!',
                            text: 'Sản phẩm đã được thêm thành công.',
                            icon: 'success',
                            confirmButtonText: 'Tuyệt vời!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/Product';
                            }
                        });
                    } else {
                        // Sử dụng SweetAlert để hiển thị thông báo lỗi
                        Swal.fire({
                            title: 'Lỗi!',
                            text: 'Thêm sản phẩm thất bại: ' + data.message,
                            icon: 'error',
                            confirmButtonText: 'Thử lại'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Lỗi từ máy chủ:", xhr.responseText);
                    // Sử dụng SweetAlert để hiển thị thông báo lỗi
                    Swal.fire({
                        title: 'Lỗi!',
                        text: 'Lỗi: Thêm sản phẩm thất bại. Vui lòng kiểm tra console để biết thêm chi tiết.',
                        icon: 'error',
                        confirmButtonText: 'Đóng'
                    });
                }
            });
        });
    });
</script>
