<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<main class="container mx-auto py-10 px-2 min-h-[70vh] flex items-center justify-center bg-gray-50">
    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-xl border border-green-100 p-6 md:p-10 flex flex-col md:flex-row gap-8">
        <!-- Thông tin liên hệ và bản đồ -->
        <div class="flex-1 flex flex-col gap-6 justify-center">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-green-700 mb-4">Liên hệ</h1>
                <div class="space-y-2 text-gray-700">
                    <p><strong>Địa chỉ:</strong> 15 Đông Đô, Thị trấn Liên Nghĩa, Đức Trọng, Lâm Đồng</p>
                    <p><strong>Điện thoại:</strong> <a href="tel:0839395292" class="text-green-600 hover:underline font-semibold">0839395292</a></p>
                    <p><strong>Email:</strong> <a href="mailto:info@goldendragon.edu.vn" class="text-green-600 hover:underline font-semibold">info@goldendragon.edu.vn</a></p>
                </div>
            </div>
            <div class="rounded-lg overflow-hidden shadow border border-green-100">
                <iframe src="https://www.google.com/maps?q=15+Đông+Đô,+Liên+Nghĩa,+Đức+Trọng,+Lâm+Đồng&output=embed" width="100%" height="220" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
        <!-- Form liên hệ -->
        <div class="flex-1 flex flex-col justify-center">
            <div class="bg-green-50 rounded-xl shadow p-6 md:p-8 border border-green-100">
                <h2 class="text-xl font-bold text-green-700 mb-4 text-center">Gửi liên hệ</h2>
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
                        <input type="text" id="student_name" name="student_name" placeholder=" " required class="w-full px-4 py-2 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-400 outline-none">
                        <label for="student_name" class="text-gray-600">Họ tên bé</label>
                    </div>
                    <div class="form-group">
                        <input type="text" id="nick_name" name="nick_name" placeholder=" " required class="w-full px-4 py-2 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-400 outline-none">
                        <label for="nick_name" class="text-gray-600">Tên thường gọi</label>
                    </div>
                    <div class="form-group">
                        <input type="number" id="age" name="age" min="0" max="6" placeholder=" " required class="w-full px-4 py-2 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-400 outline-none">
                        <label for="age" class="text-gray-600">Tuổi</label>
                    </div>
                    <div class="form-group">
                        <input type="text" id="parent_name" name="parent_name" placeholder=" " required class="w-full px-4 py-2 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-400 outline-none">
                        <label for="parent_name" class="text-gray-600">Họ tên phụ huynh</label>
                    </div>
                    <div class="form-group">
                        <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" placeholder=" " required class="w-full px-4 py-2 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-400 outline-none">
                        <label for="phone" class="text-gray-600">Số điện thoại</label>
                    </div>
                    <div class="form-group">
                        <input type="text" id="address" name="address" placeholder=" " required class="w-full px-4 py-2 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-400 outline-none">
                        <label for="address" class="text-gray-600">Địa chỉ</label>
                    </div>
                    <div class="form-group">
                        <textarea id="content" name="content" rows="4" placeholder=" " required class="w-full px-4 py-2 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-400 outline-none"></textarea>
                        <label for="content" class="text-gray-600">Nội dung</label>
                    </div>
                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg shadow transition">Gửi thông tin</button>
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