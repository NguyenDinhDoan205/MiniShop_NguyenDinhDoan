<?php
require_once "../../../dao/UserDAO.php";

$userDAO = new UserDAO();
$keyword = $_GET["keyword"] ?? "";

if ($keyword != "") {
    $users = array_filter(
        $userDAO->getAll(),
        function ($user) use ($keyword) {

            return stripos($user->fullname, $keyword) !== false
                || stripos($user->username, $keyword) !== false
                || stripos($user->email, $keyword) !== false;
        }
    );

} else {

    $users = $userDAO->getAll();

}


$pageTitle = "Quản lý người dùng";

ob_start();

?>

<div class="card shadow">


    <div class="card-header bg-primary text-white">


        <div class="d-flex justify-content-between align-items-center">


            <h4 class="mb-0">

                <i class="bi bi-people"></i>

                Quản lý người dùng

            </h4>


            <a href="create.php" class="btn btn-light">

                <i class="bi bi-person-plus"></i>

                Thêm user

            </a>


        </div>


    </div>



    <div class="card-body">


        <form method="get" class="row g-2 mb-3">


            <div class="col-md-5">

                <input

                    type="text"

                    name="keyword"

                    class="form-control"

                    placeholder="Nhập tên, username, email..."

                    value="<?= htmlspecialchars($keyword) ?>">


            </div>


            <div class="col-auto">


                <button class="btn btn-primary">

                    <i class="bi bi-search"></i>

                    Tìm kiếm

                </button>


                <a href="index.php" class="btn btn-secondary">

                    <i class="bi bi-arrow-clockwise"></i>

                    Làm mới

                </a>


            </div>


        </form>





        <div class="table-responsive">


            <table class="table table-bordered table-hover align-middle">


                <thead class="table-dark text-center">


                    <tr>

                        <th width="60">STT</th>

                        <th>Họ tên</th>

                        <th>Username</th>

                        <th>Email</th>

                        <th>Số điện thoại</th>

                        <th>Vai trò</th>

                        <th>Trạng thái</th>

                        <th width="220">Thao tác</th>

                    </tr>


                </thead>



                <tbody>


                <?php if(count($users) == 0): ?>


                    <tr>

                        <td colspan="8" class="text-center text-danger">

                            Không có dữ liệu

                        </td>

                    </tr>


                <?php else: ?>


                    <?php $stt = 1; ?>


                    <?php foreach($users as $item): ?>


                    <tr>


                        <td class="text-center">

                            <?= $stt++ ?>

                        </td>


                        <td>

                            <strong>

                                <?= htmlspecialchars($item->fullname) ?>

                            </strong>

                        </td>


                        <td>

                            <?= htmlspecialchars($item->username) ?>

                        </td>


                        <td>

                            <?= htmlspecialchars($item->email) ?>

                        </td>


                        <td>

                            <?= htmlspecialchars($item->phone) ?>

                        </td>



                        <td class="text-center">


                            <?php if($item->role == 1): ?>


                                <span class="badge bg-primary">

                                    Admin

                                </span>


                            <?php else: ?>
                                <span class="badge bg-secondary">
                                    User
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if($item->status == 1): ?>
                                <span class="badge bg-success">
                                    Hoạt động
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger">
                                    Khóa
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="detail.php?id=<?= $item->id ?>"
                               class="btn btn-info btn-sm">
                                <i class="bi bi-eye"></i>
                                Chi tiết
                            </a>
                            <a href="edit.php?id=<?= $item->id ?>"
                               class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                                Sửa
                            </a>
                            <a href="delete.php?id=<?= $item->id ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Bạn có chắc muốn xóa?')">
                                <i class="bi bi-trash"></i>
                                Xóa
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php

$content = ob_get_clean();

include "../layouts/master.php";

?>