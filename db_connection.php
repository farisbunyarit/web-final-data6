<?php
$host = 'localhost';        // اسم الخادم
$username = 'root';         // اسم المستخدم
$password = '890890890f';             // كلمة المرور
$database = 'motorcycle_shop';  // قاعدة البيانات

// الاتصال بقاعدة البيانات باستخدام MySQLi
$conn = new mysqli($host, $username, $password, $database);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
