<?php
// ملف web.php الآن يعمل كـ API لـ React

// 1. تضمين ملف الاتصال بقاعدة البيانات.
include '../includes/db_connection.php'; 

// 2. إعداد مصفوفة فارغة لتخزين جميع المنتجات
$products = [];

// 3. جلب البيانات
$sql = "SELECT id, name, description, price, image_url FROM products";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    // تخزين كل المنتجات في مصفوفة PHP
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// 4. إغلاق اتصال قاعدة البيانات (خطوة جيدة)
$conn->close();

// 5. إرسال البيانات بصيغة JSON
// تعيين رأس الصفحة لإخبار المتصفح بأن المحتوى هو JSON
header('Content-Type: application/json');

// إرجاع مصفوفة المنتجات كـ JSON
echo json_encode([
    'products' => $products,
    'status' => 'success'
]);

exit();
?>