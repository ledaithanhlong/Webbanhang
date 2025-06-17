<?php include 'app/views/shares/header.php'; ?>

<section class="vh-100" style="background-color: #fdf6ee;">
  <div class="container h-100 d-flex justify-content-center align-items-center">
    <div class="col-lg-7 col-md-9">
      <div class="card shadow-card p-4">
        <h2 class="text-center fw-bold mb-4" style="color: #B0926A;">Thêm danh mục</h2>

        <?php if (!empty($errors)): ?>
          <div class="alert alert-danger">
            <ul class="mb-0">
              <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <form action="/webbanhang/category/store" method="POST">
          <div class="mb-3">
            <label for="name" class="form-label">Tên danh mục:</label>
            <input type="text" name="name" id="name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Mô tả:</label>
            <textarea name="description" id="description" class="form-control" rows="4"></textarea>
          </div>

          <div class="d-flex justify-content-between mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Thêm danh mục</button>
            <a href="/webbanhang/category/list" class="btn btn-secondary btn-lg">Quay lại danh sách</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php include 'app/views/shares/footer.php'; ?>

<!-- Đảm bảo style giống với trang thêm sản phẩm -->
<style>
  .shadow-card {
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    border-radius: 25px;
    background-color: #ffffff;
  }
</style>
