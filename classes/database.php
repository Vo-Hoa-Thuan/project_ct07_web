<?
    class Database{
        // các thuộc tính database
        protected $db_host;
        protected $db_name;
        protected $db_user;
        protected $db_pass;
        //phương thức khởi tạo
        public function __construct($host, $name, $user, $pass)
        {
            $this->db_host = $host;
            $this->db_name = $name;
            $this->db_user = $user;
            $this->db_pass = $pass;
        }
        //phương thức kết nối, dùng DSN
        public function getConn(){
            //tạo dsn(datasource name)
            $dsn = "mysql:host={$this->db_host};dbname={$this->db_name};charset=utf8";
            try{
                $conn = new PDO($dsn, $this->db_user, $this->db_pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $conn;
            }
            catch(PDOException $e){
                echo $e->getMessage();
                exit;
            }
        }
    }
?>