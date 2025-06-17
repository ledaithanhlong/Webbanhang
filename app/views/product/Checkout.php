<?php include 'app/views/shares/header.php'; ?>

<?php
$cart = $cart ?? [];

// Lấy danh sách tỉnh thành và huyện xã
$provinces = json_decode(file_get_contents('https://provinces.open-api.vn/api/?depth=3'), true);

// Giả lập mảng tỉnh
// $provinces = [
//     ['name' => 'Bình Dương', 'code' => '74'],
//     ['name' => 'Bắc Ninh', 'code' => '27'],
//     ['name' => 'Cà Mau', 'code' => '97'],
//     ['name' => 'Đà Nẵng', 'code' => '48'],
//     ['name' => 'Thành phố Hồ Chí Minh', 'code' => '79'],
//     ['name' => 'Thành phố Hà Nội', 'code' => '01'],
//     ['name' => 'An Giang', 'code' => '89'],
// ];

// Đặt ưu tiên 2 thành phố
$priorityNames = ['Thành phố Hồ Chí Minh', 'Thành phố Hà Nội'];
$priorityProvinces = [];
$otherProvinces = [];

foreach ($provinces as $province) {
    if (in_array($province['name'], $priorityNames)) {
        $priorityProvinces[] = $province;
    } else {
        $otherProvinces[] = $province;
    }
}

// Sắp xếp tên tỉnh/thành còn lại theo natural order (theo tên)
// Sử dụng collator để sắp xếp alphabet chuẩn tiếng Việt
$collator = new Collator('vi_VN');
usort($otherProvinces, function ($a, $b) use ($collator) {
    return $collator->compare($a['name'], $b['name']);
});

$provinces = array_merge($priorityProvinces, $otherProvinces);
?>

<style>
  body { background-color: #fdf6ee; }
  .checkout-container {
    max-width: 1000px;
    margin: 40px auto;
    padding: 0 15px;
  }
  h1.title {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 30px;
    text-align: center;
    position: relative;
  }
  h1.title::after {
    content: "";
    display: block;
    width: 80px;
    height: 4px;
    background: #B0926A;
    margin: 8px auto 0;
    border-radius: 2px;
  }
  .bg-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    padding: 25px;
    margin-bottom: 30px;
  }
  .bg-card h5 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #3e3e3e;
    margin-bottom: 20px;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
  }
  .form-group label { font-weight: 500; color: #555; }
  .form-control {
    border-radius: 6px;
    padding: 10px 12px;
    font-size: 0.95rem;
  }
  .form-control:focus {
    border-color: #B0926A;
    box-shadow: 0 0 0 0.1rem rgba(176,146,106,0.2);
  }
  .btn-success {
    background-color: #B0926A;
    border-color: #B0926A;
    font-weight: 600;
    padding: 12px 30px;
    border-radius: 8px;
  }
  .btn-success:hover {
    background-color: #967a50;
    border-color: #967a50;
  }
  .btn-outline-secondary {
    padding: 12px 30px;
    border-radius: 8px;
  }

  .card-order {
    display: flex;
    align-items: center;
    padding: 16px 18px;
    background: #fff;
    border-radius: 10px;
    border: 1px solid #e6e6e6;
    margin-bottom: 16px;
  }

  .card-order img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid #ddd;
  }

  .card-order .product-info {
    flex: 1;
    padding-left: 20px;
  }

  .card-order .product-info .name {
    font-weight: 600;
    color: #333;
    font-size: 1.05rem;
  }

  .card-order .product-info .detail {
    font-size: 0.95rem;
    color: #777;
    margin-top: 6px;
  }

  .card-order .price {
    white-space: nowrap;
    font-weight: 700;
    font-size: 1rem;
    color: #B0926A;
  }

  .order-summary h5 {
    font-size: 1.1rem;
    color: #333;
    font-weight: 600;
  }
  .order-total {
    background: #faf7f2;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
    margin-top: 20px;
    font-size: 1.15rem;
    font-weight: 700;
    color: #333;
  }
  .order-total span {
    color: #B0926A;
  }
  select.form-control {
    height: auto;
    min-height: 42px;
    font-size: 1rem;
    padding: 8px 12px;
    color: #333;
  }
  .row .col-12 select.form-control {
    width: 100%;
  }
  #bankTransferInfo {
    border-left: 4px solid #B0926A;
    margin-top: 20px;
  }
  #bankTransferInfo p {
    margin-bottom: 5px;
    word-wrap: break-word;
  }
