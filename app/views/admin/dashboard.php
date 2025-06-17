<?php
include 'app/views/shares/header.php';
require_once('app/config/database.php');

$db = new Database();
$conn = $db->getConnection();

// Lấy bộ lọc doanh thu
$filter = $_GET['filter'] ?? 'daily';

// Thống kê tổng
$orderCount = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$userCount = $conn->query("SELECT COUNT(*) FROM account WHERE role = 'user'")->fetchColumn();
$totalRevenue = $conn->query("SELECT SUM(price * quantity) FROM order_details")->fetchColumn();

// Doanh thu theo thời gian
switch ($filter) {
  case 'weekly':
    $revenueData = $conn->query("
      SELECT YEARWEEK(o.created_at, 1) AS period, SUM(od.price * od.quantity) AS revenue
      FROM orders o
      JOIN order_details od ON o.id = od.order_id
      GROUP BY YEARWEEK(o.created_at, 1)
      ORDER BY period
    ")->fetchAll(PDO::FETCH_ASSOC);
    $labels = array_map(fn($r) => 'Tuần ' . substr($r['period'], 4), $revenueData);
    break;

  case 'monthly':
    $revenueData = $conn->query("
      SELECT DATE_FORMAT(o.created_at, '%Y-%m') AS period, SUM(od.price * od.quantity) AS revenue
      FROM orders o
      JOIN order_details od ON o.id = od.order_id
      GROUP BY DATE_FORMAT(o.created_at, '%Y-%m')
      ORDER BY period
    ")->fetchAll(PDO::FETCH_ASSOC);
    $labels = array_column($revenueData, 'period');
    break;

  default:
    $revenueData = $conn->query("
      SELECT DATE(o.created_at) AS period, SUM(od.price * od.quantity) AS revenue
      FROM orders o
      JOIN order_details od ON o.id = od.order_id
      GROUP BY DATE(o.created_at)
      ORDER BY period
    ")->fetchAll(PDO::FETCH_ASSOC);
    $labels = array_column($revenueData, 'period');
    break;
}

// Top sản phẩm
$topProducts = $conn->query("
  SELECT p.name, SUM(od.quantity) AS sold, SUM(od.quantity * od.price) AS total_revenue
  FROM order_details od
  JOIN product p ON od.product_id = p.id
  GROUP BY p.name
  ORDER BY sold DESC
  LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Top loại sản phẩm
$topCategories = $conn->query("
  SELECT c.name AS category_name, SUM(od.quantity) AS total_sold, SUM(od.quantity * od.price) AS total_revenue
  FROM order_details od
  JOIN product p ON od.product_id = p.id
  JOIN category c ON p.category_id = c.id
  GROUP BY c.name
  ORDER BY total_sold DESC
  LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Phân trang lịch sử đơn hàng
$limit = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalOrders = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalPages = ceil($totalOrders / $limit);

$orderHistory = $conn->query("
  SELECT o.id, o.name, o.phone, o.address, o.created_at, SUM(od.quantity * od.price) AS total
  FROM orders o
  JOIN order_details od ON o.id = od.order_id
  GROUP BY o.id
  ORDER BY o.created_at DESC
  LIMIT $limit OFFSET $offset
")->fetchAll(PDO::FETCH_ASSOC);
?>

<section style="background-color: #fdf6ee; min-height: 100vh;">
  <div class="container py-5">

    <div class="text-center mb-5">
      <h2 class="fw-bold" style="color: #B0926A;">Chào mừng, admin <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
      <p class="text-muted">Đây là khu vực quản trị của hệ thống.</p>
    </div>

    <!-- Tổng quan -->
    <div class="row text-center mb-5">
      <?php
      $stats = [
        ['label' => 'Tổng đơn hàng', 'value' => $orderCount],
        ['label' => 'Tổng người dùng', 'value' => $userCount],
        ['label' => 'Tổng doanh thu', 'value' => number_format($totalRevenue, 0, ',', '.') . ' đ']
      ];
      foreach ($stats as $stat):
      ?>
        <div class="col-md-4 mb-3">
          <div class="card shadow-card p-4">
            <h5 class="text-muted"><?= $stat['label'] ?></h5>
            <h3><?= $stat['value'] ?></h3>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Bộ lọc -->
    <form method="GET" class="text-end mb-3">
      <label for="filter">Xem doanh thu theo:</label>
      <select name="filter" id="filter" onchange="this.form.submit()">
        <option value="daily" <?= $filter === 'daily' ? 'selected' : '' ?>>Ngày</option>
        <option value="weekly" <?= $filter === 'weekly' ? 'selected' : '' ?>>Tuần</option>
        <option value="monthly" <?= $filter === 'monthly' ? 'selected' : '' ?>>Tháng</option>
      </select>
    </form>

    <!-- Biểu đồ -->
    <div class="card shadow-card p-4 mb-5">
      <h5 class="text-center mb-4">Biểu đồ doanh thu theo <?= $filter === 'daily' ? 'ngày' : ($filter === 'weekly' ? 'tuần' : 'tháng') ?></h5>
      <canvas id="revenueChart"></canvas>
    </div>

    <!-- Top sản phẩm -->
    <div class="card shadow-card p-4 mb-5">
      <h5 class="text-center mb-4">Top 5 sản phẩm bán chạy</h5>
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-light">
            <tr><th>Tên sản phẩm</th><th>Số lượng bán</th><th>Tổng doanh thu</th></tr>
          </thead>
          <tbody>
            <?php foreach ($topProducts as $p): ?>
              <tr>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td><?= $p['sold'] ?></td>
                <td><?= number_format($p['total_revenue'], 0, ',', '.') ?> đ</td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Top loại sản phẩm -->
    <div class="card shadow-card p-4 mb-5">
      <h5 class="text-center mb-4">Top 5 loại sản phẩm bán chạy</h5>
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-light">
            <tr><th>Loại sản phẩm</th><th>Số lượng bán</th><th>Tổng doanh thu</th></tr>
          </thead>
          <tbody>
            <?php foreach ($topCategories as $cat): ?>
              <tr>
                <td><?= htmlspecialchars($cat['category_name']) ?></td>
                <td><?= $cat['total_sold'] ?></td>
                <td><?= number_format($cat['total_revenue'], 0, ',', '.') ?> đ</td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Lịch sử đơn hàng -->
    <div class="card shadow-card p-4 mb-5">
      <h5 class="text-center mb-4">Lịch sử đơn hàng</h5>
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
    </div>

    <!-- Phân trang -->
    <nav class="mt-4 d-flex justify-content-center">
      <ul class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <li class="page-item <?= $i == $page ? 'active' : '' ?>">
            <a class="page-link" href="?filter=<?= $filter ?>&page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>

  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: <?= json_encode($labels) ?>,
    datasets: [{
      label: 'Doanh thu (VNĐ)',
      data: <?= json_encode(array_column($revenueData, 'revenue')) ?>,
      backgroundColor: '#B0926A',
      borderRadius: 8,
      maxBarThickness: 40
    }]
  },
  options: {
    responsive: true,
    plugins: {
      tooltip: {
        callbacks: {
          label: ctx => ctx.raw.toLocaleString('fr-FR') + ' đ'
        }
      },
      legend: { display: false }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          callback: v => v.toLocaleString('fr-FR') + ' đ'
        }
      },
      x: {
        ticks: {
          maxRotation: 45,
          minRotation: 30,
          autoSkip: true
        }
      }
    }
  }
});
</script>

<?php include 'app/views/shares/footer.php'; ?>

<style>
.shadow-card {
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  border-radius: 25px;
  background-color: #fff;
}
select {
  padding: 6px 10px;
  border-radius: 8px;
  border: 1px solid #ccc;
  margin-left: 5px;
}
.table th, .table td {
  vertical-align: middle;
}
</style>
