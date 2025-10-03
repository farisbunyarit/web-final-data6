<?php
session_start();
include '../includes/db_connection.php'; 

// === دالة لحساب الإجمالي الكلي للسلة ===
function calculate_cart_total() {
    $total = 0;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            // الإجمالي هو السعر * الكمية لكل منتج
            $total += $item['price'] * $item['quantity'];
        }
    }
    // حفظ الإجمالي الكلي في الجلسة
    $_SESSION['cart_total'] = $total;
}
// ======================================


// ======================================
// === المنطق الجديد: معالجة طلب حذف منتج (Remove Item) ===
if (isset($_POST['action']) && $_POST['action'] == 'remove' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    
    // التحقق من وجود المنتج في السلة ثم حذفه
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    
    calculate_cart_total(); // إعادة حساب الإجمالي
    header('Location: cart.php'); // العودة إلى صفحة السلة
    exit();
}
// ======================================


// ======================================
// === المنطق الجديد: معالجة طلب تحديث الكمية (Update Quantity) ===
if (isset($_POST['action']) && $_POST['action'] == 'update' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $new_quantity = intval($_POST['quantity']);
    
    if (isset($_SESSION['cart'][$product_id])) {
        
        if ($new_quantity > 0) {
            // تحديث الكمية إذا كانت أكبر من صفر
            $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
        } 
        else {
            // حذف المنتج إذا أصبحت الكمية صفر أو أقل
            unset($_SESSION['cart'][$product_id]);
        }
    }

    calculate_cart_total(); // إعادة حساب الإجمالي
    header('Location: cart.php'); // العودة إلى صفحة السلة
    exit();
}
// ======================================


// الخطوة 3: معالجة طلب إضافة منتج (Add to Cart) - (المنطق الأصلي)
if (isset($_POST['action']) && $_POST['action'] == 'add' && isset($_POST['product_id'])) {
    
    // تنظيف البيانات الواردة
    $product_id = intval($_POST['product_id']);
    $product_name = htmlspecialchars($_POST['product_name']);
    $product_price = (float)$_POST['product_price'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // تهيئة مصفوفة السلة إذا لم تكن موجودة
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // منطق إضافة/تحديث الكمية:
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'id' => $product_id,
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => $quantity,
        ];
    }
    
    calculate_cart_total();

    // الخطوة 4: إعادة توجيه المستخدم لصفحة المنتجات
    header('Location: web.php'); 
    exit();
}

?>