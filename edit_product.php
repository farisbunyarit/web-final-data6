<?php
// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "890890890f";
$dbname = "motorcycle_shop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT * FROM products WHERE id = $id";
  $result = $conn->query($sql);
  $product = $result->fetch_assoc();
}

if (isset($_POST['edit_product'])) {
  $image_url = $_POST['image_url'];
  $name = $_POST['name'];
  $description = $_POST['description'];
  $price = $_POST['price'];

  $sql = "UPDATE products SET image_url='$image_url', name='$name', description='$description', price='$price' WHERE id='$id'";
  $conn->query($sql);

  header("Location: admin.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Product</title>
  
  <style>
    /* Reset basic styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    html {
        scroll-behavior: smooth;
    }

    /* Body and Background */
    body {
        background-color: #f9f9f9;
        color: #333;
        line-height: 1.6;
    }

    /* Header (لم يتم تضمينها في هذا الملف، لكن التنسيقات موجودة) */
    header {
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 10px 20px;
        background-color: #333;
        color: white;
    }
    
    /* ... (تنسيقات شريط التنقل، الهامبرغر، إلخ) ... */

    /* 1. تنسيق القسم الحاوي (للبحث) */
    #search-section {
        display: flex; 
        justify-content: center; 
        align-items: center; 
        margin: 20px 0;
    }

    /* 2. تنسيق حقل البحث */
    #search-input {
        width: 40%; 
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-right: 20px; 
    }

    #search-input:focus {
        outline: none;
        border-color: #007BFF;
    }
    
    /* 3. تنسيق زر سلة المشتريات */
    #shopping-cart-btn {
        display: inline-block; 
        padding: 10px 15px;    
        border-radius: 5px;    
        background-color: #ff6347;
        color: white;              
        border: none;              
        font-size: 16px;
        font-weight: bold;
        text-decoration: none; 
        cursor: pointer;       
        transition: background-color 0.3s ease;
    }

    #shopping-cart-btn:hover {
        background-color: #fe0202; 
    }
    
    /* ... (تنسيقات السلايدر، المنتجات، المراجعات، إلخ) ... */
    
    /* نموذج الاتصال (تم استخدام نفس الأنماط لنموذج التعديل) */
    #contact form {
        display: flex;
        flex-direction: column;
        align-items: center;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
    }
    
    /* ==================================== */
    /* === تنسيق نموذج تعديل المنتج (Edit Product Form) === */
    /* ==================================== */

    /* تنسيق العنوان الرئيسي */
    h2 {
        font-size: 2.5rem;
        font-weight: 600;
        text-align: center;
        color: #333;
        margin: 40px 0 20px 0;
    }

    /* تنسيق النموذج الحاوي */
    form {
        display: flex;
        flex-direction: column;
        align-items: center;
        background-color: #fff; 
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); 
        margin: 20px auto;
        width: 90%;
        max-width: 500px; /* تحديد أقصى عرض للنموذج */
    }

    /* تنسيق حقول الإدخال والمساحات النصية */
    form input[type="text"],
    form input[type="number"],
    form textarea {
        width: 90%;
        padding: 12px;
        margin: 10px 0;
        border-radius: 5px;
        border: 1px solid #ccc;
        box-sizing: border-box;
        font-size: 16px;
        transition: border-color 0.3s ease;
    }

    /* تنسيق التركيز (عند النقر داخل الحقل) */
    form input:focus,
    form textarea:focus {
        outline: none;
        border-color: #ff6347; /* لون التمييز البرتقالي */
    }

    /* تنسيق حقل المساحة النصية */
    form textarea {
        resize: vertical;
        min-height: 120px;
    }

    /* تنسيق زر التحديث (Update Product) */
    form button[type="submit"] {
        background-color: #ff6347; 
        color: white;
        padding: 12px 25px;
        margin-top: 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 18px;
        font-weight: bold;
        width: 90%;
        transition: background-color 0.3s ease;
    }

    form button[type="submit"]:hover {
        background-color: #ff4500; /* لون أغمق عند التفاعل */
    }
    
    /* ... (باقي تنسيقات الأقسام الأخرى) ... */

    /* Footer Section */
    footer {
        text-align: center;
        padding: 20px;
        margin-top: 20px;
        background-color: #333;
        color: white;
    }
    
    /* ... (باقي تنسيقات الـ media queries) ... */
    
  </style>
</head>
<body>

  <h2>Edit Product</h2>

  <form method="POST">
    <input type="text" name="image_url" value="<?= $product['image_url'] ?>" placeholder="Image URL" required>
    <input type="text" name="name" value="<?= $product['name'] ?>" placeholder="Product Name" required>
    <textarea name="description" placeholder="Product Description" required><?= $product['description'] ?></textarea>
    <input type="number" name="price" value="<?= $product['price'] ?>" placeholder="Price (EGP/SAR)" required>
    <button type="submit" name="edit_product">Update Product</button>
  </form>

</body>
</html>

<?php
// $conn->close();
?>
