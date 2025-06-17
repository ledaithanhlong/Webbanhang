<?php include 'app/views/shares/header.php'; ?>

<div class="container my-4" style="max-width: 700px;">
    <div class="card shadow-sm p-4">
        <h2 class="mb-4 text-center" style="color: #B0926A; border-bottom: 2px solid #B0926A; padding-bottom: 10px;">
            Sửa sản phẩm
        </h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="/webbanhang/Product/update" enctype="multipart/form-data" onsubmit="return validateForm();">
            <input type="hidden" name="id" value="<?php echo $product->id; ?>">

            <div class="form-group mb-3">
                <label for="name">Tên sản phẩm:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="description">Mô tả:</label>
                <textarea id="description" name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="price">Giá:</label>
                <input type="text" id="price" name="price" class="form-control" value="<?php echo number_format($product->price, 0, '', ' '); ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="category_id">Danh mục:</label>
                <select id="category_id" name="category_id" class="form-control" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category->id; ?>" <?php echo $category->id == $product->category_id ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group mb-4">
                <label for="image">Hình ảnh:</label>
                <input type="file" id="image" name="image" class="form-control">
                <input type="hidden" name="existing_image" value="<?php echo $product->image; ?>">
                <?php if ($product->image): ?>
                    <div class="mt-3">
                        <img src="/webbanhang/<?php echo $product->image; ?>" alt="Product Image" class="img-thumbnail" style="max-width: 150px;">
                    </div>
                <?php endif; ?>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary px-5">Lưu thay đổi</button>
                <a href="/webbanhang" class="btn btn-secondary ml-3">Quay lại danh sách sản phẩm</a>
            </div>
        </form>
    </div>
</div>

<script>
function formatNumber(num) {
  num = num.toString().replace(/\D/g, ''); // bỏ hết ký tự không phải số
  return num.replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
}

function unformatNumber(str) {
  return str.replace(/\s/g, '');
}

document.addEventListener("DOMContentLoaded", function () {
  const priceInput = document.getElementById('price');

  // Format giá trị ban đầu nếu có
  if (priceInput.value) {
    priceInput.value = formatNumber(priceInput.value);
  }

  // Format khi người dùng nhập
  priceInput.addEventListener('input', function () {
    const raw = unformatNumber(priceInput.value);
    priceInput.value = formatNumber(raw);
  });

  // Trước khi submit thì bỏ định dạng
  const form = priceInput.closest('form');
  form.addEventListener('submit', function () {
    priceInput.value = unformatNumber(priceInput.value);
  });
});
</script>


<?php include 'app/views/shares/footer.php'; ?>
