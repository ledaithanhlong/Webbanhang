<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>BAGBYLONG</title>
    <link rel="shortcut icon" href="/webbanhang/public/images/logo.png" type="image/x-icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <style>
        body {
            background-color: #fdf6ee;
            color: #3e2f1c;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: #d2b48c !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar-light .navbar-nav .nav-link,
        .navbar-light .navbar-brand {
            color: #3e2f1c !important;
            font-weight: 600;
            font-size: 1.05rem;
        }

        .navbar-light .navbar-nav .nav-link:hover {
            color: #7a5a32 !important;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: 1.2px;
        }

        .navbar-toggler {
            border-color: #a67c52;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='%23a67c52' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }

        .btn-primary {
            background-color: #a67c52;
            border-color: #a67c52;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #8b5e3c;
            border-color: #8b5e3c;
        }

        .navbar-light .navbar-nav .nav-link.username {
            color: #5c4a2e !important;
            font-weight: 600;
            cursor: default;
            user-select: none;
        }

        .navbar-nav .nav-link[href*="admin/dashboard"] {
            font-weight: 700;
            color: #4a2c17 !important;
        }

        .navbar-nav .nav-link[href*="admin/dashboard"]:hover {
            color: #7a4b2c !important;
        }

        /* SEARCH BAR CUSTOMIZATION */
        .search-form {
            flex-grow: 1;
            max-width: 300px;
            margin: 0 15px;
        }

        .search-input,
        .btn-search {
            height: 42px;
            font-size: 0.95rem;
            border-width: 1px;
            border-style: solid;
        }

        .search-input {
            border-radius: 30px 0 0 30px;
            border-color: #a67c52;
            background-color: #fffdf9;
            color: #3e2f1c;
            padding: 0 15px;
        }

        .btn-search {
            border-radius: 0 30px 30px 0;
            background-color: #a67c52;
            border-color: #a67c52;
            color: white;
            padding: 0 18px;
            font-weight: 600;
            line-height: 1.4;
        }

        .btn-search:hover {
            background-color: #8b5e3c;
            border-color: #8b5e3c;
        }

        .btn-search:hover {
            background-color: #8b5e3c;
            border-color: #8b5e3c;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <a class="navbar-brand d-flex align-items-center" href="/webbanhang">
            <img src="/webbanhang/public/images/logo.png" alt="Logo" style="height: 36px; width: auto; margin-right: 8px;">
            <span>BAGBYLONG</span>
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="/webbanhang/Product/">Danh sách sản phẩm</a>
                </li>
                <?php if (!SessionHelper::isAdmin() && SessionHelper::isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/Category/list">Danh sách danh mục</a>
                    </li>
                <?php endif; ?>
                <?php if (SessionHelper::isAdmin()): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/webbanhang/Product/add">Thêm sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/webbanhang/category/add">Thêm danh mục</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/webbanhang/admin/dashboard">Quản trị</a>
                </li>
                <?php endif; ?>
            </ul>

            <!-- FORM TÌM KIẾM SẢN PHẨM -->
            <form class="search-form d-flex align-items-center w-100 mx-3" method="GET" action="/webbanhang/Product/search">
                <div class="input-group w-100">
                    <input type="search" name="q" class="form-control search-input" placeholder="Tìm sản phẩm..." required>
                    <div class="input-group-append">
                        <button class="btn btn-search" type="submit">
                            <i class="fas fa-search mr-1"></i> Tìm
                        </button>
                    </div>
                </div>
            </form>

            <ul class="navbar-nav align-items-lg-center">
                <li class="nav-item">
                    <?php if (SessionHelper::isLoggedIn()): ?>
                    <span class="nav-link username"><?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <?php else: ?>
                    <a class="nav-link" href="/webbanhang/account/login">Đăng nhập</a>
                    <?php endif; ?>
                </li>
                <?php if (SessionHelper::isLoggedIn()): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/webbanhang/account/logout">Đăng xuất</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Nội dung trang ở đây -->
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
