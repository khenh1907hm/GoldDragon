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
                <form class="space-y-4">
                    <input type="text" placeholder="Họ tên của bạn" required class="w-full px-4 py-2 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-400 outline-none">
                    <input type="email" placeholder="Email" required class="w-full px-4 py-2 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-400 outline-none">
                    <textarea placeholder="Nội dung liên hệ" required rows="4" class="w-full px-4 py-2 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-400 outline-none"></textarea>
                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg shadow transition">Gửi</button>
                </form>
            </div>
        </div>
    </div>
</main>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 