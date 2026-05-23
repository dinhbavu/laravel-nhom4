@if(session()->has('login_history_id'))
<!-- Banner yêu cầu GPS -->
<div id="gpsPromptBanner" style="position:fixed;bottom:20px;left:20px;z-index:9999;background:rgba(15,23,42,0.95);backdrop-filter:blur(10px);border:1px solid rgba(74,222,128,0.3);padding:15px;border-radius:12px;color:white;display:none;align-items:center;gap:15px;box-shadow:0 10px 25px rgba(0,0,0,0.5);">
    <div style="font-size:24px;color:#4ade80;"><i class="fa-solid fa-location-crosshairs"></i></div>
    <div>
        <h4 style="margin:0 0 5px;font-size:14px;color:#4ade80;">Xác minh bảo mật</h4>
        <p style="margin:0;font-size:12px;color:#cbd5e1;max-width:250px;">VietGo cần quyền truy cập vị trí để ghi nhận lịch sử đăng nhập an toàn của bạn.</p>
    </div>
    <button id="btnGrantGps" style="background:linear-gradient(135deg, #4ade80, #10b981);color:#0f172a;border:none;padding:8px 16px;border-radius:8px;font-weight:700;cursor:pointer;font-size:13px;white-space:nowrap;transition:transform 0.2s;">Bật Vị Trí</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if ("geolocation" in navigator) {
        if (navigator.permissions) {
            navigator.permissions.query({name: 'geolocation'}).then(function(result) {
                if (result.state === 'granted') {
                    getAndSaveGps();
                } else if (result.state === 'prompt') {
                    document.getElementById('gpsPromptBanner').style.display = 'flex';
                    document.getElementById('btnGrantGps').addEventListener('click', function() {
                        getAndSaveGps();
                    });
                }
            });
        } else {
            // Fallback for Safari
            getAndSaveGps();
        }
    }

    function getAndSaveGps() {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;
            const historyId = {{ session('login_history_id') }};
            
            fetch('{{ route("khach-hang.locate.save-gps") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    lat: lat,
                    lon: lon,
                    history_id: historyId
                })
            }).then(res => res.json()).then(data => {
                if(data.success) {
                    const banner = document.getElementById('gpsPromptBanner');
                    if (banner) banner.style.display = 'none';
                }
            });
        }, function(error) {
            console.log("GPS denied or failed: ", error);
            const banner = document.getElementById('gpsPromptBanner');
            if (banner) banner.style.display = 'none';
        });
    }
});
</script>
@endif
