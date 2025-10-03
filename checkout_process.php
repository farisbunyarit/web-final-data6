<?php
session_start();
include '../includes/db_connection.php'; // تأكد من المسار الصحيح لملف الاتصال

// دالة لحساب الإجمالي الكلي (يمكن نسخها من cart_action.php)
function calculate_cart_total() {
    $total = 0;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    $_SESSION['cart_total'] = $total;
}

// === منطق حفظ الطلب (يتم تشغيله عند الضغط على زر الإرسال في صفحة الدفع) ===
if (isset($_POST['complete_order']) && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    
    // 1. حساب الإجمالي النهائي
    calculate_cart_total();
    $final_total = $_SESSION['cart_total'] ?? 0; 
    $customer_id = 0; // **مهم:** ضع ID المستخدم الفعلي أو اتركه 0 للضيوف

    // 2. إدراج الطلب في جدول orders (الجدول الرئيسي)
    $sql_order = "INSERT INTO orders (customer_id, total_price, order_date, status) VALUES (?, ?, NOW(), 'Processing')";
    $stmt_order = $conn->prepare($sql_order);
    
    // ربط المتغيرات (id: integer, total_price: double/decimal)
    $stmt_order->bind_param("id", $customer_id, $final_total); 
    
    if ($stmt_order->execute()) {
        $order_id = $conn->insert_id; // الحصول على ID الطلب الجديد

        // 3. إدراج عناصر الطلب في جدول order_items
        $sql_item = "INSERT INTO order_items (order_id, product_id, quantity, price_at_order) VALUES (?, ?, ?, ?)";
        $stmt_item = $conn->prepare($sql_item);

        foreach ($_SESSION['cart'] as $item) {
            $product_id = $item['id'];
            $quantity = $item['quantity'];
            $price_at_order = $item['price'];
            
            // ربط المتغيرات (order_id: int, product_id: int, quantity: int, price_at_order: double/decimal)
            $stmt_item->bind_param("iiid", $order_id, $product_id, $quantity, $price_at_order);
            $stmt_item->execute();
        }
        
        // 4. مسح السلة بعد نجاح الطلب
        unset($_SESSION['cart']);
        unset($_SESSION['cart_total']);

        // إعادة التوجيه إلى صفحة تأكيد الطلب
        header('Location: order_success.php?order=' . $order_id);
        exit();
        
    } else {
        // معالجة خطأ الطلب
        echo "Error placing order: " . $conn->error;
    }
    
    $conn->close();
} else {
    // إذا لم يتم إرسال النموذج بشكل صحيح
    header('Location: cart.php'); // إرسال المستخدم إلى صفحة السلة
    exit();
}
?>