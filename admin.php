<?php
// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "890890890f";
$dbname = "motorcycle_shop";

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// إضافة منتج جديد (مع فحص الأخطاء)
if (isset($_POST['add_product'])) {
  $image_url = $_POST['image_url'];
  $name = $_POST['name'];
  $description = $_POST['description'];
  $price = $_POST['price'];

  $sql = "INSERT INTO products (image_url, name, description, price) 
          VALUES ('$image_url', '$name', '$description', '$price')";
          
  // 💡 قم بتنفيذ الاستعلام وتحقق من نجاحه
  if ($conn->query($sql) === TRUE) {
      // 💡 إذا نجح: قم بإعادة التوجيه لرؤية المنتج الجديد
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
  } else {
      // 💡 إذا فشل: أظهر رسالة خطأ واضحة
      die("Error adding product: " . $conn->error); 
  }
}

// تعديل منتج
if (isset($_POST['edit_product'])) {
  $id = $_POST['id'];
  $image_url = $_POST['image_url'];
  $name = $_POST['name'];
  $description = $_POST['description'];
  $price = $_POST['price'];

  $sql = "UPDATE products SET image_url='$image_url', name='$name', description='$description', price='$price' WHERE id='$id'";
  $conn->query($sql);
}

// حذف منتج
if (isset($_GET['delete_id'])) {
  $delete_id = $_GET['delete_id'];
  $sql = "DELETE FROM products WHERE id='$delete_id'";
  $conn->query($sql);
}

// عرض المنتجات
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel - Motorcycle Shop</title>
  <style>/* Custom Admin Panel CSS - Modern and Clean Design */

/* ------------------------------------ */
/* General Reset and Layout */
/* ------------------------------------ */
body {
    background-color: #eef1f5; /* خلفية فاتحة جدًا لراحة العين */
    color: #333;
    font-family: Arial, sans-serif;
    padding: 30px;
    max-width: 1400px;
    margin: 0 auto;
    line-height: 1.6;
}

h2 {
    font-size: 2.2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 25px;
    padding-bottom: 10px;
    border-bottom: 3px solid #ff6347; /* خط مميز بلون العلامة التجارية */
    text-align: left;
}

h3 {
    font-size: 1.4rem;
    color: #ff6347;
    margin-top: 30px;
    margin-bottom: 20px;
    font-weight: 600;
}

hr {
    border: none;
    height: 1px;
    background-color: #ddd;
    margin: 40px 0;
}

/* ------------------------------------ */
/* Form Styles (نموذج إضافة منتج جديد) */
/* ------------------------------------ */
form {
    background-color: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08); /* ظل أعمق قليلًا */
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    align-items: flex-start;
}

form h3 {
    width: 100%;
    margin-top: 0;
    margin-bottom: 15px;
    border-bottom: 1px dashed #eee;
    padding-bottom: 10px;
}

input[type="text"],
input[type="number"],
textarea {
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 1rem;
    flex-grow: 1;
    min-width: 200px;
    transition: border-color 0.3s, box-shadow 0.3s;
}

input[type="text"]:focus,
input[type="number"]:focus,
textarea:focus {
    border-color: #ff6347;
    box-shadow: 0 0 5px rgba(255, 99, 71, 0.5);
    outline: none;
}

textarea {
    resize: vertical;
    height: 120px;
}

button[name="add_product"] {
    background-color: #ff6347;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: bold;
    transition: background-color 0.3s, transform 0.1s;
    align-self: flex-end; /* وضع الزر في الأسفل على اليمين */
}

button[name="add_product"]:hover {
    background-color: #ff4500;
    transform: translateY(-2px);
}

/* ------------------------------------ */
/* Table Styles (عرض المنتجات) */
/* ------------------------------------ */
table {
    width: 100%;
    border-collapse: separate; /* استخدام هذا لتطبيق border-radius على الجدول */
    border-spacing: 0;
    margin-top: 25px;
    background-color: white;
    border-radius: 10px;
    overflow: hidden; 
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

th, td {
    padding: 15px 18px;
    text-align: left;
}

th {
    background-color: #333; 
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.95rem;
    letter-spacing: 0.5px;
}

tr:nth-child(even) {
    background-color: #f9f9f9; 
}

tr:hover {
    background-color: #fffaf0; /* تمييز الصف عند المرور عليه */
}

td {
    border-bottom: 1px solid #eee;
}

tr:last-child td {
    border-bottom: none; /* إزالة الخط من آخر صف */
}

td img {
    border-radius: 4px;
    object-fit: cover;
    border: 1px solid #ddd;
}

/* ------------------------------------ */
/* Actions Links (تعديل | حذف) */
/* ------------------------------------ */
td a {
    color: #007bff; /* لون مختلف لتمييز الأكشن */
    text-decoration: none;
    font-weight: 600;
    margin-right: 10px;
}

td a:last-child {
    color: #ff4500; /* لون أحمر/برتقالي للحذف */
}

td a:hover {
    text-decoration: underline;
}

/* ------------------------------------ */
/* Responsive Design */
/* ------------------------------------ */
@media (max-width: 768px) {
    body {
        padding: 15px;
    }
    
    form {
        flex-direction: column;
        gap: 15px;
    }
    
    input[type="text"],
    input[type="number"],
    textarea,
    button[name="add_product"] {
        width: 100%;
        min-width: unset;
    }

    /* تحويل الجدول إلى تنسيق البطاقات (إذا أردت) */
    table, thead, tbody, th, td, tr {
        display: block;
    }

    thead tr {
        position: absolute;
        top: -9999px;
        left: -9999px;
    }

    tr {
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
    }

    td {
        border: none;
        border-bottom: 1px solid #eee;
        position: relative;
        padding-left: 50%;
        text-align: right;
    }

    td:before {
        /* لعرض تسمية الحقل على اليسار */
        content: attr(data-label);
        position: absolute;
        left: 0;
        width: 45%;
        padding-left: 15px;
        font-weight: bold;
        color: #555;
        text-align: left;
    }
}
</style>
</head>
<body>

  <h2>Admin Panel</h2>

  <!-- نموذج إضافة منتج جديد -->
  <form method="POST">
    <h3>Add New Product</h3>
    <input type="text" name="image_url" placeholder="Image URL" required>
    <input type="text" name="name" placeholder="Product Name" required>
    <textarea name="description" placeholder="Product Description" required></textarea>
    <input type="number" name="price" placeholder="Price" required>
    <button type="submit" name="add_product">Add Product</button>
  </form>

  <hr>

  <h3>Product List</h3>
  <table>
    <tr>
      <th>Image</th>
      <th>Name</th>
      <th>Description</th>
      <th>Price</th>
      <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
      <td><img src="<?= $row['image_url'] ?>" alt="<?= $row['name'] ?>" width="100"></td>
      <td><?= $row['name'] ?></td>
      <td><?= $row['description'] ?></td>
      <td><?= $row['price'] ?> Baht</td>
      <td>
        <a href="edit_product.php?id=<?= $row['id'] ?>">Edit</a> | 
        <a href="?delete_id=<?= $row['id'] ?>">Delete</a>
      </td>
    </tr>
    <?php } ?>
  </table>

</body>
</html>

<?php
$conn->close();
?>
