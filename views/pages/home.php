<?php 
$pageTitle = 'Trang chủ';
require_once __DIR__ . '/../layouts/header.php'; ?>
<main class="container">
    <section class="hero">
        <div class="hero-text">
            <h1>Chào mừng đến với Golden Dragon Kindergarten</h1>
            <p>Nơi nuôi dưỡng tài năng tương lai cho bé yêu của bạn!</p>
        </div>
    </section>
    <section class="about">
        <h2>Giới thiệu</h2>
        <p>Trường mầm non Golden Dragon cung cấp môi trường học tập an toàn, sáng tạo và đầy cảm hứng cho trẻ em từ 2-6 tuổi.</p>
    </section>
    <section class="activities">
        <h2>Hoạt động nổi bật</h2>
        <ul>
            <li>Học tập theo phương pháp Montessori</li>
            <li>Hoạt động ngoại khóa sáng tạo</li>
            <li>Chương trình phát triển kỹ năng sống</li>
        </ul>
    </section>
    <section class="teachers">
        <h2>Đội ngũ giáo viên</h2>
        <ul>
            <li>Nguyễn Thị Lanh - Giáo viên chủ nhiệm</li>
            <li>Trần Thu Hương - Giáo viên âm nhạc</li>
            <li>Lê Minh Tâm - Giáo viên mỹ thuật</li>
        </ul>
    </section>
    <section class="quick-contact">
        <h2>Liên hệ nhanh</h2>
        <p>Hotline: 0839395292</p>
        <p>Email: info@goldendragon.edu.vn</p>
    </section>
    <section class="admission-form-section my-12">
        <h2 class="text-2xl font-bold text-yellow-600 mb-4 text-center">Đăng ký nhập học nhanh</h2>
        <form id="homeAdmissionForm" class="max-w-xl mx-auto bg-white rounded-2xl shadow-xl border-2 border-yellow-200 p-8 space-y-4" onsubmit="handleRegistration(event)">
            <input type="text" name="student_name" placeholder="Họ tên bé" required class="w-full px-4 py-2 border border-yellow-200 rounded-lg focus:ring-2 focus:ring-yellow-400 outline-none">
            <input type="text" name="nick_name" placeholder="Tên gọi ở nhà" class="w-full px-4 py-2 border border-yellow-200 rounded-lg focus:ring-2 focus:ring-yellow-400 outline-none">
            <div class="flex flex-col md:flex-row gap-4">
                <input type="number" name="age" placeholder="Tuổi" min="2" max="6" required class="w-full md:w-32 px-4 py-2 border border-yellow-200 rounded-lg focus:ring-2 focus:ring-yellow-400 outline-none">
                <input type="text" name="parent_name" placeholder="Họ tên phụ huynh" required class="flex-1 px-4 py-2 border border-yellow-200 rounded-lg focus:ring-2 focus:ring-yellow-400 outline-none">
            </div>
            <input type="tel" name="phone" placeholder="Số điện thoại" required class="w-full px-4 py-2 border border-yellow-200 rounded-lg focus:ring-2 focus:ring-yellow-400 outline-none">
            <input type="text" name="address" placeholder="Địa chỉ" class="w-full px-4 py-2 border border-yellow-200 rounded-lg focus:ring-2 focus:ring-yellow-400 outline-none">
            <textarea name="content" placeholder="Nội dung" rows="3" class="w-full px-4 py-2 border border-yellow-200 rounded-lg focus:ring-2 focus:ring-yellow-400 outline-none"></textarea>
            <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-3 rounded-lg shadow transition">Gửi đăng ký</button>
        </form>
    </section>
</main>
<script>
async function handleRegistration(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    try {
        const response = await fetch('/api/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
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
        alert('Có lỗi xảy ra, vui lòng thử lại sau.');
    }
}
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 