</style>

<div class="checkout-container">
  <h1 class="title">Thanh toán</h1>

  <form method="POST" action="/webbanhang/Product/processCheckout">
    <div class="row gx-4">
      <!-- Thông tin khách hàng -->
      <div class="col-md-6">
        <div class="bg-card">
          <h5>Thông tin thanh toán</h5>
          <div class="form-group mb-3">
            <label for="customerName">Họ tên</label>
            <input type="text" id="customerName" name="name" class="form-control" required>
          </div>
          <div class="form-group mb-3">
            <label for="phone">Số điện thoại</label>
            <input type="text" id="phone" name="phone" class="form-control" required>
          </div>
          <div class="form-group mb-3">
            <label for="email">Email (không bắt buộc)</label>
            <input type="email" id="email" name="email" class="form-control">
          </div>
          <div class="form-group mb-3">
            <label for="address">Địa chỉ chi tiết</label>
            <textarea id="address" name="address" class="form-control" rows="2" required></textarea>
          </div>

          <!-- Tỉnh/Thành phố -->
          <div class="row mb-3">
            <div class="col-12">
              <label for="province">Tỉnh/Thành phố</label>
              <select id="province" name="province" class="form-control" required>
                <option value="">-- Chọn --</option>
                <?php foreach ($provinces as $p): ?>
                  <option value="<?= $p['code'] ?>" data-districts='<?= json_encode($p['districts']) ?>'>
                    <?= htmlspecialchars($p['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <!-- Quận/Huyện + Phường/Xã -->
          <div class="row gx-2 mb-3">
            <div class="col-12 col-md-6">
              <label for="district">Quận/Huyện</label>
              <select id="district" name="district" class="form-control" required>
                <option value="">-- Chọn --</option>
              </select>
            </div>
            <div class="col-12 col-md-6">
              <label for="ward">Phường/Xã</label>
              <select id="ward" name="ward" class="form-control" required>
                <option value="">-- Chọn --</option>
              </select>
            </div>
          </div>

          <!-- Phương thức thanh toán -->
          <div class="form-group mb-3">
            <label>Phương thức thanh toán</label><br>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
              <label class="form-check-label" for="cod">Tiền mặt khi nhận hàng</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
              <label class="form-check-label" for="bank_transfer">Chuyển khoản ngân hàng</label>
            </div>
          </div>
        </div>
      </div>

      <!-- Chi tiết đơn hàng -->
      <div class="col-md-6">
        <div class="bg-card order-summary">
          <h5 class="text-center">Chi tiết đơn hàng</h5>
          <ul class="list-unstyled">
            <?php 
              $totalPrice = 0;
              foreach ($cart as $id => $item):
                $itemTotal = $item['price'] * $item['quantity'];
                $totalPrice += $itemTotal;
            ?>
            <li>
              <div class="card-order">
                <img src="/webbanhang/<?= htmlspecialchars($item['image'] ?? '') ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                <div class="product-info">
                  <div class="name"><?= htmlspecialchars($item['name']) ?></div>
                  <div class="detail"><?= $item['quantity'] ?> x <?= number_format($item['price'], 0, ',', '.') ?>đ</div>
                </div>
                <div class="price"><?= number_format($itemTotal, 0, ',', '.') ?>đ</div>
              </div>
            </li>
            <?php endforeach; ?>
          </ul>
          <div class="order-total">
            Tổng cộng: <span><?= number_format($totalPrice,0,',','.') ?>đ</span>
          </div>

          <!-- Chuyển khoản -->
          <div id="bankTransferInfo" style="display:none;" class="bg-light p-3 mt-4 rounded border">
            <h6><i class="fas fa-university"></i> Thông tin chuyển khoản</h6>
            <p><strong>Mã QR:</strong></p>
            <img src="/webbanhang/public/images/qr-code.png" alt="Mã QR chuyển khoản" style="max-width: 200px; margin-top: 10px;">
            <p><strong>Ngân hàng:</strong> Vietcombank</p>
            <p><strong>Số tài khoản:</strong> 1027239741</p>
            <p><strong>Chủ tài khoản:</strong> Lê Đại Thanh Long</p>
            <p id="transferNote"><strong>Nội dung CK:</strong> Thanh toan hoa don - [Tên khách hàng]</p>
          </div>

          <div class="text-center mt-4">
            <button type="submit" class="btn btn-success">Đặt hàng</button>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const province = document.getElementById('province');
  const district = document.getElementById('district');
  const ward = document.getElementById('ward');

  // Hàm sắp xếp quận/huyện theo số trước, rồi alphabet
  function naturalSort(a, b) {
    const nameA = a.name.trim();
    const nameB = b.name.trim();

    // Tìm số đầu tiên trong tên (nếu có)
    const numA = parseInt(nameA.match(/\d+/)?.[0]);
    const numB = parseInt(nameB.match(/\d+/)?.[0]);

    // Kiểm tra có bắt đầu với số không
    const hasNumA = /^\D*\d+/.test(nameA);
    const hasNumB = /^\D*\d+/.test(nameB);

    if (hasNumA && hasNumB) {
      // Cả 2 đều có số → so sánh số
      return numA - numB;
    }
    if (hasNumA && !hasNumB) return -1;  // A có số đứng trước
    if (!hasNumA && hasNumB) return 1;   // B có số đứng trước

    // Cả hai không có số → so sánh tên alphabet
    return nameA.localeCompare(nameB, 'vi', { sensitivity: 'base' });
  }

  province.addEventListener('change', function () {
    district.innerHTML = '<option value="">-- Chọn --</option>';
    ward.innerHTML = '<option value="">-- Chọn --</option>';

    const districts = JSON.parse(this.selectedOptions[0].dataset.districts || '[]');
    districts.sort(naturalSort);
    districts.forEach(d => {
      const opt = document.createElement('option');
      opt.value = d.code;
      opt.textContent = d.name;
      opt.dataset.wards = JSON.stringify(d.wards || []);
      district.appendChild(opt);
    });
  });

  district.addEventListener('change', function () {
    ward.innerHTML = '<option value="">-- Chọn --</option>';
    const wards = JSON.parse(this.selectedOptions[0].dataset.wards || '[]');
    wards.sort(naturalSort);
    wards.forEach(w => {
      const opt = document.createElement('option');
      opt.value = w.code;
      opt.textContent = w.name;
      ward.appendChild(opt);
    });
  });

  // Hiển thị thông tin chuyển khoản khi chọn phương thức
  const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
  const bankInfo = document.getElementById('bankTransferInfo');
  const nameInput = document.getElementById('customerName');
  const transferNote = document.getElementById('transferNote');

  paymentRadios.forEach(r => {
    r.addEventListener('change', () => {
      bankInfo.style.display = document.getElementById('bank_transfer').checked ? 'block' : 'none';
    });
  });

  // Cập nhật nội dung chuyển khoản theo tên khách hàng
  nameInput.addEventListener('input', function () {
    transferNote.innerHTML = `<strong>Nội dung CK:</strong> Thanh toan don hang ${this.value}`;
  });
});
</script>

<?php include 'app/views/shares/footer.php'; ?>
