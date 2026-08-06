<?php
require_once "../../dao/CategoryDAO.php";
require_once "../../dao/UserDAO.php";
require_once "../../dao/ProductDAO.php";
require_once "../../dao/CustomerDAO.php";
require_once "../../dao/OrderDAO.php";

$pageTitle = "Dashboard";

$categoryDAO = new CategoryDAO();
$userDAO = new UserDAO();
$productDAO = new ProductDAO();
$customerDAO = new CustomerDAO();
$orderDAO = new OrderDAO();

$totalCategory = $categoryDAO->count();
$totalUser = $userDAO->count();
$totalProduct = $productDAO->count();
$totalCustomer = $customerDAO->count();
$totalOrder = $orderDAO->count();

$latestProducts = $productDAO->latest();
$latestOrders = $orderDAO->latest();

const ORDER_STATUS_MAP = [
    0 => ['label' => 'Chờ xử lý',   'class' => 'bg-warning-subtle text-warning-emphasis'],
    1 => ['label' => 'Đã xác nhận', 'class' => 'bg-success-subtle text-success-emphasis'],
    2 => ['label' => 'Đang giao',   'class' => 'bg-info-subtle text-info-emphasis'],
    3 => ['label' => 'Hoàn thành',  'class' => 'bg-primary-subtle text-primary-emphasis'],
    4 => ['label' => 'Đã hủy',      'class' => 'bg-danger-subtle text-danger-emphasis'],
];

function statusLabel(int $status): string
{
    return ORDER_STATUS_MAP[$status]['label'] ?? 'Không xác định';
}
 
function statusBadgeClass(int $status): string
{
    return ORDER_STATUS_MAP[$status]['class'] ?? 'bg-secondary-subtle text-secondary-emphasis';
}

ob_start();
?>

<h2 class="fw-bold mb-4 ">Dashboard</h2>



<div class="row g-4 mb-4">
     <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0">
            <div class="card-body text-center">
                <i class="bi bi-people-fill fs-1 text-info"></i>
                <h5 class="mt-3">Người dùng</h5>
                <h2><?= $totalUser ?></h2>
            </div>
             <div class="card-footer bg-white border-0 pt-0 text-center">
                <a href="user.php" class="stat-link">Xem chi tiết</a>
            </div>
        </div>
    </div>

   

    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0">
            <div class="card-body text-center">
                <i class="bi bi-bookmark-fill fs-1 text-success"></i>
                <h5 class="mt-3">Sản phẩm</h5>
                <h2><?= $totalProduct ?></h2>
            </div>
             <div class="card-footer bg-white border-0 pt-0 text-center">
                <a href="products.php" class="stat-link">Xem chi tiết</a>
            </div>
            
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0">
            <div class="card-body text-center">
                <i class="bi bi-box-seam fs-1 text-warning"></i>
                <h5 class="mt-3">Đơn hàng</h5>
                <h2><?= $totalOrder ?></h2>
            </div>
             <div class="card-footer bg-white border-0 pt-0 text-center">
                <a href="order.php" class="stat-link">Xem chi tiết</a>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card shadow border-0">
            <div class="card-body text-center">
                <i class="bi bi-grid fs-1 text-primary"></i>
                <h5 class="mt-3">Khách hàng</h5>
                <h2><?= $totalCustomer ?></h2>
                
            </div>
            <div class="card-footer bg-white border-0 pt-0 text-center">
                <a href="customer.php" class="stat-link">Xem chi tiết</a>
            </div>
        </div>
         
    </div>

</div>

<div class="row mb-5">

    <div class="col-md-6">

        <div class="card shadow">

            <div class="card-header bg-primary text-white">
                5 sản phẩm mới nhất
            </div>

            <table class="table table-hover mb-0">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Giá</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach($latestProducts as $product): ?>

                    <tr>
                        <td><?= $product->id ?></td>
                        <td><?= $product->proname ?></td>
                        <td><?= number_format($product->price) ?> đ</td>
                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

    <div class="col-md-6">

        <div class="card shadow">

            <div class="card-header bg-success text-white">
                5 đơn hàng mới nhất
            </div>

            <table class="table table-hover mb-0">

               <thead>
                    <tr class="text-muted small text-uppercase">
                        <th>#</th>
                        <th>Mã đơn</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
 
                <tbody>
 
                <?php foreach ($latestOrders as $order): ?>
 
                    <tr>
                        <td><?= $order->id ?></td>
                        <td class="fw-semibold"><?= $order->orderCode ?></td>
                        <td><?= number_format($order->totalAmount) ?> đ</td>
                        <td>
                            <span class="badge <?= statusBadgeClass($order->status) ?>">
                                <?= statusLabel($order->status) ?>
                            </span>
                        </td>
                    </tr>
 
                <?php endforeach; ?>
 
                <?php if (empty($latestOrders)): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Chưa có đơn hàng nào.</td>
                    </tr>
                <?php endif; ?>
 
                </tbody>

            </table>

        </div>

    </div>

</div>

<div class="card shadow border-0">

    <div class="card-body text-center">

        <h4>Tổng đơn hàng</h4>

        <h1 class="text-danger fw-bold">
            <?= $totalOrder ?>
        </h1>

    </div>

</div>

<?php
$content = ob_get_clean();

include "layouts/master.php";
