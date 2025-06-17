<?php include 'app/views/shares/header.php'; ?>

<style>
  .btn-edit {
    border: 1px solid #B0926A;
    color: black;
    background-color: #E4D96F;
  }
  .btn-delete {
    border: 1px solid #dc3545;
    color: white;
    background-color: #FF5E5E;
  }

  .product-section {
    padding: 60px 0;
    background-color: #fdfaf5;
  }

  .product-grid {
    --bs-gutter-x: 2rem;
    --bs-gutter-y: 2.5rem;
  }

  .card {
    border-radius: 20px;
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background-color: white;
    width: 100%;
    display: flex;
    flex-direction: column;
  }

  .card:hover {
    transform: translateY(-10px);
    box-shadow: 0 16px 32px rgba(0, 0, 0, 0.15);
  }

  .product-card-img {
    object-fit: cover;
    width: 100%;
    aspect-ratio: 1 / 1;
  }

  .card-body {
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
  }

  .card-title a {
    font-size: 1.1rem;
    color: #000;
    transition: color 0.2s;
  }

  .card-title a:hover {
    color: #B0926A;
  }

  .card-text {
    font-size: 0.95rem;
    color: #555;
  }

  .price-highlight {
    font-size: 1rem;
    color: #B0926A;
    font-weight: bold;
  }
</style>

<section class="product-section">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="d-inline-block px-4 py-2 border border-2 rounded" style="border-color: #B0926A; background: #fff; color: #000;">
        Sản phẩm phù hợp
      </h2>
    </div>

    <?php if (!empty($results)): ?>
      <div class="row product-grid justify-content-center">
        <?php foreach ($results as $product): ?>
          <div class="col-sm-10 col-md-6 col-lg-4 d-flex">
            <div class="card h-100">
              <?php if (!empty($product->image)): ?>
                <img src="/webbanhang/<?php echo $product->image; ?>" 
                     class="card-img-top product-card-img" 
                     alt="Ảnh <?php echo htmlspecialchars($product->name); ?>">
              <?php else: ?>
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                     style="aspect-ratio: 1 / 1;">
                  Không có hình ảnh
                </div>
              <?php endif; ?>

              <div class="card-body d-flex flex-column">
                <h5 class="card-title">
                  <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="text-decoration-none fw-bold">
                    <?php echo htmlspecialchars($product->name); ?>
                  </a>
                </h5>

                <p class="card-text text-truncate" style="flex-grow: 1;">
                  <?php echo htmlspecialchars($product->description ?? ''); ?>
                </p>

                <p class="card-text">
                  Giá: <span class="price-highlight"><?php echo number_format($product->price, 0, ',', '.'); ?> VND</span>
                </p>

                <div class="mt-auto">
                  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <a href="/webbanhang/Product/addToCart/<?php echo $product->id; ?>" class="btn btn-primary btn-sm flex-fill">Thêm vào giỏ</a>
                    <?php if (SessionHelper::isAdmin()): ?>
                      <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-edit btn-sm flex-fill">Sửa</a>
                      <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" class="btn btn-delete btn-sm flex-fill"
                         onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="text-center fs-5">Không tìm thấy sản phẩm phù hợp.</p>
    <?php endif; ?>
  </div>
</section>

<?php include 'app/views/shares/footer.php'; ?>
