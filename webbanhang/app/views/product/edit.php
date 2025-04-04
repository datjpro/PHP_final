<?php include 'app/views/shares/header.php'; ?>

<div class="container">
    <h1 class="mt-4 mb-4">Sửa sản phẩm</h1>
    <form id="edit-product-form">
        <input type="hidden" id="product_id" name="id">
        
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
                <!-- Danh mục được tải từ API -->
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="/Product" class="btn btn-secondary mt-2">Quay lại</a>
    </form>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
    const productId = new URLSearchParams(window.location.search).get('id');
    
    // Tải thông tin sản phẩm và danh mục
    $.when(
        $.get('/api/category'),
        $.get(`/api/product/${productId}`)
    ).done(function(categoriesResp, productResp) {
        const categories = categoriesResp[0];
        const product = productResp[0];
        
        // Điền thông tin sản phẩm
        $('#product_id').val(product.id);
        $('#name').val(product.name);
        $('#description').val(product.description);
        $('#price').val(product.price);
        
        // Điền danh mục
        const $categorySelect = $('#category_id').empty();
        $categorySelect.append('<option value="">-- Chọn danh mục --</option>');
        $.each(categories, function(i, category) {
            $categorySelect.append(
                $('<option>', {
                    value: category.id,
                    text: category.name,
                    selected: category.id === product.category_id
                })
            );
        });
    }).fail(function() {
        Swal.fire('Lỗi!', 'Không tải được dữ liệu', 'error')
            .then(() => window.location = '/Product');
    });

    // Xử lý cập nhật
    $('#edit-product-form').submit(function(e) {
        e.preventDefault();
        
        const formData = {
            id: $('#product_id').val(),
            name: $('#name').val(),
            description: $('#description').val(),
            price: $('#price').val(),   
            category_id: $('#category_id').val()
        };

        $.ajax({
            url: `/api/product/${productId}`,
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            success: function(response) {
                Swal.fire('Thành công!', 'Cập nhật thành công', 'success')
                    .then(() => window.location = '/Product');
            },
            error: function(xhr) {
                Swal.fire('Lỗi!', xhr.responseJSON?.message || 'Cập nhật thất bại', 'error');
            }
        });
    });
});
</script>
