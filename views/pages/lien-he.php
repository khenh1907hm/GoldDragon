<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@700;900&display=swap');
:root {
  --primary-color: #1976D2;
  --primary-light: #E3F2FD;
  --primary-dark: #0D47A1;
}
body, input, textarea, button {
  font-family: 'Roboto', Arial, Helvetica, sans-serif;
}
</style>
<main class="container mx-auto pt-20 py-10 px-2 min-h-[70vh] flex items-center justify-center bg-[var(--primary-light)]">
    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-xl border border-[var(--primary-color)] p-6 md:p-10 flex flex-col md:flex-row gap-10">
        <!-- Thông tin liên hệ và bản đồ -->
        <div class="flex-1 flex flex-col gap-6 justify-start">
            <div>
                <h1 class="text-5xl font-extrabold text-[var(--primary-color)] text-center mb-2" style="font-family: 'Roboto', Arial, Helvetica, sans-serif;">Liên hệ</h1>
            </div>
            <div class="space-y-2 text-gray-700 text-center">
                <p class="text-lg font-bold text-[var(--primary-dark)]">Trường mầm non Rồng Vàng</p>
                <p><strong>Hotline:</strong> <a href="tel:0839395292" class="text-[var(--primary-color)] hover:underline font-semibold">0839395292</a></p>
                <p><strong>Email:</strong> <a href="mailto:info@goldendragon.edu.vn" class="text-[var(--primary-color)] hover:underline font-semibold">info@goldendragon.edu.vn</a></p>
            </div>
            <div class="rounded-lg overflow-hidden shadow border border-[var(--primary-color)] mt-2">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3918.1708777071103!2d106.62007261032092!3d10.87460455733101!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752b32e08d6d5d%3A0xb86bdb4c70012465!2zTeG6p20gTm9uIFLhu5NuZyBWw6BuZw!5e0!3m2!1svi!2s!4v1747401055450!5m2!1svi!2s" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" width="100%" height="220" style="border:0;"></iframe>
            </div>
            <div class="my-6 flex flex-col items-center w-full">
                <hr class="w-2/3 border-t-2 border-[var(--primary-color)] mb-2">
                <span class="text-base font-semibold text-[var(--primary-color)] mb-2">Quét mã QR để thanh toán nhanh</span>
                <div class="bg-white border border-[var(--primary-color)] rounded-xl p-4 shadow-sm flex flex-col items-center">
                    <img src="/RongVang/asset/img/logo.png" alt="QR code" class="w-48 h-48 object-contain mb-2">
                    <span class="text-xs text-gray-500">Quét mã QR để thanh toán nhanh</span>
                </div>
            </div>
        </div>
        <!-- Form liên hệ -->
        <div class="flex-1 flex flex-col justify-center">
            <div class="bg-[var(--primary-light)] rounded-xl shadow p-6 md:p-8 border border-[var(--primary-color)]">
                <h2 class="text-xl font-bold text-[var(--primary-color)] mb-4 text-center">Gửi liên hệ</h2>
                <div id="form-message" class="alert mb-4" style="display: none;"></div>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success mb-4">
                        <?php 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger mb-4">
                        <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>
                <form id="contactForm" onsubmit="handleRegistration(event)" class="space-y-4">
                    <div class="form-group">
                        <input type="text" id="student_name" name="student_name" placeholder=" " required class="w-full px-4 py-2 border border-[var(--primary-color)] rounded-lg focus:ring-2 focus:ring-[var(--primary-color)] outline-none">
                        <label for="student_name" class="text-gray-600">Họ tên bé</label>
                    </div>
                    <div class="form-group">
                        <input type="text" id="nick_name" name="nick_name" placeholder=" " required class="w-full px-4 py-2 border border-[var(--primary-color)] rounded-lg focus:ring-2 focus:ring-[var(--primary-color)] outline-none">
                        <label for="nick_name" class="text-gray-600">Tên thường gọi</label>
                    </div>
                    <div class="form-group">
                        <input type="number" id="age" name="age" min="0" max="6" placeholder=" " required class="w-full px-4 py-2 border border-[var(--primary-color)] rounded-lg focus:ring-2 focus:ring-[var(--primary-color)] outline-none">
                        <label for="age" class="text-gray-600">Tuổi</label>
                    </div>
                    <div class="form-group">
                        <input type="text" id="parent_name" name="parent_name" placeholder=" " required class="w-full px-4 py-2 border border-[var(--primary-color)] rounded-lg focus:ring-2 focus:ring-[var(--primary-color)] outline-none">
                        <label for="parent_name" class="text-gray-600">Họ tên phụ huynh</label>
                    </div>
                    <div class="form-group">
                        <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" placeholder=" " required class="w-full px-4 py-2 border border-[var(--primary-color)] rounded-lg focus:ring-2 focus:ring-[var(--primary-color)] outline-none">
                        <label for="phone" class="text-gray-600">Số điện thoại</label>
                    </div>
                    <div class="form-group">
                        <input type="text" id="address" name="address" placeholder=" " required class="w-full px-4 py-2 border border-[var(--primary-color)] rounded-lg focus:ring-2 focus:ring-[var(--primary-color)] outline-none">
                        <label for="address" class="text-gray-600">Địa chỉ</label>
                    </div>
                    <div class="form-group">
                        <textarea id="content" name="content" rows="4" placeholder=" " required class="w-full px-4 py-2 border border-[var(--primary-color)] rounded-lg focus:ring-2 focus:ring-[var(--primary-color)] outline-none"></textarea>
                        <label for="content" class="text-gray-600">Nội dung</label>
                    </div>
                    <button type="submit" class="w-full bg-[var(--primary-color)] hover:bg-[var(--primary-dark)] text-white font-bold py-3 rounded-lg shadow transition">Gửi thông tin</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
