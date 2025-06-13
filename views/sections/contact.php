<div class="contact-section" id="lien-he">
    <h2 class="section-title animate" data-animation="fade-in-up">Liên hệ đăng ký</h2>
    <div class="contact-container">
        <div class="form-container animate" data-animation="fade-in-left">
            <img src="/RongVang/asset/img/logo.png" alt="Teacher Avatar" class="teacher-avatar">
            <div id="form-message" class="alert" style="display: none;"></div>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>
            
            <form id="contactForm" onsubmit="handleRegistration(event)">
                <div class="form-group">
                    <input type="text" id="student_name" name="student_name" placeholder=" " required>
                    <label for="student_name">Họ tên bé</label>
                </div>
                <div class="form-group">
                    <input type="text" id="nick_name" name="nick_name" placeholder=" " required>
                    <label for="nick_name">Tên thường gọi</label>
                </div>
                <div class="form-group">
                    <input type="number" id="age" name="age" min="0" max="6" placeholder=" " required>
                    <label for="age">Tuổi</label>
                </div>
                <div class="form-group">
                    <input type="text" id="parent_name" name="parent_name" placeholder=" " required>
                    <label for="parent_name">Họ tên phụ huynh</label>
                </div>
                <div class="form-group">
                    <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" placeholder=" " required>
                    <label for="phone">Số điện thoại</label>
                </div>
                <div class="form-group">
                    <input type="text" id="address" name="address" placeholder=" " required>
                    <label for="address">Địa chỉ</label>
                </div>
                <div class="form-group">
                    <textarea id="content" name="content" rows="4" placeholder=" " required></textarea>
                    <label for="content">Nội dung</label>
                </div>
                <button type="submit" class="submit-btn">Gửi thông tin</button>
            </form>
        </div>
        <div class="map-container animate" data-animation="fade-in-right">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3918.1708777071103!2d106.62007261032092!3d10.87460455733101!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752b32e08d6d5d%3A0xb86bdb4c70012465!2zTeG6p20gTm9uIFLhu5NuZyBWw6BuZw!5e0!3m2!1svi!2s!4v1747401055450!5m2!1svi!2s" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</div>

<script>
async function handleRegistration(event) {
    event.preventDefault();
    console.log('Form submission started');
    
    const form = event.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('.submit-btn');
    const messageDiv = document.getElementById('form-message');
    
    // Debug: Log form data
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
    
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
</style>
