/**
 * VietGo - JS Chính (Frontend)
 */

document.addEventListener('DOMContentLoaded', function () {

    // ========== HERO SLIDER ==========
    const anhHero = document.querySelectorAll('.anh-hero');
    if (anhHero.length > 1) {
        let viTri = 0;
        setInterval(() => {
            anhHero[viTri].classList.remove('hoat-dong');
            viTri = (viTri + 1) % anhHero.length;
            anhHero[viTri].classList.add('hoat-dong');
        }, 5000);
    }

    // ========== HEADER SCROLL ==========
    const dauTrang = document.getElementById('dau-trang');
    let cuonTruoc = 0;
    if (dauTrang) {
        window.addEventListener('scroll', () => {
            const cuonHienTai = window.pageYOffset;
            if (cuonHienTai > 80) {
                dauTrang.classList.add('da-cuon');
            } else {
                dauTrang.classList.remove('da-cuon');
            }
            cuonTruoc = cuonHienTai;
        });
    }

    // ========== MOBILE MENU ==========
    const nutMenu = document.getElementById('nutMenuMobile');
    const menuChinh = document.querySelector('.menu-chinh');
    if (nutMenu && menuChinh) {
        nutMenu.addEventListener('click', () => {
            nutMenu.classList.toggle('mo');
            menuChinh.classList.toggle('hien');
        });
    }

    const navToggle = document.getElementById('navToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    if (navToggle && mobileMenu) {
        navToggle.addEventListener('click', () => {
            navToggle.classList.toggle('open');
            mobileMenu.classList.toggle('open');
        });
        
        // Close menu when clicking a link
        const mobileLinks = mobileMenu.querySelectorAll('.mobile-nav-link');
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                navToggle.classList.remove('open');
                mobileMenu.classList.remove('open');
            });
        });
        
        // Close menu when clicking outside (on the overlay)
        mobileMenu.addEventListener('click', (e) => {
            if (e.target === mobileMenu) {
                navToggle.classList.remove('open');
                mobileMenu.classList.remove('open');
            }
        });
    }

    // ========== DROPDOWN MENU ==========
    document.querySelectorAll('.co-menu-con').forEach(item => {
        item.addEventListener('click', function (e) {
            if (window.innerWidth <= 992) {
                e.preventDefault();
                this.classList.toggle('mo-menu-con');
            }
        });
    });

    // ========== SCROLL TO TOP ==========
    const nutLen = document.getElementById('nutLenDau');
    if (nutLen) {
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 400) {
                nutLen.classList.add('hien');
            } else {
                nutLen.classList.remove('hien');
            }
        });
        nutLen.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ========== AUTO HIDE FLASH ==========
    document.querySelectorAll('.tu-dong-an').forEach(el => {
        setTimeout(() => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(-20px)';
            setTimeout(() => el.remove(), 400);
        }, 4000);
    });

    // ========== ACCOUNT DROPDOWN (Desktop) ==========
    const taiKhoanDD = document.querySelector('.tai-khoan-dropdown');
    if (taiKhoanDD) {
        const nutTK = taiKhoanDD.querySelector('.nut-tai-khoan');
        const menuTK = taiKhoanDD.querySelector('.menu-tai-khoan');
        if (nutTK && menuTK) {
            nutTK.addEventListener('click', (e) => {
                e.stopPropagation();
                taiKhoanDD.classList.toggle('mo-menu-con');
            });
            document.addEventListener('click', () => {
                taiKhoanDD.classList.remove('mo-menu-con');
            });
        }
    }
});
