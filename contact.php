<?php

include('db_connection.php');

// التحقق من وجود البيانات في النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // أخذ المدخلات من المستخدم باستخدام POST مع تأكيد الأمان عبر mysqli_real_escape_string
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $secretword = mysqli_real_escape_string($conn, $_POST['secretword']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // استخدام الاستعلام المحضر (Prepared Statement) لتأمين البيانات
    $stmt = $conn->prepare("INSERT INTO contact_form (name, subject, phone, email, secretword, message) 
                            VALUES (?, ?, ?, ?, ?, ?)");

    // ربط المتغيرات بالاستعلام المحضر
    $stmt->bind_param("ssssss", $name, $subject, $phone, $email, $secretword, $message);

    // تنفيذ الاستعلام
    if ($stmt->execute()) {
        echo "تم إرسال البيانات بنجاح!";
    } else {
        echo "حدث خطأ: " . $stmt->error;
    }

    // إغلاق الاتصال والاستعلام
    $stmt->close();
    $conn->close();
    
}
?>
