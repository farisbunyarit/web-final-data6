
fetch(
  "https://api.weatherapi.com/v1/current.json?key=fe510e7f4cfe4a58a54155300252809&q=thailand&aqi=no"
)
  .then((response) => response.json())
  .then((data) => {
    // Display temperature
    document.getElementById("temp").innerText = `${data.current.temp_c} °C`;

    // Display weather condition
    document.getElementById(
      "condition"
    ).innerText = `Condition: ${data.current.condition.text}`;

    // Display humidity
    document.getElementById(
      "humidity"
    ).innerText = `Humidity: ${data.current.humidity}%`;

    // Display wind speed
    document.getElementById(
      "wind"
    ).innerText = `Wind: ${data.current.wind_kph} kph`;

    // Display feels like temperature
    document.getElementById(
      "feelslike"
    ).innerText = `Feels like: ${data.current.feelslike_c} °C`;

    // Display weather icon
    document.getElementById("weather-icon").src =
      "https:" + data.current.condition.icon;
  })
  .catch((error) => {
    console.error("Error fetching data:", error);
  });

let productClicks = JSON.parse(localStorage.getItem("productClicks")) || {};

function increaseClickCount(productCard) {
  const productId = productCard.querySelector("h3").textContent;

  if (!productClicks[productId]) {
    productClicks[productId] = 0;
  }

  productClicks[productId]++;
  localStorage.setItem("productClicks", JSON.stringify(productClicks));

  highlightTopProducts(); // تحديث التمييز + عرض القائمة
}

function highlightTopProducts() {
  const sortedProducts = Object.entries(productClicks).sort(
    (a, b) => b[1] - a[1]
  );
  const top3 = sortedProducts.slice(0, 3).map((item) => item[0]); // فقط أسماء المنتجات

  const listContainer = document.getElementById("top-products-list");
  listContainer.innerHTML = ""; // مسح القائمة

  const allCards = document.querySelectorAll(".product-card");

  allCards.forEach((card) => {
    const title = card.querySelector("h3").textContent;

    // ✅ تمييز المنتجات الأعلى (داخل القائمة الأصلية)
    if (top3.includes(title)) {
      card.classList.add("highlight");
    } else {
      card.classList.remove("highlight");
    }
  });

  // ✅ إضافة البطاقات الأعلى للقائمة الجانبية
  top3.forEach((productName) => {
    allCards.forEach((card) => {
      const title = card.querySelector("h3").textContent;
      if (title === productName) {
        const clonedCard = card.cloneNode(true);
        clonedCard.onclick = null; // منع تكرار النقر داخل القائمة
        clonedCard.classList.remove("highlight"); // نحذف التمييز من النسخة
        listContainer.appendChild(clonedCard);
      }
    });
  });
}

// تحميل الترتيب عند فتح الصفحة
window.addEventListener("DOMContentLoaded", highlightTopProducts);

// ✅ إعداد عرض الشرائح (Slideshow)
let slideIndex = 1;
showSlides(slideIndex);

// ✅ التبديل بين الشرائح (السابق/التالي)
function plusSlides(n) {
  showSlides((slideIndex += n));
}

// ✅ الانتقال لشريحة معينة (عند الضغط على النقاط مثلًا)
function currentSlide(n) {
  showSlides((slideIndex = n));
}

// ✅ عرض شريحة معينة بناءً على رقمها
function showSlides(n) {
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");

  if (slides.length === 0) return; // لو ما فيه شرائح، اوقف التنفيذ هنا
  if (dots.length === 0) return; // لو ما فيه نقاط، اوقف التنفيذ هنا

  // إعادة تعيين الفهرس إذا خرج عن النطاق
  if (n > slides.length) {
    slideIndex = 1;
  }
  if (n < 1) {
    slideIndex = slides.length;
  }

  // إخفاء كل الشرائح
  for (let i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }

  // إزالة الكلاس "active" من جميع النقاط (dots)
  for (let i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }

  // عرض الشريحة الحالية
  slides[slideIndex - 1].style.display = "block";

  // تمييز النقطة المرتبطة بالشريحة الحالية
  dots[slideIndex - 1].className += " active";
}

// عند الضغط على الهامبرغر، سيتم تفعيل أو إخفاء القائمة
function toggleHamburgerMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    mobileMenu.classList.toggle('show');
}


// ✅ إظهار/إخفاء القائمة عند الضغط على زر القائمة
hamburgerMenu.addEventListener("click", () => {
  navbar.classList.toggle("active");
});