async function handleRegistration(event) {
    event.preventDefault();
    console.log('Form submission started');
    
    const form = event.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const messageDiv = document.getElementById('form-message');
    
    try {
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.textContent = 'Đang gửi...';
        
        console.log('Sending request to /RongVang/api/register');
        const response = await fetch('/RongVang/api/register', {
            method: 'POST',
            headers: {
                'Accept': 'application/json'
            },
            body: formData
        });
        
        console.log('Response received:', response);
        
        // Get response text first
        const responseText = await response.text();
        console.log('Response text:', responseText);
        
        let result;
        try {
            result = JSON.parse(responseText);
        } catch (e) {
            console.error('Error parsing JSON:', e);
            throw new Error('Invalid server response');
        }
        
        console.log('Response data:', result);
        
        // Show message
        messageDiv.style.display = 'block';
        if (result.success) {
            messageDiv.className = 'alert alert-success';
            messageDiv.textContent = result.message || 'Cảm ơn bạn đã gửi thông tin! Chúng tôi sẽ liên hệ lại trong thời gian sớm nhất.';
            form.reset();
        } else {
            messageDiv.className = 'alert alert-danger';
            messageDiv.textContent = result.message || 'Có lỗi xảy ra, vui lòng thử lại sau.';
        }
    } catch (error) {
        console.error('Error:', error);
        messageDiv.style.display = 'block';
        messageDiv.className = 'alert alert-danger';
        messageDiv.textContent = 'Có lỗi xảy ra, vui lòng thử lại sau.';
    } finally {
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.textContent = 'Gửi thông tin';
    }
}
</script>

<style>
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.form-group {
    position: relative;
    margin-bottom: 1rem;
}

.form-group label {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    transition: all 0.2s ease;
    pointer-events: none;
    color: #666;
}

.form-group input:focus + label,
.form-group input:not(:placeholder-shown) + label,
.form-group textarea:focus + label,
.form-group textarea:not(:placeholder-shown) + label {
    top: 0;
    font-size: 0.8rem;
    background: white;
    padding: 0 0.25rem;
    color: #059669;
}

.form-group textarea + label {
    top: 1rem;
    transform: none;
}

.form-group textarea:focus + label,
.form-group textarea:not(:placeholder-shown) + label {
    top: 0;
    transform: translateY(-50%);
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 