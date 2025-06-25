<?php
if (!isset($baseUrl)) $baseUrl = '/RongVang';
require_once __DIR__ . '/layouts/header.php'; ?>

<!-- Hero Section -->
<div class="w-full flex flex-col-reverse md:flex-row items-center justify-between mt-10 md:mt-16 py-12 md:py-20 px-4 md:px-12 bg-gradient-to-br from-blue-50 to-blue-100 relative overflow-hidden">
    <!-- Text left -->
    <div class="flex-1 flex flex-col items-center md:items-start z-10">
        <h4 class="text-lg md:text-xl text-blue-600 font-semibold mb-2 animate" data-animation="fade-in-left">Nơi nuôi dưỡng tài năng tương lai</h4>
        <h1 class="text-3xl md:text-5xl font-extrabold text-blue-900 mb-4 animate" data-animation="fade-in-up">Welcome to Golden Dragon Kindergarten</h1>
        <p class="text-base md:text-lg text-gray-700 mb-6 animate" data-animation="fade-in-up">Chúng tôi cung cấp môi trường học tập an toàn, sáng tạo và đầy cảm hứng cho trẻ em từ 2-6 tuổi</p>
        <button class="bg-[var(--primary-color)] hover:bg-[var(--primary-dark)] text-white font-bold px-8 py-3 rounded-lg shadow animate" data-animation="fade-in-up" onclick="location.href='/RongVang/tuyen-sinh'">Tìm hiểu thêm</button>
    </div>
    <!-- Hero image right -->
    <div class="flex-1 flex items-end justify-center relative w-full mb-4 md:mb-0 min-h-[340px] md:min-h-[440px]">
        <!-- Blob background, đẩy xuống dưới -->
        <div class="absolute left-1/2 top-2/3 -translate-x-1/2 -translate-y-1/2 w-80 h-80 md:w-[25rem] md:h-[25rem] bg-blue-300/30 blur-2xl rounded-[60%_40%_60%_40%/40%_60%_40%_60%] z-0 animate-pulse"></div>
        <!-- Water blob SVG overlay for more organic shape, đẩy xuống dưới -->
        <svg class="absolute left-1/2 top-2/3 -translate-x-1/2 -translate-y-1/2 w-80 h-80 md:w-[25rem] md:h-[25rem] z-0" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">

            <defs>
                <filter id="blur" x="-20%" y="-20%" width="140%" height="140%">
                    <feGaussianBlur stdDeviation="30" />
                </filter>
            </defs>
            <path filter="url(#blur)" fill="#60A5FA" fill-opacity="0.25" d="M320,200Q320,260,260,320Q200,380,140,320Q80,260,80,200Q80,140,140,80Q200,20,260,80Q320,140,320,200Z" />
        </svg>
        <!-- Teacher image nổi lên trên blob -->
        <img src="/RongVang/asset/img/teacher3.jpg" alt="Teacher" class="relative w-60 h-60 md:w-[22rem] md:h-[22rem] object-cover rounded-[40%_60%_60%_40%/60%_40%_60%_40%] shadow-2xl shadow-blue-300 border-4 border-white z-20" style="background:rgba(255,255,255,0.2)">
    </div>
</div>

<!-- Activities Section -->
<div class="content-section">
    <h2 class="section-title animate" data-animation="fade-in-up">Hoạt động của nhóm trẻ</h2>
    <div class="slider-container animate" data-animation="scale-in">      
        <div class="slider">
            <!-- Original slides lặp 3 lần -->
            <?php
            $activities = [1, 2, 3, 4, 5, 6, 7, 8];
            for ($repeat = 0; $repeat < 3; $repeat++) {
                foreach($activities as $index) {
                    echo '<div class="slide animate" data-animation="fade-in-up" style="margin:0;padding:0;display:inline-block;">
                            <img src="/RongVang/asset/img/reason_' . $index . '.jpg" alt="Hoạt động ' . $index . '">
                        </div>';
                }
            }
            ?>
        </div>
    </div>
</div>

<!-- Include Montessori Section -->
<?php require_once __DIR__ . '/sections/montessori.php'; ?>

<!-- Include Teachers Section -->
<?php require_once __DIR__ . '/sections/teachers.php'; ?>s

<!-- Include Contact Section -->
<?php require_once __DIR__ . '/sections/contact.php'; ?>

<script type="importmap">
    {
        "imports": {
            "three": "https://unpkg.com/three@0.152.2/build/three.module.js",
            "three/addons/": "https://unpkg.com/three@0.152.2/examples/jsm/"
        }
    }
    </script>
    <script src="/RongVang/asset/js/animations.js"></script>
    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.querySelector('.mobile-menu');
            mobileMenu.classList.toggle('active');
        }
    </script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>