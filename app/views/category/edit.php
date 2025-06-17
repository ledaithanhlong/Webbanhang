<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5" style="max-width: 700px;">
    <div class="text-center mb-4">
        <h2 class="px-4 py-2 border border-2 rounded text-dark" style="background-color: white; border-color: #B0926A;">
            Chỉnh sửa danh mục
        </h2>
    </div>

    <form action="/webbanhang/category/update/<?= $category->id ?>" method="post"
          class="p-4 shadow rounded" style="background-color: #ffffff;">
        <div class="mb-3">
            <label for="name" class="form-label fw-bold">Tên danh mục</label>
            <input type="text" class="form-control" id="name" name="name"
                   value="<?= htmlspecialchars($category->name) ?>" required
                   style="border-color: #B0926A;">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label fw-bold">Mô tả</label>
            <textarea class="form-control" id="description" name="description" rows="4"
                      style="border-color: #B0926A;"><?= htmlspecialchars($category->description) ?></textarea>
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-update">Cập nhật</button>
            <a href="/webbanhang/category/list" class="btn btn-cancel">Hủy</a>
        </div>
    </form>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<style>
    .btn-update {
        background-color: #B0926A;
        color: #fff;
        font-weight: bold;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        transition: background-color 0.2s ease;
    }

    .btn-update:hover {
        background-color: #967a50;
        color: #fff;
    }

    .btn-cancel {
        background-color: #e0e0e0;
        color: #333;
        font-weight: 500;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        transition: background-color 0.2s ease;
        text-decoration: none;
    }

    .btn-cancel:hover {
        background-color: #ccc;
        color: #000;
        text-decoration: none;
    }

    label.form-label {
        color: #3e2d1c;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(176, 146, 106, 0.25);
        border-color: #B0926A;
    }
</style>
