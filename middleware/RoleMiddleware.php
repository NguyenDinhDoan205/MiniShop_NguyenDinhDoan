<?php

class RoleMiddleware
{
    public static function admin()
    {
        if (!isset($_SESSION["user"])) {

            header("Location: ../login.php");

            exit;
        }

        $user = $_SESSION["user"];

        if ((int)$user->role !== 1) {

            http_response_code(403);

            die("
                <div style='
                    margin: 50px auto;
                    max-width: 600px;
                    text-align: center;
                    font-family: Arial;
                '>

                    <h1>403</h1>

                    <h2>Từ chối truy cập</h2>

                    <p>
                        Bạn không có quyền thực hiện chức năng này.
                    </p>

                    <a href='../index.php'>
                        Quay lại
                    </a>

                </div>
            ");
        }
    }
}