// ✅ زر "الرجوع للأعلى" عند التمرير لأسفل الصفحة
const scrollToTopBtn = document.getElementById("scrollToTopBtn");

// ✅ إظهار الزر عندما يتجاوز التمرير 300 بكسل
window.onscroll = function () {
  if (
    document.body.scrollTop > 300 ||
    document.documentElement.scrollTop > 300
  ) {
    scrollToTopBtn.style.display = "block";
  } else {
    scrollToTopBtn.style.display = "none";
  }
};

// ✅ التمرير للأعلى عند الضغط على الزر
scrollToTopBtn.addEventListener("click", () => {
  window.scrollTo({
    top: 0,
    behavior: "smooth",
  });
});

// ✅ دالة التحقق من المدخلات في نموذج الاتصال
function validateForm(event) {
  event.preventDefault(); // منع إرسال النموذج بشكل افتراضي

  // ✅ جلب القيم من الحقول
  const name = document.querySelector('input[name="name"]').value;
  const subject = document.querySelector('input[name="subject"]').value;
  const phone = document.querySelector('input[name="phone"]').value;
  const email = document.querySelector('input[name="email"]').value;
  const secretword = document.querySelector('input[name="secretword"]').value;
  const message = document.querySelector('textarea[name="message"]').value;

  // ✅ تحقق من الاسم (أحرف فقط)
  const nameRegex = /^[A-Za-z\s]+$/;
  if (!name || !nameRegex.test(name)) {
    alert("Please enter a valid name with letters only.");
    return false;
  }

  // ✅ تحقق من البريد الإلكتروني (تم تعطيله بالتعليق)
  /*
    const emailRegex = /^[a-zA-Z0-9._%+-]+@ftu\.ac\.th$/;
    if (!email || !emailRegex.test(email)) {
        alert("Please enter a valid email with the domain @ftu.ac.th.");
        return false;
    }
    */

  // ✅ تحقق من رقم الهاتف (10 أرقام)
  const phoneRegex = /^\d{10}$/;
  if (!phone || !phoneRegex.test(phone)) {
    alert("Phone number must be 10 digits.");
    return false;
  }

  // ✅ تحقق من كلمة السر (أمان قوي)
  const secretwordRegex =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/;
  if (!secretword || !secretwordRegex.test(secretword)) {
    alert(
      "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character."
    );
    return false;
  }

  // ✅ تحقق من الرسالة (لا يجب أن تكون فارغة)
  if (!message) {
    alert("Please enter a message.");
    return false;
  }

  // ✅ تنظيف المدخلات قبل الإرسال (Sanitize)
  const sanitizedName = sanitizeInput(name);
  const sanitizedEmail = sanitizeInput(email);
  const sanitizedPhone = sanitizeInput(phone);
  const sanitizedSecretword = sanitizeInput(secretword);
  const sanitizedMessage = sanitizeInput(message);

  // ✅ طباعة القيم في الكونسول (اختياري)
  console.log("Form Submitted Successfully!");
  console.log("Name:", sanitizedName);
  console.log("Email:", sanitizedEmail);
  console.log("Phone:", sanitizedPhone);
  console.log("Secret Word:", sanitizedSecretword);
  console.log("Message:", sanitizedMessage);

  // ✅ إرسال النموذج بعد التحقق
  document.getElementById("contactForm").submit();
}

// ✅ دالة لتنظيف النصوص من أي أكواد خبيثة (XSS Protection)
function sanitizeInput(input) {
  const div = document.createElement("div");
  div.textContent = input;
  return div.innerHTML;
}

// ✅ ربط الدالة validateForm بإرسال النموذج
document.getElementById("contactForm").addEventListener("submit", validateForm);

// ✅ دالة البحث داخل المنتجات
function searchProducts() {
  const searchQuery = document
    .getElementById("search-input")
    .value.toLowerCase();
  const products = document.querySelectorAll(".product-card");

  // ✅ فلترة المنتجات حسب العنوان أو الوصف
  products.forEach((product) => {
    const title = product.querySelector("h3").textContent.toLowerCase();
    const description = product.querySelector("p").textContent.toLowerCase();

    if (title.includes(searchQuery) || description.includes(searchQuery)) {
      product.style.display = "block"; // إظهار المنتج
    } else {
      product.style.display = "none"; // إخفاؤه
    }
  });
}
