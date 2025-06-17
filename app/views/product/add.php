<?php include 'app/views/shares/header.php'; ?>

<section class="vh-100" style="background-color: #f8f9fa;">
  <div class="container h-100 d-flex justify-content-center align-items-center">
    <div class="col-lg-7 col-md-9">
      <div class="card shadow-card p-4">
        <h2 class="text-center fw-bold mb-4" style="color: #B0926A;">Thêm sản phẩm mới</h2>

        <?php if (!empty($errors)): ?>
          <div class="alert alert-danger">
            <ul class="mb-0">
              <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <form method="POST" action="/webbanhang/Product/save" enctype="multipart/form-data" onsubmit="return validateForm();">
          <div class="mb-3">
            <label for="name" class="form-label">Tên sản phẩm:</label>
            <input type="text" id="name" name="name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Mô tả:</label>
            <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
          </div>

          <div class="mb-3">
            <label for="price" class="form-label">Giá:</label>
            <input type="text" id="price" name="price" class="form-control" required>
          </div>

          <script>
            const priceInput = document.getElementById('price');

            priceInput.addEventListener('input', function (e) {
              // Lấy giá trị, loại bỏ dấu cách cũ
              let value = this.value.replace(/\s+/g, '');

              // Nếu không phải số thì bỏ qua
              if (!/^\d*$/.test(value)) {
                return;
              }

              // Thêm dấu cách sau mỗi 3 chữ số
              this.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            });
          </script>

          <div class="mb-3">
            <label for="category_id" class="form-label">Danh mục:</label>
            <select id="category_id" name="category_id" class="form-select" required>
              <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category->id; ?>">
                  <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-4">
            <label for="image" class="form-label">Hình ảnh:</label>
            <input type="file" id="image" name="image" class="form-control">
          </div>

          <div class="d-flex justify-content-between mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Thêm sản phẩm</button>
            <a href="/webbanhang" class="btn btn-secondary btn-lg">Quay lại danh sách sản phẩm</a>
          </div>

        </form>
      </div>
    </div>
  </div>
</section>

<?php include 'app/views/shares/footer.php'; ?>

<style>
  .shadow-card {
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    border-radius: 25px;
    background-color: #ffffff;
  }
</style>
