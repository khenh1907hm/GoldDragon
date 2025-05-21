
    <?php
if (!isset($baseUrl)) $baseUrl = '/3D_web_test';
require_once __DIR__ . '/layouts/header.php'; ?>

<!-- Hero Section -->
<div class="hero">
    <div class="hero-text">
        <h4 class="animate" data-animation="fade-in-left">Nơi nuôi dưỡng tài năng tương lai</h4>
        <h1 class="animate" data-animation="fade-in-up">Welcome to Golden Dragon Kindergarten</h1>
        <p class="animate" data-animation="fade-in-up">Chúng tôi cung cấp môi trường học tập an toàn, sáng tạo và đầy cảm hứng cho trẻ em từ 2-6 tuổi</p>
        <button class="animate" data-animation="fade-in-up" onclick="location.href='/3D_web_test/tuyen-sinh'">Tìm hiểu thêm</button>
    </div>
    <div class="hero-model">
        <canvas id="glbCanvas"></canvas>
    </div>
</div>

<!-- Activities Section -->
<div class="content-section">
    <h2 class="section-title animate" data-animation="fade-in-up">Hoạt động của nhóm trẻ</h2>
    <div class="slider-container animate" data-animation="scale-in">      
        <div class="slider">
            <!-- Original slides -->
            <?php
            $activities = [1, 2, 3, 4, 5, 6, 7, 8];            foreach($activities as $index) {
                echo '<div class="slide animate" data-animation="fade-in-up">
                        <img src="/3D_web_test/asset/img/reason_' . $index . '.jpg" alt="Hoạt động ' . $index . '">
                    </div>';
            }
            // Duplicate for seamless loop
            foreach($activities as $index) {
                echo '<div class="slide animate" data-animation="fade-in-up">
                        <img src="/3D_web_test/asset/img/reason_' . $index . '.jpg" alt="Hoạt động ' . $index . '">
                    </div>';
            }
            ?>
        </div>
    </div>
</div>

<!-- Include Montessori Section -->
<?php require_once __DIR__ . '/sections/montessori.php'; ?>

<!-- Include Teachers Section -->
<?php require_once __DIR__ . '/sections/teachers.php'; ?>

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
    <script src="/3D_web_test/asset/js/animations.js"></script>
    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.querySelector('.mobile-menu');
            mobileMenu.classList.toggle('active');
        }
    </script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>