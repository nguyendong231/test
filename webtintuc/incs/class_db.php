<?php
class dblib{
    
    // Biến lưu trữ kết nối
    private $_conn;
    
    // hàm kết nối
    function connect()
    {
        // thông tin database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "webtintuc";
        
        // kiểm tra nếu chưa kết nối thì thực hiện kết nối
        if(!$this->_conn){
            // kết nối
            try {
                $this->_conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $this->_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch (PDOException $e){
                echo "Error: ".$e->getMessage();
                die();
            }
        }
    }
    
    // hàm ngắt kết nối
    function dis_connect(){
        // nếu đang kết nối thì ngắt
        if ($this->_conn){
            $this->_conn = null;
        }
    }
    
    // hàm Insert
    function insert($table, $data)
    {
        // Kết nối
        $this->connect();
        
        // Lưu trữ danh sách field
        $field_list = '';
        // Lưu trữ danh sách giá trị tương ứng với field
        $value_list = '';
        
        // Lập qua data
        foreach ($data as $key => $value){
            $field_list .= ",$key";
            $value_list .= ",'".$value."'";
        }
        
        //  các vòng lặp các biến $field_list và $value_list sẽ thừa một dấu , nên ta sẽ dùng hàm trim để xóa đi
        $sql = 'INSERT INTO '.$table. '('.trim($field_list, ',').') VALUES ('.trim($value_list, ',').')';
        $stmt = $this->_conn->prepare($sql);
        
        return $stmt->execute();
    }
    
    // Hàm Update
    function update($table, $data, $where)
    {
        // Kết nối
        $this->connect();
        $sql = '';
        // Lặp qua data
        foreach ($data as $key => $value){
            $sql .= "$key = '".$value."',";
        }
        
        // 
        $sql = 'UPDATE '.$table. ' SET '.trim($sql, ',').' WHERE '.$where;
        $stmt = $this->_conn->prepare($sql);
        
        return $stmt->execute();
    }
    
    // Hàm delete
    function remove($table, $where){
        // Kết nối
        $this->connect();
        
        // Delete
        $sql = "DELETE FROM $table WHERE $where";
        $stmt = $this->_conn->prepare($sql);
        
        return $stmt->execute();
    }
    
    // Hàm lấy danh sách
    function get_list($sql)
    {
        // Kết nối
        $this->connect();
        
        // thực hiện lấy danh sách
        $stmt = $this->_conn->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        
        return $stmt->fetchALL();
    }
    
    // hàm lấy 1 record duy nhất
    function get_row($sql)
    {
        // Kết nối
        $this->connect();
        
        // Thực hiện lấy dữ liệu
        $stmt = $this->_conn->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC); //Trả về dữ liệu dạng mảng với key là tên của column
        
        return $stmt->fetch();
    }
    
    // hàm lấy tổng số bài viết
    function get_row_number($sql)
    {
        // Kết nối
        $this->connect();
        
        // Thực hiện lấy dữ liệu
        $stmt = $this->_conn->prepare($sql);
        $stmt->execute();
        
        
        return $stmt->fetchColumn();
    }
}
?>