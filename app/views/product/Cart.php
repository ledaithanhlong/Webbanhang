<?php include 'app/views/shares/header.php'; ?>

<section style="background-color: #fdf6ee; padding: 60px 0;">
  <div class="container">
    <div class="col-lg-10 col-md-11 mx-auto">
      <div class="card shadow-card p-4">
        <h2 class="text-center fw-bold mb-4" style="color: #B0926A;">Giỏ hàng</h2>

        <?php if (!empty($cart)): ?>
          <ul class="list-unstyled">
            <?php 
              $totalPrice = 0;
              foreach ($cart as $id => $item): 
                $itemTotal = $item['price'] * $item['quantity'];
                $totalPrice += $itemTotal;
            ?>
              <li class="mb-4">
                <div class="card shadow-sm rounded product-card">
                  <div class="row g-0 align-items-center">
                    <div class="col-md-3 text-center p-3">
                      <?php if ($item['image']): ?>
                        <img src="/webbanhang/<?php echo $item['image']; ?>" alt="Product Image" class="img-fluid rounded product-img">
                      <?php else: ?>
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" style="height: 120px;">
                          Không có ảnh
                        </div>
                      <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                      <div class="card-body">
                        <h5 class="card-title mb-2 fw-semibold"><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h5>
                        <p class="mb-1 text-muted">Giá: <strong><?php echo number_format($item['price'], 0, ',', '.'); ?> VND</strong></p>

                        <form method="POST" action="/webbanhang/Product/updateCartQuantity/<?php echo $id; ?>" class="d-inline-flex align-items-center quantity-form">
                          <button type="submit" name="action" value="decrease" class="btn btn-outline-secondary btn-sm">−</button>
                          <span class="mx-3 quantity-text"><?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?></span>
                          <button type="submit" name="action" value="increase" class="btn btn-outline-secondary btn-sm">+</button>
                        </form>

                        <p class="mt-2 mb-0">Tổng: <span class="text-success fw-bold"><?php echo number_format($itemTotal, 0, ',', '.'); ?> VND</span></p>
                      </div>
                    </div>
                    <div class="col-md-3 d-none d-md-block">
                      <div class="color-options">
                        <p class="mb-1 fw-semibold">Chọn màu sắc:</p>
                        <label class="color-option">
                          <input type="radio" name="color_<?php echo $id; ?>" value="#6D92D0">
                          <span class="color-swatch" style="background-color: #6D92D0;"></span>
                          <span class="color-label">Xanh Navy</span>
                        </label>
                        <label class="color-option">
                          <input type="radio" name="color_<?php echo $id; ?>" value="#E5C1A7">
                          <span class="color-swatch" style="background-color: #E5C1A7;"></span>
                          <span class="color-label">Be Hồng</span>
                        </label>
                        <label class="color-option">
                          <input type="radio" name="color_<?php echo $id; ?>" value="#FFFFFF">
                          <span class="color-swatch" style="background-color: #FFFFFF;"></span>
                          <span class="color-label">Trắng</span>
                        </label>
                        <label class="color-option">
                          <input type="radio" name="color_<?php echo $id; ?>" value="#000000">
                          <span class="color-swatch" style="background-color: #000000;"></span>
                          <span class="color-label">Đen</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>

          <div class="text-center my-4">
            <h4 class="d-inline-block px-4 py-2 border border-warning rounded bg-white text-dark">
              Tổng tiền: <span class="text-success fw-bold"><?php echo number_format($totalPrice, 0, ',', '.'); ?> VND</span>
            </h4>
          </div>

          <div class="d-flex flex-wrap justify-content-center gap-4 mb-4">
            <a href="/webbanhang/Product" class="btn btn-outline-secondary px-4 py-2 fw-semibold">Tiếp tục mua sắm</a>
            <a href="/webbanhang/Product/checkout" class="btn btn-brand px-4 py-2">Thanh toán</a>
          </div>

          <div class="text-center mt-2">
            <form method="POST" action="/webbanhang/Product/clearCart" onsubmit="return confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?');">
              <button type="submit" class="btn btn-logout px-4 py-2">Xóa toàn bộ</button>
            </form>
          </div>

        <?php else: ?>
          <p class="text-center fst-italic text-muted">Giỏ hàng của bạn đang trống.</p>
        <?php endif; ?>
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

  .product-card {
    transition: all 0.3s ease;
  }

  .product-card:hover {
    box-shadow: 0 0 15px rgba(176, 146, 106, 0.3);
  }

  .product-img {
    max-height: 130px;
    object-fit: contain;
  }

  .btn-brand {
    background-color: #B0926A;
    color: #fff;
    font-weight: bold;
    border: none;
  }

  .btn-brand:hover {
    background-color: #967a50;
    color: #fff;
  }

  .btn-logout {
    background-color: #a26757;
    color: #fff;
    font-weight: bold;
    border: none;
  }

  .btn-logout:hover {
    background-color: #874e43;
    color: #fff;
  }

  .quantity-form {
    margin-top: 10px;
  }

  .quantity-text {
    min-width: 30px;
    text-align: center;
    font-weight: bold;
  }

  @media (max-width: 576px) {
    .quantity-form {
      flex-direction: column;
      gap: 6px;
    }
    .quantity-text {
      margin: 4px 0;
    }
  }

  .color-options {
    background-color: #fdf6ee; 
    padding: 10px 12px;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .color-option {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    cursor: pointer;
  }

  .color-swatch {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #ccc;
    display: inline-block;
    transition: transform 0.2s ease;
  }

  .color-option:hover .color-swatch {
    transform: scale(1.1);
    border-color: #B0926A;
  }

  .color-option input[type="radio"]:checked + .color-swatch {
    border-color: #333;
  }
</style>
