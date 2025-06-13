<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<main class="container mx-auto py-10 px-2 min-h-[60vh] flex flex-col items-center bg-gray-50">
    <h1 class="text-3xl md:text-4xl font-extrabold text-pink-600 mb-8 text-center tracking-wide">Thực đơn tuần này</h1>
    <div class="w-full max-w-3xl overflow-x-auto">
        <table class="min-w-full bg-white rounded-2xl shadow-xl border border-pink-100">
            <thead>
                <tr>
                    <th class="py-3 px-4 bg-pink-100 text-pink-800 text-lg font-bold text-center">Thứ</th>
                    <th class="py-3 px-4 bg-pink-100 text-pink-800 text-lg font-bold text-center">Bữa sáng</th>
                    <th class="py-3 px-4 bg-pink-100 text-pink-800 text-lg font-bold text-center">Bữa trưa</th>
                    <th class="py-3 px-4 bg-pink-100 text-pink-800 text-lg font-bold text-center">Bữa chiều</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-base">
                <tr class="hover:bg-pink-50 transition">
                    <td class="py-3 px-4 font-semibold text-center">Thứ 2</td>
                    <td class="py-3 px-4 text-center">Cháo thịt bằm</td>
                    <td class="py-3 px-4 text-center">Cơm gà xé</td>
                    <td class="py-3 px-4 text-center">Sữa chua</td>
                </tr>
                <tr class="hover:bg-pink-50 transition">
                    <td class="py-3 px-4 font-semibold text-center">Thứ 3</td>
                    <td class="py-3 px-4 text-center">Bún bò</td>
                    <td class="py-3 px-4 text-center">Cơm cá sốt cà</td>
                    <td class="py-3 px-4 text-center">Bánh flan</td>
                </tr>
                <tr class="hover:bg-pink-50 transition">
                    <td class="py-3 px-4 font-semibold text-center">Thứ 4</td>
                    <td class="py-3 px-4 text-center">Bánh mì trứng</td>
                    <td class="py-3 px-4 text-center">Cơm thịt kho</td>
                    <td class="py-3 px-4 text-center">Trái cây</td>
                </tr>
                <tr class="hover:bg-pink-50 transition">
                    <td class="py-3 px-4 font-semibold text-center">Thứ 5</td>
                    <td class="py-3 px-4 text-center">Phở gà</td>
                    <td class="py-3 px-4 text-center">Cơm tôm rim</td>
                    <td class="py-3 px-4 text-center">Sữa tươi</td>
                </tr>
                <tr class="hover:bg-pink-50 transition">
                    <td class="py-3 px-4 font-semibold text-center">Thứ 6</td>
                    <td class="py-3 px-4 text-center">Xôi đậu xanh</td>
                    <td class="py-3 px-4 text-center">Cơm bò xào</td>
                    <td class="py-3 px-4 text-center">Bánh quy</td>
                </tr>
            </tbody>
        </table>
    </div>
</main>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 