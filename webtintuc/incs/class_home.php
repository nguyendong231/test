<?php
class homelib extends dblib{
    
    function register(){
        $error = array();
        $data = array();
        
        // Lấy dữ liệu
        $data ['username'] = isset ( $_POST ['username'] ) ? $_POST ['username'] : '';
        $data ['email'] = isset ( $_POST ['email'] ) ? $_POST ['email'] : '';
        $data ['password'] = isset ( $_POST ['password'] ) ? $_POST ['password'] : '';
        
        // Kiểm tra dữ liệu
        if (empty($data ['username'])) {
            $error['username'] = 'Bạn chưa nhập tên';
        }
        
        if (empty($data ['email'])) {
            $error['email'] = 'Bạn chưa email';
        }
        
        // kiểm tra email
        if (!filter_var($data ['email'], FILTER_VALIDATE_EMAIL)) {
            
            $error['email'] = 'Email không đúng định dạng';
        }
        
        if (empty($data ['password'])) {
            $error['password'] = 'Bạn chưa nhập password';
        }
        
        // Kiểm tra nếu không có lổi thì Lưu dữ liệu
        if (!$error) {
            
            // Mã hóa password bằng MD5 để bảo mật mật khẩu
            $data ['password'] = md5($data ['password']);
            // Thời gian khi user được tạo
            $data["createdate"] = date("Y-m-d H:i:s");
            
            // Gọi hàm insert từ class_db.php để insert dữ liệu
            $this->insert("user", $data);
            $data['password'] = $_POST["password"];
           
            $error["note"] = "Đăng ký thành công";
        }
        else {
            $error["note"] = "Đăng ký thất bại";
        }
        
//         // Trả về $error để thông báo lổi nếu có
        $message[0] = $error;
        $message[1] = $data;
        
        return $message;
    
    }
    
    //hàm đăng nhập
    function login() {
        $error = array();
        $data = array();
        
        // Lấy dữ liệu
        $data ['username'] = isset ( $_POST ['username'] ) ? $_POST ['username'] : '';
        $data ['password'] = isset ( $_POST ['password'] ) ? $_POST ['password'] : '';
        
        // Kiểm tra dữ liệu
        if (empty($data ['username'])) {
            $error['username'] = 'Bạn chưa nhập tên';
        }
        
        if (empty($data ['password'])) {
            $error['password'] = 'Bạn chưa nhập password';
        }
        
        // Kiểm tra nếu không có lổi thì Lưu dữ liệu
        if (!$error) {
            
            $username = $data ['username'];
            // Mã hóa password bằng MD5 để bảo mật mật khẩu
            $password = md5($data ['password']);
            
            // SQL: hàm count(*) dùng để đếm tổng số dữ liệu được tìm thấy
            $sql = "SELECT count(*) FROM user WHERE username = '$username' AND password = '$password' LIMIT 1";
            
            // Gọi hàm get_row() từ class_db.php để lấy dữ liệu trả về cho biến $result
            $result = $this->get_row_number($sql);
            
            if ($result > 0) {
                // Tạo thông báo
                $error['message'] = "Đăng nhập thành công";
                
                // Lưu thông tin user vào cookie để không đăng nhập lần nữa, cookie sẽ có thời hạn 24h
                setcookie("user", $username, time() + (86400 * 30));
                header("Location:index.php");
                die();
            }
            else {
                // Tạo thông báo
                $error['message'] = "username hoặc password không đúng!";
            }
        }
        
        // Trả về $error để thông báo
        $message[0] = $error;
        $message[1] = $data;
        
        return $message;
    }
}
?>