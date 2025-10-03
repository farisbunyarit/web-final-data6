<?php
// الخطوة 1: بدء الجلسة
session_start();

// الخطوة 2: تضمين ملف الاتصال 
include '../includes/db_connection.php'; 

// دالة لحساب الإجمالي الكلي (للتأكد من أن الإجمالي محدث)
function calculate_cart_total() {
    $total = 0;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    $_SESSION['cart_total'] = $total;
}

calculate_cart_total(); // تحديث الإجمالي عند كل زيارة للصفحة
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
<style>
    /* Inherit basic font from the main CSS */
    table { 
        width: 90%; /* Slightly wider table */
        margin: 20px auto; 
        border-collapse: collapse; 
        font-family: Arial, sans-serif;
    }
    th, td { 
        border: 1px solid #ddd; 
        padding: 12px; /* Increased padding */
        text-align: right; /* Changed to right-align for Arabic/RTL */
    }
    th {
        background-color: #333; /* Dark background from your header */
        color: white; 
    }
    .total-row { 
        font-weight: bold; 
        background-color: #ffe0d8; /* Light coral background for totals */
        color: #333;
    }
    .checkout-container { 
        text-align: center; 
        margin-top: 30px; 
    }
    .btn-primary { 
        padding: 12px 25px; /* Larger padding for the main button */
        background-color: #ff6347; /* Your primary theme color (Tomato) */
        color: white; 
        border: none; 
        cursor: pointer; 
        border-radius: 5px; 
        font-size: 16px;
        transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #ff4500; /* Darker orange on hover */
    }
</style>
</head>
<body>

    <h1>Shopping Cart</h1>

    <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity & Action</th> <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= number_format($item['price'], 2) ?> Baht</td>
                        
                        <td>
                            <form method="post" action="cart_action.php" style="display: flex; flex-wrap: wrap; gap: 5px; align-items: center;">
                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">

                                <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="0" style="width: 60px;">
                                
                                <button type="submit" name="action" value="update" style="padding: 5px 10px; background-color: #28a745; color: white; border: none; cursor: pointer;">Update</button>
                                
                                <button type="submit" name="action" value="remove" style="padding: 5px 10px; background-color: #dc3545; color: white; border: none; cursor: pointer;">Remove</button>
                            </form>
                        </td>
                        
                        <td><?= number_format($item['price'] * $item['quantity'], 2) ?> Baht</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Total:</td>
                    <td><?= number_format($_SESSION['cart_total'], 2) ?> Baht</td>
                </tr>
            </tfoot>
        </table>

        <div class="checkout-container">
            <h3>Total Amount: <?= number_format($_SESSION['cart_total'], 2) ?> Baht</h3>
            
            <form method="post" action="checkout_process.php">
                <input type="hidden" name="complete_order" value="1"> 
                
                <button type="submit" class="btn-primary">Proceed to Checkout & Place Order</button>
            </form>
        </div>

    <?php else: ?>
        <p style="text-align: center;">Your cart is empty. Please add some products!</p>
    <?php endif; ?>

</body>
</html>