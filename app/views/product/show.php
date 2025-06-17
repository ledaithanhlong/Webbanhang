<?php include 'app/views/shares/header.php'; ?>

<section class="py-5" style="background-color: #f8f9fa;">
  <div class="container" style="max-width: 800px;">
    <div class="card shadow-card p-4" style="border-radius: 25px;">
      <h2 class="text-center fw-bold mb-4" style="color: #B0926A; border-bottom: 2px solid #B0926A; padding-bottom: 10px;">
        Chi tiết sản phẩm
      </h2>

      <div class="row">
        <!-- Hình ảnh sản phẩm -->
        <div class="col-md-6 mb-4">
          <?php if ($product->image): ?>
            <img src="/webbanhang/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                 class="img-fluid" 
                 alt="Hình ảnh sản phẩm <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" 
                 style="object-fit: cover; border-radius: 15px; max-height: 400px; width: 100%;">
          <?php else: ?>
            <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 400px; border-radius: 15px;">
              Không có hình ảnh
            </div>
          <?php endif; ?>
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-md-6">
          <h3 class="fw-bold"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></h3>
          <p class="text-muted">
            Danh mục: 
            <?php 
              // Kiểm tra xem category_name có tồn tại không
              $category_name = isset($product->category_name) ? htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8') : 'Không có danh mục';
              echo $category_name;
            ?>
          </p>
          <p class="fw-semibold fs-4">Giá: <span class="text-primary"><?php echo number_format($product->price, 0, ',', '.'); ?> VND</span></p>
          <p class="mb-4"><?php echo nl2br(htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8')); ?></p>

          <!-- Nút hành động -->
          <div class="d-flex gap-2">
            <a href="/webbanhang/Product/addToCart/<?php echo $product->id; ?>" class="btn btn-primary">Thêm vào giỏ hàng</a>
            <?php if (SessionHelper::isAdmin()): ?>
              <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning">Sửa</a>
              <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
            <?php endif; ?>
          </div>

          <div class="mt-3">
            <a href="/webbanhang" class="btn btn-secondary">Quay lại danh sách sản phẩm</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'app/views/shares/footer.php'; ?>

<style>
  .shadow-card {
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    background-color: #ffffff;
  }
</style>