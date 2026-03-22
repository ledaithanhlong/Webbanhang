# Web Bán Hàng – Đồ Án PHP MVC

> **Sinh viên thực hiện:** Lê Đại Thanh Long  
> **MSSV:** 2280601752  
> **Trường:** Đại học Công nghệ TP.HCM (HUTECH)  
> **Môn học:** Lập trình Web  

---

## Giới thiệu

**Web Bán Hàng** là một ứng dụng thương mại điện tử đơn giản được xây dựng theo mô hình **MVC (Model - View - Controller)** sử dụng ngôn ngữ **PHP thuần** kết hợp với **MySQL**. Dự án cho phép người dùng xem, tìm kiếm và mua sản phẩm; quản trị viên có thể quản lý sản phẩm, danh mục và đơn hàng.

---

## Tính năng chính

### Người dùng (User)
- Xem danh sách sản phẩm và chi tiết sản phẩm
- Tìm kiếm sản phẩm theo tên hoặc danh mục
- Thêm sản phẩm vào giỏ hàng, tăng/giảm số lượng, xóa giỏ hàng
- Đặt hàng với thông tin giao hàng (tên, SĐT, địa chỉ đầy đủ theo tỉnh/quận/phường)
- Xem trang xác nhận đơn hàng sau khi thanh toán
- Đăng ký tài khoản, đăng nhập, đăng xuất

### Quản trị viên (Admin)
- Quản lý sản phẩm: thêm, sửa, xóa (kèm upload ảnh)
- Quản lý danh mục: thêm, sửa, xóa
- Xem dashboard quản trị và lịch sử đơn hàng

---

## Cấu trúc thư mục

```
Webbanhang/
│
├── index.php                  # Front Controller – điều hướng toàn bộ request
├── my_store.sql               # Script tạo CSDL
├── .htaccess                  # Cấu hình URL rewrite (Apache)
│
├── app/
│   ├── config/
│   │   └── database.php       # Kết nối CSDL bằng PDO
│   │
│   ├── models/
│   │   ├── ProductModel.php   # Thao tác CSDL với bảng product
│   │   ├── CategoryModel.php  # Thao tác CSDL với bảng category
│   │   └── AccountModel.php   # Thao tác CSDL với bảng account
│   │
│   ├── controllers/
│   │   ├── ProductController.php   # Xử lý sản phẩm, giỏ hàng, đặt hàng
│   │   ├── CategoryController.php  # Xử lý danh mục
│   │   ├── AccountController.php   # Đăng ký, đăng nhập, đăng xuất
│   │   ├── AdminController.php     # Dashboard quản trị
│   │   └── DefaultController.php   # Controller mặc định
│   │
│   ├── views/
│   │   ├── product/           # Danh sách, chi tiết, thêm, sửa, giỏ hàng, thanh toán, xác nhận, tìm kiếm
│   │   ├── category/          # Danh sách, thêm, sửa danh mục
│   │   ├── account/           # Đăng nhập, đăng ký
│   │   ├── admin/             # Dashboard và lịch sử đơn hàng
│   │   └── shares/            # Header, Footer, trang 404 dùng chung
│   │
│   └── helpers/
│       └── SessionHelper.php  # Quản lý phiên đăng nhập và phân quyền
│
├── public/
│   ├── css/                   # File CSS giao diện
│   └── images/                # Hình ảnh tĩnh
│
└── uploads/                   # Ảnh sản phẩm được upload
```

---

## Cơ sở dữ liệu

**Tên CSDL:** `my_store`

| Bảng           | Mô tả                                        |
|----------------|----------------------------------------------|
| `category`     | Danh mục sản phẩm (id, name, description)    |
| `product`      | Sản phẩm (id, name, description, price, image, category_id) |
| `orders`       | Đơn hàng (id, name, phone, address, created_at) |
| `order_details`| Chi tiết đơn hàng (order_id, product_id, quantity, price) |
| `account`      | Tài khoản người dùng (username, password, role: admin/user) |

