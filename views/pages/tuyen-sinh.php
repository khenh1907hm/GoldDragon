<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<main class="container mx-auto pt-20 py-12 px-4">
    <div class="bg-blue-50 rounded-xl shadow-lg overflow-hidden max-w-4xl mx-auto border border-blue-200">
        <div class="bg-[var(--primary-color)] p-6 md:p-8 text-white text-center">
            <h1 class="text-3xl md:text-4xl font-bold tracking-tight">Tuyển Sinh Năm Học Mới</h1>
            <p class="mt-2 text-blue-100">Cùng bé khôn lớn và khám phá thế giới tại Rồng Vàng</p>
        </div>

        <div class="p-6 md:p-10 grid md:grid-cols-2 gap-10 items-start">
            <!-- Left Column: Information -->
            <div class="space-y-8">
                <div>
                    <h2 class="text-2xl font-semibold text-[var(--primary-color)] mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Quy trình nhập học
                    </h2>
                    <ol class="list-decimal list-inside text-gray-600 space-y-2 pl-4 border-l-4 border-yellow-200 py-2">
                        <li><strong>Đăng ký:</strong> Phụ huynh điền đầy đủ thông tin vào form bên cạnh.</li>
                        <li><strong>Xác nhận:</strong> Nhà trường sẽ liên hệ lại để xác nhận thông tin và tư vấn.</li>
                        <li><strong>Hoàn tất hồ sơ:</strong> Phụ huynh chuẩn bị hồ sơ và hoàn tất thủ tục tại trường.</li>
                        <li><strong>Nhập học:</strong> Bé chính thức trở thành thành viên của gia đình Rồng Vàng!</li>
                    </ol>
                </div>
                <div>
                    <h2 class="text-2xl font-semibold text-[var(--primary-color)] mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Thông tin cần biết
                    </h2>
                    <ul class="list-disc list-inside text-gray-600 space-y-2 pl-4">
                        <li>Độ tuổi tuyển sinh: 2 - 6 tuổi</li>
                        <li>Chương trình học chuẩn quốc tế kết hợp Montessori</li>
                        <li>Cơ sở vật chất hiện đại, an toàn</li>
                        <li>Đội ngũ giáo viên tận tâm, giàu kinh nghiệm</li>
                    </ul>
                </div>
            </div>

            <!-- Right Column: Form -->
            <div>
                <h2 class="text-2xl font-semibold text-[var(--primary-color)] mb-4 text-center md:text-left">Form Đăng Ký Tư Vấn</h2>
                <form id="admissionForm" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" name="student_name" placeholder="Họ tên bé" required class="w-full px-4 py-3 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none transition">
                        <input type="text" name="nick_name" placeholder="Tên ở nhà (Nickname)" class="w-full px-4 py-3 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none transition">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="number" name="age" placeholder="Tuổi của bé" min="2" max="6" required class="w-full px-4 py-3 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none transition">
                        <input type="text" name="parent_name" placeholder="Họ tên phụ huynh" required class="w-full px-4 py-3 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none transition">
                    </div>
                    <input type="tel" name="phone" placeholder="Số điện thoại liên hệ" required class="w-full px-4 py-3 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none transition">
                    <input type="text" name="address" placeholder="Địa chỉ hiện tại" required class="w-full px-4 py-3 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none transition">
                    <textarea name="content" placeholder="Lời nhắn hoặc câu hỏi cho nhà trường..." rows="4" required class="w-full px-4 py-3 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none transition"></textarea>
                    
                    <div id="form-feedback" class="mt-4"></div>

                    <button type="submit" id="submit-button" class="w-full bg-[var(--primary-color)] hover:bg-[var(--primary-dark)] text-white font-bold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition-all flex items-center justify-center">
                        <span id="button-text">Gửi Đăng Ký</span>
                        <svg id="button-spinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>
<script>
document.getElementById('admissionForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    
    const form = event.target;
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const buttonSpinner = document.getElementById('button-spinner');
    const formFeedback = document.getElementById('form-feedback');

    // Show spinner and disable button
    buttonText.classList.add('hidden');
    buttonSpinner.classList.remove('hidden');
    submitButton.disabled = true;
    formFeedback.innerHTML = '';

    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    try {
        const response = await fetch('/RongVang/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            const errorData = await response.json().catch(() => ({ message: 'Lỗi không xác định. Vui lòng thử lại.' }));
            throw new Error(errorData.message || 'Server responded with an error');
        }

        const result = await response.json();

        if (result.success) {
            formFeedback.innerHTML = `<div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                <span class="font-medium">Đăng ký thành công!</span> Cảm ơn bạn đã gửi thông tin. Chúng tôi sẽ liên hệ lại trong thời gian sớm nhất.
            </div>`;
            form.reset();
        } else {
            formFeedback.innerHTML = `<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                <span class="font-medium">Lỗi!</span> ${result.message || 'Có lỗi xảy ra, vui lòng thử lại.'}
            </div>`;
        }

    } catch (error) {
        console.error('Registration Error:', error);
        formFeedback.innerHTML = `<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
            <span class="font-medium">Lỗi kết nối!</span> Không thể gửi thông tin. Vui lòng kiểm tra lại kết nối mạng và thử lại.
        </div>`;
    } finally {
        // Hide spinner and re-enable button
        buttonText.classList.remove('hidden');
        buttonSpinner.classList.add('hidden');
        submitButton.disabled = false;
    }
});
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 