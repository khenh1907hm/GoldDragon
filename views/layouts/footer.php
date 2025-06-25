<!-- Footer -->
    <footer class="footer">
        <div class="top-bar destop">
            <div class="container">
                <div class="support-item ">
                    <i class="fas fa-utensils"></i>
                    <span>Ăn sáng</span>
                </div>
                <div class="support-item">
                    <i class="far fa-clock"></i>
                    <span>Đón muộn</span>
                </div>
                <div class="support-item">
                    <i class="far fa-calendar-alt"></i>
                    <span>Trông thứ 7</span>
                </div>
                <div class="support-item">
                    <i class="fas fa-bus"></i>
                    <span>Xe bus đưa đón</span>
                </div>
                <div class="support-item">
                    <i class="fas fa-phone-alt"></i>
                    <span>Hỗ trợ 24/7</span>
                </div>
                <div class="support-item">
                    <i class="fas fa-heart"></i>
                    <span>Chăm sóc chu đáo</span>
                </div>
            </div>
        </div>

        <div class="main-footer">
            <div class="container">
                <div class="footer-logo">
                    <img src="/RongVang/asset/img/logo.png" alt="Golden Dragon Kindergarten">
                    <div class="address">
                        <p><strong>Golden Dragon Kindergarten</strong> - Nơi nuôi dưỡng tài năng tương lai</p>
                        <p><i class="fas fa-map-marker-alt"></i> 616 Đ. Nguyễn Ảnh Thủ, Tân Chánh Hiệp, Quận 12, Hồ Chí Minh, Việt Nam</p>
                        <p><i class="fas fa-phone"></i> 0839395292</p>
                    </div>
                </div>

                <div class="footer-links">
                    <h3>Liên kết nhanh</h3>
                    <ul>
                        <li><a href="/">Trang chủ</a></li>
                        <li><a href="/gioi-thieu">Giới thiệu</a></li>
                        <li><a href="/chuong-trinh">Chương trình học</a></li>
                        <li><a href="/lien-he">Liên hệ</a></li>
                    </ul>
                </div>

                <div class="footer-newsletter">
                    <h3>Đăng ký nhận tin</h3>
                    <form>
                        <input type="email" placeholder="Email của bạn">
                        <button type="submit" class="bg-[var(--primary-color)] hover:bg-[var(--primary-dark)] text-white font-bold px-4 py-2 rounded-lg transition">Đăng ký</button>
                    </form>
                    <div class="social-media">
                        <strong>Mạng xã hội</strong>
                        <div class="social-icons">
                            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bottom-bar">
            <hr class="footer-divider">
            <div class="container">
                <p>&copy; 2025 Bản quyền thuộc về Trường mầm non Golden Dragon Kindergarten | Thiết kế bởi Minh Lê</p>
            </div>
        </div>
    </footer>

    <script type="importmap">
    {
        "imports": {
            "three": "https://unpkg.com/three@0.152.2/build/three.module.js",
            "three/addons/": "https://unpkg.com/three@0.152.2/examples/jsm/"
        }
    }
    </script>
    <script src="<?php echo $baseUrl; ?>/asset/js/animations.js"></script>
    <script src="/RongVang/asset/js/animations.js"></script>
    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.querySelector('.mobile-menu');
            mobileMenu.classList.toggle('active');
        }

        // Đóng menu mobile khi click vào link
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.mobile-menu a').forEach(link => {
                link.addEventListener('click', () => {
                    document.querySelector('.mobile-menu').classList.remove('active');
                });
            });
        });

        // Tự động ẩn menu mobile khi resize lên desktop
        window.addEventListener('resize', function() {
            const mobileMenu = document.querySelector('.mobile-menu');
            if (window.innerWidth > 768) {
                mobileMenu.classList.remove('active');
            }
        });
    </script>
    <style>
        .mobile-menu-btn { z-index: 1001; }
    </style>
</body>
</html>