---

## Hướng dẫn cài đặt & chạy

### Yêu cầu hệ thống
- **PHP** >= 7.4
- **MySQL** >= 5.7
- **Apache** với `mod_rewrite` bật (hoặc dùng XAMPP/WAMP)
- Extension PHP: `pdo`, `pdo_mysql`

### Các bước cài đặt

**1. Clone hoặc copy dự án vào thư mục web server:**
```
Đường dẫn mặc định XAMPP: C:\xampp\htdocs\webbanhang
```

**2. Tạo cơ sở dữ liệu:**
- Mở **phpMyAdmin** (hoặc MySQL client bất kỳ)
- Chạy file `my_store.sql` để tạo database và các bảng:
```sql
SOURCE /đường/dẫn/tới/my_store.sql;
```

**3. Cấu hình kết nối CSDL:**  
Mở file `app/config/database.php` và chỉnh sửa thông tin:
```php
private $host     = "localhost";
private $db_name  = "my_store";
private $username = "root";
private $password = "";       // ← Thay bằng mật khẩu MySQL của bạn
```

**4. Bật Apache & MySQL** trong XAMPP Control Panel.

**5. Truy cập ứng dụng trong trình duyệt:**
```
http://localhost/webbanhang
```

---

## Các đường dẫn chính

| Chức năng              | URL                                      |
|------------------------|------------------------------------------|
| Trang chủ (sản phẩm)  | `/webbanhang/product`                    |
| Chi tiết sản phẩm     | `/webbanhang/product/show/{id}`          |
| Tìm kiếm              | `/webbanhang/product/search?q=...`       |
| Giỏ hàng              | `/webbanhang/product/cart`               |
| Thanh toán            | `/webbanhang/product/checkout`           |
| Đăng ký               | `/webbanhang/account/register`           |
| Đăng nhập             | `/webbanhang/account/login`              |
| Danh mục              | `/webbanhang/category/list`              |
| Dashboard Admin       | `/webbanhang/admin/dashboard`            |
| Thêm sản phẩm (Admin) | `/webbanhang/product/add`                |

---

## Phân quyền

| Vai trò | Quyền hạn                                                       |
|---------|-----------------------------------------------------------------|
| `guest` | Xem sản phẩm, tìm kiếm, thêm vào giỏ, đặt hàng                |
| `user`  | Tất cả quyền guest + đăng nhập/đăng xuất                       |
| `admin` | Tất cả + Thêm/Sửa/Xóa sản phẩm & danh mục, xem dashboard       |

> Mật khẩu được mã hóa bằng `password_hash()` (bcrypt) trước khi lưu vào CSDL.

---

## Công nghệ sử dụng

| Công nghệ       | Mục đích                              |
|-----------------|---------------------------------------|
| PHP 7.4+        | Ngôn ngữ lập trình chính (MVC)        |
| MySQL           | Hệ quản trị cơ sở dữ liệu            |
| PDO             | Kết nối và truy vấn CSDL an toàn     |
| Apache .htaccess| URL Rewrite (clean URL)               |
| HTML / CSS / JS | Giao diện người dùng                  |
| Bootstrap       | Framework CSS responsive              |
| [provinces.open-api.vn](https://provinces.open-api.vn) | API địa chỉ Việt Nam (Tỉnh/Quận/Phường) |

---

## Ghi chú thêm

- File ảnh sản phẩm được upload vào thư mục `uploads/` (tối đa **10MB**, hỗ trợ: jpg, jpeg, png, gif, webp).
- Địa chỉ giao hàng được lấy tự động từ API mở [`provinces.open-api.vn`](https://provinces.open-api.vn) theo 3 cấp: Tỉnh/Thành → Quận/Huyện → Phường/Xã.
- URL được rewrite qua `.htaccess` theo mô hình: `/webbanhang/{controller}/{action}/{id}`.

---

*© 2025 – Lê Đại Thanh Long – MSSV: 2280601752 – HUTECH*
