<?php
require_once 'controllers/Controller.php';
require_once 'models/Category.php';
//controllers/CategoryController.php
class CategoryController extends Controller {
    public function create() {
        if (isset($_POST['submit'])) {
            $name = $_POST['name'];
            if (empty($name)) {
                $this->error = "Name ko đc để trống";
            }
            if (empty($this->error)) {
                $category_model = new Category();
                $is_insert = $category_model->insertData($name);
                if ($is_insert) {
                    $_SESSION['success'] = 'Thêm mới thành công';
                    header('Location:index.php?controller=category&action=index');
                    exit();
                }
                $this->error = 'Thêm mới thất bại';
            }
        }

        $this->page_title = 'Trang thêm mới danh mục';
        $this->content =
            $this->render('views/categories/create.php');
        require_once 'views/layouts/main.php';

    }

    // index.php?controller=category&action=index
    public function index() {
        // - Controller gọi Model để truy vấn CSDL
        $category_model = new Category();
        $categories = $category_model->listData();

        // - Controller gọi View để hiển thị giao diện
        $this->page_title = 'Trang liệt kê danh mục';
        $this->content =
            $this->render('views/categories/index.php', [
                'categories' => $categories
            ]);
        require_once 'views/layouts/main.php';
    }

    //index.php?controller=category&action=update&id=5
    public function update() {
        // Validate tham số id:
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error'] = 'Tham số id ko hợp lệ';
            header('Location:index.php?controller=category&action=index');
            exit();
        }
        $id = $_GET['id'];
        // - Controller gọi Model truy vấn lấy danh mục theo id:
        $category_model = new Category();
        $category = $category_model->getById($id);
        if (isset($_POST['submit'])) {
            // + B4:
            $name = $_POST['name'];
            // + B5:
            if (empty($name)) {
                $this->error = "Name ko đc để trống";
            }
            // + B6:
            if (empty($this->error)) {
                // - Controller gọi Model để update vào bảng:
                $is_update = $category_model->updateData($name, $id);
                if ($is_update) {
                    $_SESSION['success'] = 'Cập nhật thành công';
                    header('Location:index.php?controller=category&action=index');
                    exit();
                }
                $this->error = 'Cập nhật thất bại';
            }
            // + B7: Hiển thị error ra view
        }

        // - Controller gọi View:
        $this->page_title = 'Form cập nhật';
        $this->content =
            $this->render('views/categories/update.php', [
                'category' => $category
            ]);
        require_once 'views/layouts/main.php';

    }

    //index.php?controller=category&action=delete&id=6
    public function delete() {
        // Validate tham số id:
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error'] = 'Tham số id ko hợp lệ';
            header('Location:index.php?controller=category&action=index');
            exit();
        }
        $id = $_GET['id'];
        // - Controller gọi Model để thực hiện truy vấn xóa
        $category_model = new Category();
        $is_delete = $category_model->deleteData($id);
        if ($is_delete) {
            $_SESSION['success'] = 'Xóa thành công';
        } else {
            $_SESSION['error'] = 'Xóa thất bại';
        }
        header('Location:index.php?controller=category&action=index');
        exit();
    }
}