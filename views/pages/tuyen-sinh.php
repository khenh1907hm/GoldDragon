<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<main class="container mx-auto py-10 flex justify-center items-center min-h-[80vh] bg-gray-50">
    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl border-2 border-yellow-200 p-8 md:p-12 relative">
        <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-yellow-400 rounded-full w-20 h-20 flex items-center justify-center shadow-lg border-4 border-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 7v-6m0 0l-9-5m9 5l9-5" /></svg>
        </div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-yellow-600 text-center mb-8 mt-8 tracking-wide">Đơn Tuyển Sinh</h1>
        <section class="mb-8">
            <h2 class="text-xl font-bold text-yellow-700 mb-2">Quy trình nhập học</h2>
            <ol class="list-decimal list-inside text-gray-700 space-y-1 pl-2">
                <li>Phụ huynh đăng ký thông tin</li>
                <li>Nhà trường liên hệ xác nhận</li>
                <li>Hoàn thiện hồ sơ và nhập học</li>
            </ol>
        </section>
        <section class="mb-4">
            <h2 class="text-xl font-bold text-yellow-700 mb-2">Đăng ký nhập học</h2>
            <form id="admissionForm" class="space-y-4" onsubmit="handleRegistration(event)">
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
    </div>
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