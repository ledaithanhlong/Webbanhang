<?php
require_once('app/config/database.php');
$db = new Database();
$conn = $db->getConnection();

$filter = $_GET['filter'] ?? 'daily';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$orderHistory = $conn->query("
  SELECT o.id, o.name, o.phone, o.address, o.created_at, SUM(od.quantity * od.price) AS total
  FROM orders o
  JOIN order_details od ON o.id = od.order_id
  GROUP BY o.id
  ORDER BY o.created_at DESC
  LIMIT $limit OFFSET $offset
")->fetchAll(PDO::FETCH_ASSOC);

$totalOrders = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalPages = ceil($totalOrders / $limit);
?>

<!-- Phần bảng đơn hàng -->
<div class="table-responsive">
  <table class="table table-bordered table-striped">
    <thead class="table-light">
      <tr>
        <th>ID</th><th>Khách hàng</th><th>SĐT</th><th>Địa chỉ</th><th>Thời gian</th><th>Tổng tiền</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($orderHistory as $order): ?>
        <tr>
          <td><?= $order['id'] ?></td>
          <td><?= htmlspecialchars($order['name']) ?></td>
          <td><?= htmlspecialchars($order['phone']) ?></td>
          <td><?= htmlspecialchars($order['address']) ?></td>
          <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
          <td><?= number_format($order['total'], 0, ',', '.') ?> đ</td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Phân trang -->
<nav class="mt-4 d-flex justify-content-center">
  <ul class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <li class="page-item <?= $i == $page ? 'active' : '' ?>">
        <a class="page-link page-ajax-link" href="?page=<?= $i ?>&filter=<?= $filter ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>
  </ul>
</nav>
