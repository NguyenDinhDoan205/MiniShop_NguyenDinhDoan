<?php
require_once "../../../dao/CategoryDAO.php";

$pageTitle = "Danh mục";

$categoryDAO = new CategoryDAO();
$categories = $categoryDAO->getAll();

ob_start();
?>

<h2 class="mb-3">Danh sách danh mục</h2>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Tên danh mục</th>
            <th>Slug</th>
            <th>Trạng thái</th>
        </tr>
    </thead>

    <tbody>

    <?php foreach($categories as $item): ?>

        <tr>
            <td><?= $item->id ?></td>
            <td><?= $item->catename ?></td>
            <td><?= $item->slug ?></td>

            <td>
                <?= $item->status == 1 ? "Hiển thị" : "Ẩn" ?>
            </td>

        </tr>

    <?php endforeach; ?>

    </tbody>

</table>

<?php

$content = ob_get_clean();

include "../layouts/master.php";