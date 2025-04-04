<?php include 'app/views/shares/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Danh sách sản phẩm</h1>
        <a href="/Product/add" class="btn btn-success">Thêm sản phẩm mới</a>
    </div>
    
    <!-- Search Box -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" id="search-input" class="form-control" placeholder="Tìm kiếm sản phẩm...">
                <button class="btn btn-outline-secondary" type="button" id="search-button">
                    <i class="bi bi-search"></i> Tìm kiếm
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loading" class="text-center my-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Đang tải...</span>
        </div>
        <p class="mt-2">Đang tải danh sách sản phẩm...</p>
    </div>

    <!-- Product Grid -->
    <div class="row row-cols-1 row-cols-md-3 g-4" id="product-list">
        <!-- Products will be loaded here -->
    </div>

    <!-- No Products Message -->
    <div id="no-products" class="alert alert-info text-center my-5 d-none">
        Không có sản phẩm nào. Hãy thêm sản phẩm mới!
    </div>

    <!-- Error Message -->
    <div id="error-message" class="alert alert-danger text-center my-5 d-none">
        Không thể tải danh sách sản phẩm. Vui lòng thử lại sau.
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const token = localStorage.getItem('jwtToken');
        const productList = document.getElementById('product-list');
        const loading = document.getElementById('loading');
        const noProducts = document.getElementById('no-products');
        const errorMessage = document.getElementById('error-message');
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');

        // Check authentication
        if (!token) {
            alert('Vui lòng đăng nhập');
            location.href = '/account/login';
            return;
        }

        // Load products
        loadProducts();

        // Search functionality
        searchButton.addEventListener('click', function() {
            loadProducts(searchInput.value);
        });

        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                loadProducts(searchInput.value);
            }
        });

        // Load products function
        function loadProducts(searchTerm = '') {
            // Show loading, hide other elements
            loading.classList.remove('d-none');
            productList.innerHTML = '';
            noProducts.classList.add('d-none');
            errorMessage.classList.add('d-none');

            // API endpoint
            let url = '/api/product';
            if (searchTerm) {
                url = `/api/product?search=${encodeURIComponent(searchTerm)}`;
            }

            // Fetch products
            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                loading.classList.add('d-none');
                
                if (data.length === 0) {
                    noProducts.classList.remove('d-none');
                    return;
                }
                
                // Display products
                data.forEach(product => {
                    const productCard = document.createElement('div');
                    productCard.className = 'col';
                    productCard.innerHTML = `
                        <div class="card h-100 shadow-sm">
                            ${product.image 
                                ? `<img src="${product.image}" class="card-img-top" alt="${product.name}" style="height: 200px; object-fit: cover;">` 
                                : `<div class="bg-light text-center py-5"><i class="bi bi-image text-secondary" style="font-size: 3rem;"></i></div>`
                            }
                            <div class="card-body">
                                <h5 class="card-title"><a href="/Product/show/${product.id}" class="text-decoration-none">${product.name}</a></h5>
                                <p class="card-text text-truncate">${product.description}</p>
                                <p class="card-text"><span class="badge bg-primary">${product.category_name || 'Không có danh mục'}</span></p>
                                <p class="card-text text-danger fw-bold">${formatCurrency(product.price)} VND</p>
                            </div>
                            <div class="card-footer bg-white d-flex justify-content-between">
                                <a href="/Product/edit/${product.id}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="deleteProduct(${product.id})">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </div>
                        </div>
                    `;
                    productList.appendChild(productCard);
                });
            })
            .catch(error => {
                console.error('Lỗi khi tải danh sách sản phẩm:', error);
                loading.classList.add('d-none');
                errorMessage.classList.remove('d-none');
            });
        }

        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount);
        }
    });

    // Delete product function
    function deleteProduct(id) {
        if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
            const token = localStorage.getItem('jwtToken');
            
            fetch(`/api/product/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message === 'Product deleted successfully') {
                    // Show success message
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show';
                    alert.innerHTML = `
                        Sản phẩm đã được xóa thành công!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    document.querySelector('.container').prepend(alert);
                    
                    // Remove the product card
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    alert('Xóa sản phẩm thất bại');
                }
            })
            .catch(error => {
                console.error('Lỗi khi xóa sản phẩm:', error);
                alert('Không thể xóa sản phẩm. Vui lòng thử lại sau.');
            });
        }
    }
</script>