<?php include 'app/views/shares/header.php'; ?>
<link rel="stylesheet" href="/public/css/order-confirm.css">

<style>
    .content-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 40px 20px;
        font-family: Arial, sans-serif;
        text-align: center;
    }

    .thank-you-image img {
        max-width: 200px;
        margin-bottom: 20px;
    }

    .header-section h1 {
        font-size: 28px;
        margin-bottom: 10px;
    }

    .thank-you-message {
        font-size: 16px;
        color: #444;
    }

    .order-info, .customer-info, .order-details {
        text-align: left;
        margin-top: 30px;
    }

    .order-info p, .customer-info p {
        margin: 5px 0;
        font-size: 15px;
    }

    .order-details table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .order-details th, .order-details td {
        padding: 10px;
        border: 1px solid #ccc;
        text-align: center;
        font-size: 14px;
    }

    .timeline-container {
        margin-top: 40px;
    }

    .timeline-container h3 {
        margin-bottom: 20px;
    }

    .timeline {
        list-style: none;
        display: flex;
        justify-content: space-between;
        padding: 0;
        position: relative;
    }

    /* Dòng nối nền xám + gradient cho đoạn completed màu xanh */
    .timeline::before {
        content: '';
        position: absolute;
        top: 18px;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(to right, #00BFA5 0%, #00BFA5 40%, /* 40% tương ứng 2/5 bước completed */#ccc 40%, #ccc 100%);
        z-index: 0;
        border-radius: 2px;
    }

    .timeline li {
        position: relative;
        z-index: 1;
        background: #ccc;
        color: white;
        width: 36px;
        height: 36px;
        line-height: 36px;
        border-radius: 50%;
        display: inline-block;
        font-size: 12px;
    }

    .timeline li.completed {
        background-color: #00BFA5;
    }

    .timeline li::after {
        content: attr(data-label);
        position: absolute;
        top: 50px;
        left: 50%;
        transform: translateX(-50%);
        color: #333;
        font-size: 13px;
        white-space: nowrap;
    }

    .action-buttons {
        margin-top: 60px;
        text-align: center;
    }

    .btn {
        display: inline-block;
        padding: 10px 20px;
        border-radius: 4px;
        text-decoration: none;
        margin: 0 10px;
        font-size: 14px;
    }

    .btn-primary {
        background-color: #00BFA5;
        color: white;
    }

    .btn-secondary {
        background-color: #ccc;
        color: black;
    }

    .order-total {
        font-weight: bold;
        font-size: 16px;
        text-align: right;
        padding-right: 10px;
    }
</style>

<div class="content-container">

    <div class="thank-you-image">
        <img src="/webbanhang/public/images/thank-you.png" alt="Cảm ơn bạn">
    </div>

    <div class="header-section">
        <h1>Xác nhận đơn hàng</h1>
        <p class="thank-you-message">Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đã được xử lý thành công.</p>
    </div>

    <div class="order-info">
        <p><strong>Mã đơn hàng:</strong> #<?php echo $orderId ?? 'N/A'; ?></p>
        <p><strong>Thời gian đặt hàng:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>
        <p><strong>Dự kiến giao hàng:</strong> <?php echo date('d/m/Y', strtotime('+2 days')); ?></p>
    </div>

    <div class="customer-info">
        <h3>Thông tin khách hàng</h3>
        <p><strong>Họ tên:</strong> <?php echo $customer['name'] ?? 'Không xác định'; ?></p>
        <p><strong>Email:</strong> <?php echo $customer['email'] ?? 'Không xác định'; ?></p>
        <p><strong>Số điện thoại:</strong> <?php echo $customer['phone'] ?? 'Không xác định'; ?></p>
        <p><strong>Địa chỉ giao hàng:</strong> <?php echo $customer['address'] ?? 'Không xác định'; ?></p>
    </div>

    <div class="order-details">
        <h3>Chi tiết sản phẩm</h3>
        <table class="order-table">
            <thead>
                <tr>
                    <!-- Bỏ cột hình ảnh -->
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $tongTienDonHang = 0;
                    foreach ($orderDetails as $item):
                        $tongTien = $item['price'] * $item['quantity'];
                        $tongTienDonHang += $tongTien;
                ?>
                <tr>
                    <!-- Bỏ ô hình ảnh -->
                    <td><?php echo $item['product_name']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo number_format($item['price'], 0, ',', '.') . ' đ'; ?></td>
                    <td><?php echo number_format($tongTien, 0, ',', '.') . ' đ'; ?></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" class="order-total">Tổng tiền đơn hàng</td>
                    <td class="order-total"><?php echo number_format($tongTienDonHang, 0, ',', '.') . ' đ'; ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="timeline-container">
        <h3>Tiến trình đơn hàng</h3>
        <ul class="timeline">
            <li class="completed" data-label="Xác nhận đơn"></li>
            <li class="completed" data-label="Chuẩn bị đơn"></li>
            <li data-label="Chuyển cho shipper"></li>
            <li data-label="Đang giao hàng"></li>
            <li data-label="Đã giao hàng"></li>
        </ul>
    </div>

    <div class="action-buttons">
        <a href="/webbanhang/Product/index" class="btn btn-primary">Tiếp tục mua sắm</a>
        <a href="/webbanhang/app/views/shares/404error.php" class="btn btn-secondary">Tải hóa đơn</a>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
