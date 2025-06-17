<?php include 'app/views/shares/header.php'; ?>

<!-- ✅ Banner -->
<div class="banner">
  <div class="container">
    <h1>Khám phá các sản phẩm mới nhất!</h1>
    <p>Đa dạng mẫu mã – Giá cả hợp lý – Giao hàng toàn quốc</p>
  </div>
</div>

<div class="container mt-4" style="max-width: 900px;">
    <div class="text-center mb-4">
        <h1 class="d-inline-block px-4 py-2 border border-2 rounded text-dark" style="background-color: #f7f1ea; border-color: #B0926A;">
            Danh sách danh mục
        </h1>
    </div>

    <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="/webbanhang/category/add" class="btn btn-brand mb-3">
            + Thêm danh mục
        </a>
    <?php endif; ?>

    <table class="table table-bordered table-hover">
        <thead style="background-color: #B0926A; color: white;">
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Mô tả</th>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <th>Hành động</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($category as $cat): ?>
                <tr>
                    <td><?= $cat->id ?></td>
                    <td><?= htmlspecialchars($cat->name) ?></td>
                    <td><?= htmlspecialchars($cat->description) ?></td>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <td>
                            <a href="/webbanhang/category/edit/<?= $cat->id ?>" class="btn btn-edit">Sửa</a>
                            <a href="/webbanhang/category/delete/<?= $cat->id ?>" class="btn btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<style>
  .btn-brand {
    background-color: #B0926A;
    color: #fff;
    font-weight: bold;
    border: none;
    padding: 8px 16px;
    border-radius: 10px;
  }

  .btn-brand:hover {
    background-color: #967a50;
    color: #fff;
  }

  .btn-edit {
    background-color: #d4b28d;
    color: #3e2d1c;
    font-weight: 500;
    padding: 6px 14px;
    border-radius: 10px;
    border: none;
    transition: all 0.2s ease;
  }

  .btn-edit:hover {
    background-color: #b9966b;
    color: #fff;
  }

  .btn-delete {
    background-color: #c67e63;
    color: #fff;
    font-weight: 500;
    padding: 6px 14px;
    border-radius: 10px;
    border: none;
    transition: all 0.2s ease;
  }

  .btn-delete:hover {
    background-color: #a8634a;
    color: #fff;
  }

  .table td, .table th {
    vertical-align: middle;
  }

  .table-hover tbody tr:hover {
    background-color: #f2e8dc;
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

</style>
