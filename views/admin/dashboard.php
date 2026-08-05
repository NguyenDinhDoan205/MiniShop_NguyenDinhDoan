<?php
require_once "../../dao/CategoryDAO.php";
require_once "../../dao/BrandDAO.php";
require_once "../../dao/ProductDAO.php";
require_once "../../dao/CustomerDAO.php";
require_once "../../dao/OrderDAO.php";

$pageTitle = "Dashboard";

$categoryDAO = new CategoryDAO();
$brandDAO = new BrandDAO();
$productDAO = new ProductDAO();
$customerDAO = new CustomerDAO();
$orderDAO = new OrderDAO();

$totalCategory = $categoryDAO->count();
$totalBrand = $brandDAO->count();
$totalProduct = $productDAO->count();
$totalCustomer = $customerDAO->count();
$totalOrder = $orderDAO->count();

$latestProducts = $productDAO->latest();
$latestOrders = $orderDAO->latest();

ob_start();
?>

<h2 class="mb-4">Dashboard</h2>

<div class="alert alert-success">
    Chào mừng bạn đến với hệ thống quản trị Mini Shop.
</div>

<div class="row mb-4">
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5>Danh mục</h5>
                <h3><?= $totalCategory ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5>Thương hiệu</h5>
                <h3><?= $totalBrand ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5>Sản phẩm</h5>
                <h3><?= $totalProduct ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5>Khách hàng</h5>
                <h3><?= $totalCustomer ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5>Đơn hàng</h5>
                <h3><?= $totalOrder ?></h3>
            </div>
        </div>
    </div>
</div>

<h4>5 sản phẩm mới nhất</h4>

<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Tên sản phẩm</th>
        <th>Giá</th>
    </tr>

    <?php foreach ($latestProducts as $product): ?>
    <tr>
        <td><?= $product->id ?></td>
        <td><?= $product->proname ?></td>
        <td><?= number_format($product->price) ?> đ</td>
    </tr>
    <?php endforeach; ?>
</table>

<h4>5 đơn hàng mới nhất</h4>

<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Mã đơn</th>
        <th>Tổng tiền</th>
        <th>Trạng thái</th>
    </tr>

    <?php foreach ($latestOrders as $order): ?>
    <tr>
        <td><?= $order->id ?></td>
        <td><?= $order->orderCode ?></td>
        <td><?= number_format($order->totalAmount) ?> đ</td>
        <td><?= $order->status ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php
$content = ob_get_clean();

include "layouts/master.php";