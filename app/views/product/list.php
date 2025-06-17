<?php include 'app/views/shares/header.php'; ?>

<style>
  .btn-brand {
    background-color: #B0926A;
    color: white;
    border: none;
    transition: all 0.3s ease;
  }
  .btn-brand:hover {
    background-color: #967a50;
    color: white;
  }
  .btn-edit {
    border: 1px solid #B0926A;
    color: black;
    background-color: #E4D96F;
  }
  .btn-delete {
    border: 1px solid #dc3545;
    color: black;
    background-color: #FF746C;
  }

  .banner {
    background-image: url('/webbanhang/public/images/banner-products.jpg');
    background-size: cover;
    background-position: center;
    padding: 345px 0 100px;
    margin-top: -25px;
    text-align: center;
    color: white;
    font-weight: 1000;
    font-size: 60px;
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.9);
    border-bottom: 6px solid #B0926A;
    line-height: 1.2;
  }

  .banner p {
    font-size: 26px;
    font-weight: 600;
    margin-top: 25px;
    line-height: 1.6;
    text-shadow: 1px 1px 6px rgba(0, 0, 0, 0.6);
  }

  .card-title a:hover {
    color: #B0926A;
  }

  .row.product-grid {
    --bs-gutter-x: 1rem;
    --bs-gutter-y: 2rem;
  }

  .card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 20px;
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    overflow: hidden;
  }
  .card:hover {
    transform: translateY(-8px);
    box-shadow: 0 16px 32px rgba(0, 0, 0, 0.15);
  }

  .product-card-img {
    object-fit: cover;
    height: 300px;
    border-top-left-radius: 16px;
    border-top-right-radius: 16px;
  }

  .product-section {
    padding-bottom: 60px;
  }

  .col-sm-6.col-md-4.col-lg-3 {
    margin-bottom: 1.5rem;
  }

  .price-highlight {
    font-size: 1.1rem;
    color: #B0926A;
    font-weight: bold;
  }

  .card.h-100.shadow.w-100 {
    max-width: 95%;
    margin-left: auto;
    margin-right: auto;
  }

  @media (min-width: 992px) {
    .col-lg-3 {
      flex: 0 0 auto;
      width: 25%;
      padding: 0 1rem;
    }
  }

  .w-48 {
    width: 48%;
  }
</style>

<!-- ✅ Banner -->
<div class="banner">
  <div class="container">
    <h1>Khám phá các sản phẩm mới nhất!</h1>
    <p>Đa dạng mẫu mã – Giá cả hợp lý – Giao hàng toàn quốc</p>
  </div>
</div>

<!-- ✅ Danh sách sản phẩm -->
<section class="product-section">
  <div class="container">

    <div class="text-center mb-4">
      <h2 class="d-inline-block px-4 py-2 border border-2 rounded" style="border-color: #B0926A; background: #fff; color: #000;">
      </h2>
    </div>

    <?php if (SessionHelper::isAdmin()): ?>
      <div class="text-center mb-4">
        <a href="/webbanhang/Product/add" class="btn btn-brand btn-lg">Thêm sản phẩm mới</a>
      </div>
    <?php endif; ?>

    <div class="row product-grid">
      <?php foreach ($products as $product): ?>
        <div class="col-sm-6 col-md-4 col-lg-3 d-flex">
          <div class="card h-100 shadow w-100">
            <?php if ($product->image): ?>
              <img src="/webbanhang/<?php echo $product->image; ?>"
                   class="card-img-top product-card-img"
                   alt="Hình ảnh sản phẩm <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
            <?php else: ?>
              <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                   style="height: 200px; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                Không có hình ảnh
              </div>
            <?php endif; ?>

            <div class="card-body d-flex flex-column">
              <h5 class="card-title">
                <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="text-decoration-none text-dark fw-bold">
                  <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                </a>
              </h5>

              <p class="card-text text-truncate" style="flex-grow: 1;">
                <?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?>
              </p>

              <p class="card-text fw-semibold">
                Giá: <span class="price-highlight"><?php echo number_format($product->price, 0, ',', '.'); ?> VND</span>
              </p>

              <p class="card-text"><small class="text-muted">Danh mục: <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?></small></p>

              <!-- ✅ NÚT ADMIN -->
              <?php if (SessionHelper::isAdmin()): ?>
                <div class="d-flex justify-content-between mb-2">
                  <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-edit btn-sm w-48">Sửa</a>
                  <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" class="btn btn-delete btn-sm w-48"
                     onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
                </div>
              <?php endif; ?>

              <!-- ✅ NÚT THÊM VÀO GIỎ -->
              <a href="/webbanhang/Product/addToCart/<?php echo $product->id; ?>" class="btn btn-primary btn-sm w-100 mt-auto">Thêm vào giỏ</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<?php include 'app/views/shares/footer.php'; ?>
