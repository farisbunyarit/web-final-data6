<?php
// الخطوة 1: بدء الجلسة
session_start();

// الخطوة 2: تضمين ملف الاتصال
include '../includes/db_connection.php'; 

// الخطوة 3: الحصول على رقم الطلب 
$order_id = isset($_GET['order']) ? intval($_GET['order']) : 0;

// التأكد من وجود رقم طلب صحيح
if ($order_id === 0) {
    // إرجاع رسالة خطأ بصيغة JSON
    header('Content-Type: application/json');
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Missing or invalid order ID.']);
    exit();
}

// الخطوة 4: جلب تفاصيل الطلب من قاعدة البيانات
$order_data = [
    'order_id' => $order_id,
    'found' => false,
    'details' => []
];

$sql = "SELECT t1.*, t2.product_id, t2.quantity, t2.price_at_order 
        FROM orders t1 
        JOIN order_items t2 ON t1.id = t2.order_id 
        WHERE t1.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $order_data['found'] = true;
    
    // جلب الإجمالي من أول صف 
    $first_row = $result->fetch_assoc();
    $order_data['total_price'] = $first_row['total_price'];
    $order_data['order_date'] = $first_row['order_date']; // إضافة تاريخ الطلب
    
    // إعادة تعيين المؤشر وجلب جميع العناصر (Order Items)
    $result->data_seek(0); 
    while ($row = $result->fetch_assoc()) {
        $order_data['items'][] = $row; // وضع العناصر تحت مفتاح 'items'
    }
} 
$stmt->close();
$conn->close();

// الخطوة الأخيرة: إرسال الرد بصيغة JSON
header('Content-Type: application/json');
echo json_encode($order_data);

exit();
?>