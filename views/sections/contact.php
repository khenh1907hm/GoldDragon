<div class="contact-section" id="lien-he">
    <h2 class="section-title animate" data-animation="fade-in-up">Liên hệ đăng ký</h2>
    <div class="contact-container">
        <div class="form-container animate" data-animation="fade-in-left">
            <img src="/3D_web_test/asset/img/logo.png" alt="Teacher Avatar" class="teacher-avatar">
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
    const form = event.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    try {
        const response = await fetch('/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            alert('Cảm ơn bạn đã gửi thông tin! Chúng tôi sẽ liên hệ lại trong thời gian sớm nhất.');
            form.reset();
        } else {
            alert('Có lỗi xảy ra, vui lòng thử lại sau.');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Có lỗi xảy ra, vui lòng thử lại sau.');
    }
}
