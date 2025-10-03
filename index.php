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

// استعلام للحصول على المنتجات من قاعدة البيانات
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Motorcycle Shop</title>
  <link rel="stylesheet" href="../code/web.css">
</head>
<body>

  <!-- Products Section -->
  <section id="products" class="products">
    <h2>Our Products</h2>
    <div class="product-cards">
      <?php 
      // عرض المنتجات بشكل ديناميكي
      while ($row = $result->fetch_assoc()) { ?>
        <!-- لكل منتج سيتم استبدال القيم بالبيانات من قاعدة البيانات -->
        <div class="product-card" onclick="increaseClickCount(this)">
          <img src="<?= $row['image_url'] ?>" alt="<?= $row['name'] ?>" />
          <div class="product-info">
            <h3><?= $row['name'] ?></h3>
            <p><?= $row['description'] ?></p>
            <span class="price"><?= $row['price'] ?> Baht</span>
            <a href="#" class="btn-primary">Add to Cart</a>
          </div>
        </div>
      <?php } ?>
    </div>
  </section>

  <script src="../code/web.js"></script>
</body>
</html>

<?php
$conn->close();
?>
