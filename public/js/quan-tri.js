/**
 * VietGo Admin - JS Quản Trị
 */

document.addEventListener('DOMContentLoaded', function () {

    // ========== SIDEBAR TOGGLE ==========
    const nutThuMo = document.getElementById('nutThuMoSidebar');
    const thanhBen = document.getElementById('thanhBen');
    const vungChinh = document.getElementById('vungChinh');

    if (nutThuMo && thanhBen && vungChinh) {
        nutThuMo.addEventListener('click', () => {
            thanhBen.classList.toggle('thu-nho');
            vungChinh.classList.toggle('mo-rong');
        });
    }

    // ========== REALTIME CLOCK ==========
    const elThoiGian = document.getElementById('thoiGianHienTai');
    if (elThoiGian) {
        function capNhatGio() {
            const now = new Date();
            const gio = now.toLocaleTimeString('vi-VN', {
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            });
            const ngay = now.toLocaleDateString('vi-VN', {
                weekday: 'long', day: '2-digit', month: '2-digit', year: 'numeric'
            });
            elThoiGian.textContent = gio + ' - ' + ngay;
        }
        capNhatGio();
        setInterval(capNhatGio, 1000);
    }

    // ========== AUTO HIDE FLASH ==========
    document.querySelectorAll('.tu-dong-an').forEach(el => {
        setTimeout(() => {
            el.style.opacity = '0';
            el.style.transform = 'translateX(100%)';
            setTimeout(() => el.remove(), 400);
        }, 4000);
    });

    // ========== CONFIRM DELETE ==========
    document.querySelectorAll('.nut-xoa-confirm').forEach(nut => {
        nut.addEventListener('click', function (e) {
            if (!confirm('Bạn có chắc chắn muốn thực hiện thao tác này?')) {
                e.preventDefault();
            }
        });
    });

    // ========== PREVIEW IMAGE UPLOAD ==========
    document.querySelectorAll('.o-chon-anh input[type="file"]').forEach(input => {
        input.addEventListener('change', function () {
            const xemTruoc = this.closest('.o-chon-anh').querySelector('.xem-truoc-anh');
            if (xemTruoc && this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    xemTruoc.src = e.target.result;
                    xemTruoc.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
});
