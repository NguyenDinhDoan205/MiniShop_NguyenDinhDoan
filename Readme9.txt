1. $limit, $page, $offset dùng để làm gì?
-$limit: số bản ghi hiển thị trong trang 
-$page: trang hiện tại 
-$offset: vị trí lấy dữ liệu được tính bằng 
2. Vì sao cần ceil() khi tính $totalPages?
- vì tổng bản ghi không chia hết cho limit với celi giúp làm tròn lên dữ liệu hiển thị ở trang cuối cùng
3. LIMIT và OFFSET trong SQL có tác dụng gì?
- LIMIT: Giới hạn số lượng
- OFFSET: Bỏ qua một số bản ghi trước khi lấy dữ liệu
4. Vì sao khi chuyển trang phải giữ limit trên URL?
- Để hệ thống biết mỗi trang đang hiển thị bao nhiêu bản ghi và giữ nguyên lựa chọn của người dùng khi chuyển trang
5. Vì sao khi tìm kiếm phải giữ keyword khi chuyển trang?
- Để các trang tiếp theo vẫn lọc theo từ khóa đang tìm kiếm, tránh việc chuyển trang làm mất kết quả tìm kiếm
6. count() dùng để làm gì trong chức năng phân trang?
- count() dùng để đếm tổng số bản ghi, từ đó tính được số trang cần hiển thị
7. Vì sao nên tái sử dụng getPage() thay vì tạo getPageByKeyword() riêng?
- Giúp giảm code trùng lặp, dễ bảo trì và có thể sử dụng cùng một hàm cho nhiều trường hợp như lấy toàn bộ dữ liệu hoặc tìm kiếm
8. Khi tìm kiếm không có kết quả thì $totalPages có giá trị bao nhiêu?
- Nếu không có kết quả, tổng số bản ghi là 0
9. sort dùng để làm gì?
- sort dùng để xác định cách sắp xếp dữ liệu, theo tên, giá tăng dần,giảm dần
10.Khi kết hợp tìm kiếm + sắp xếp + phân trang, những tham số nào cần được giữ trên
URL?
-keyword: từ khóa tìm kiếm
-sort: kiểu sắp xếp
-limit: số bản ghi mỗi trang
-page: trang hiện tại