1. Middleware
a. AuthMiddleware: Kiểm tra đã đăng nhập chưa nếu chưa đăng nhập quay về trang login
b. GuestMiddleware: Kiểm tra đã đăng nhập thì không cho vào Login tại vì user đã đăng nhập rồi mà vẫn quay lại form
c. CsrfMiddleware: Tạo và kiểm tra CSRF Token kiểm tra để chống tấn công vào tài khoản giả mạo
2. Session và Cookie:
a. Phân biệt
- Session lưu dữ liệu vào server gắn liền với một Id
-Cookie. lưu dữ liệu trên máy 
b. $_SESSION["user"] dùng để làm gì?
c. GET/POST truyền dữ liệu tạm thời qua URL hoặc body form
-Session giữ dữ liệu lâu hơn trong suốt phiên làm việc, không cần truyền lại mỗi lần
d. Trình bày quá trình Session hoạt động khi người dùng chuyển từ Request này sang
Request khác.
-Khi login thành công, server tạo session ID và lưu thông tin user vào session
-Trình duyệt nhận session ID qua cookie
3. Đăng nhập, bảo mật và phân quyền:
a. password_verify dùng để so sánh mật khẩu người dùng nhập với hash mật khẩu lưu trong DB
b. Bcrypt là gì? Mật khẩu cần lưu dưới dạng hash để nếu DB bị lộ thì hacker không thấy mật khẩu gốc
c. Vì sao cần kiểm tra Session trước khi cho phép truy cập Admin để đảm bảo chỉ user đã đăng nhập và có quyền mới được truy cập, tránh truy cập trái phép
d. Khi đăng xuất, cần thực hiện những thao tác nào với Session: cần hủy dữ liệu trong $_SESSION, gọi session_destroy(), và có thể xóa cookie session
e. CSRF Token dùng để xác nhận request đến từ form hợp lệ của hệ thống, chống kẻ xấu gửi request giả mạo
f. Điều gì xảy ra khi CSRF Token không hợp lệ, hệ thống từ chối xử lý request, báo lỗi hoặc chuyển hướng
g. Phân biệt Authentication và Authorization. Cho ví dụ trong hệ thống MiniShop
- Authentication: xác thực danh tính ví dụ: đăng nhập email bằng mật khẩu
- Authorization: phân quyền sau khi login ví dụ: user chỉ được mua hàng, admin được thêm sửa, xóa