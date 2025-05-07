<?php
// Halaman Kategori
// Get current date in Indonesian format
$bulan = array(
    1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
);
$hari = array(
    'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
);
$tanggal = date('d');
$bulan_ini = $bulan[date('n')];
$tahun = date('Y');
$hari_ini = $hari[date('w')];
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Kategori</h1>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Categories List -->
            <div class="col-md-8">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Kategori</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <p>Ini adalah halaman kategori. Silakan tambahkan daftar kategori di sini.</p>
                    </div>
                </div>
            </div>

            <!-- Calendar -->
            <div class="col-md-4">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">Kalender</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Current Date Display -->
                        <div class="text-center mb-3">
                            <div class="h2 mb-0"><?php echo $tanggal; ?></div>
                            <div class="text-muted"><?php echo $hari_ini; ?></div>
                            <div class="text-muted"><?php echo $bulan_ini . ' ' . $tahun; ?></div>
                        </div>
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        locale: 'id',
        buttonText: {
            today: 'Hari Ini',
            month: 'Bulan',
            week: 'Minggu',
            day: 'Hari'
        },
        events: [
            {
                title: 'Artikel Baru',
                start: '2024-03-20',
                color: '#28a745'
            },
            {
                title: 'Update Kategori',
                start: '2024-03-25',
                color: '#17a2b8'
            }
        ],
        dayCellDidMount: function(info) {
            // Highlight current date
            if (info.date.toDateString() === new Date().toDateString()) {
                info.el.style.backgroundColor = '#e8f4f8';
            }
        }
    });
    calendar.render();
});
</script> 