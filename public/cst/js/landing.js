document.addEventListener("DOMContentLoaded", function () {
    function reveal() {
        var reveals = document.querySelectorAll(".reveal");
        for (var i = 0; i < reveals.length; i++) {
            var windowHeight = window.innerHeight;
            var elementTop = reveals[i].getBoundingClientRect().top;
            var elementVisible = 80;

            if (elementTop < windowHeight - elementVisible) {
                reveals[i].classList.add("active");
            }
        }
    }

    reveal();
    window.addEventListener("scroll", reveal);

    // Efek Navbar transparan ke solid saat scroll
    var nav = document.querySelector("nav");
    window.addEventListener("scroll", function () {
        if (window.scrollY > 50) {
            nav.classList.add("shadow-sm");
            nav.classList.replace("glass-light", "bg-white/90");
            nav.style.backdropFilter = "blur(16px)";
        } else {
            nav.classList.remove("shadow-sm");
            nav.classList.replace("bg-white/90", "glass-light");
        }
    });
});

const modalData = {
    syarat: {
        title: "Syarat & Ketentuan Penggunaan",
        body: `
            <div class="space-y-6 text-sm md:text-base">
                <p>Selamat datang di <b>RuangRestu.com</b>. Dokumen ini merupakan perjanjian sah antara Anda sebagai pengguna dengan manajemen RuangRestu.</p>
                
                <div>
                    <h4 class="font-bold text-slate-800 border-b pb-1 mb-2">1. Layanan dan Batasan</h4>
                    <ul class="list-disc ml-5 space-y-1">
                        <li>RuangRestu menyediakan platform pembuatan undangan digital berbasis web.</li>
                        <li>Paket "Aktif Selamanya" bergantung pada keberlangsungan operasional server RuangRestu (garansi ketersediaan data minimal 10 tahun).</li>
                        <li>Layanan mencakup subdomain <i>ruangrestu.com/nama-undangan</i> kecuali untuk paket eksklusif.</li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-slate-800 border-b pb-1 mb-2">2. Kewajiban Pengguna</h4>
                    <ul class="list-disc ml-5 space-y-1">
                        <li>Pengguna menjamin bahwa semua konten (foto, teks, cerita) tidak melanggar hak cipta pihak ketiga.</li>
                        <li>Dilarang keras menggunakan layanan untuk konten pornografi, perjudian, SARA, atau aktivitas ilegal lainnya.</li>
                        <li>Pengguna bertanggung jawab penuh atas kerahasiaan tautan (link) undangan yang disebarkan.</li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-slate-800 border-b pb-1 mb-2">3. Pembayaran dan Lisensi</h4>
                    <ul class="list-disc ml-5 space-y-1">
                        <li>Aktivasi fitur premium dilakukan segera setelah konfirmasi pembayaran diterima.</li>
                        <li>Lisensi satu undangan berlaku untuk satu pasangan/acara.</li>
                        <li>Pembayaran yang sudah terkonfirmasi bersifat final dan tidak dapat dikembalikan (non-refundable).</li>
                    </ul>
                </div>

                <div class="bg-slate-50 p-4 rounded-xl italic text-slate-500 text-xs">
                    Terakhir diperbarui: 12 Maret 2026. RuangRestu berhak mengubah ketentuan ini sewaktu-waktu tanpa pemberitahuan sebelumnya.
                </div>
            </div>
        `,
    },
    privasi: {
        title: "Kebijakan Privasi",
        body: `
            <div class="space-y-6 text-sm md:text-base">
                <p>Kami sangat menghargai privasi Anda. Kebijakan ini menjelaskan bagaimana kami mengelola data Anda.</p>

                <div>
                    <h4 class="font-bold text-slate-800 mb-2">A. Data yang Kami Kumpulkan</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-3 border rounded-xl">
                            <p class="font-semibold text-rRed">Data Mempelai</p>
                            <p class="text-xs">Nama, riwayat cerita, foto galeri, dan informasi kontak.</p>
                        </div>
                        <div class="p-3 border rounded-xl">
                            <p class="font-semibold text-rOrange">Data Tamu</p>
                            <p class="text-xs">Nama tamu, alamat, ucapan, dan status kehadiran (RSVP).</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-slate-800 mb-2">B. Penggunaan Informasi</h4>
                    <ul class="list-decimal ml-5 space-y-1">
                        <li>Menampilkan konten undangan digital sesuai preferensi Anda.</li>
                        <li>Mengirimkan notifikasi RSVP melalui sistem (Email/WhatsApp).</li>
                        <li>Menganalisis statistik kunjungan untuk meningkatkan performa website.</li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-slate-800 mb-2">C. Perlindungan Data</h4>
                    <p>Semua transmisi data dilakukan melalui protokol <b>Secure Socket Layer (SSL)</b> yang terenkripsi. Kami tidak menyimpan informasi kartu kredit; semua transaksi diproses melalui <i>payment gateway</i> resmi yang aman.</p>
                </div>

                <div>
                    <h4 class="font-bold text-slate-800 mb-2">D. Pengendalian Data</h4>
                    <p>Anda memiliki hak penuh untuk menghapus atau menyembunyikan (set private) undangan Anda melalui dashboard pengguna kapan saja.</p>
                </div>
            </div>
        `,
    },
    bantuan: {
        title: "Pusat Bantuan (FAQ)",
        body: `
            <div class="space-y-4">
                <div class="collapse-item">
                    <p class="font-bold text-slate-800">1. Berapa lama proses pembuatan undangan?</p>
                    <p class="text-sm text-slate-600">Undangan dapat langsung aktif segera setelah Anda melakukan pembayaran otomatis. Proses pengisian konten biasanya memakan waktu 15-30 menit saja.</p>
                </div>

                <div class="collapse-item">
                    <p class="font-bold text-slate-800">2. Apakah saya bisa mengubah data setelah disebar?</p>
                    <p class="text-sm text-slate-600">Bisa. Anda memiliki akses penuh ke dashboard untuk mengubah foto, lokasi, atau teks kapan saja. Perubahan akan langsung ter-update secara real-time.</p>
                </div>

                <div class="collapse-item">
                    <p class="font-bold text-slate-800">3. Bagaimana cara tamu memberikan amplop digital?</p>
                    <p class="text-sm text-slate-600">Tamu cukup menekan tombol 'Kado Digital', kemudian akan muncul nomor rekening atau QRIS yang sudah Anda atur. Uang akan masuk langsung ke rekening Anda tanpa potongan dari kami.</p>
                </div>

                <div class="collapse-item">
                    <p class="font-bold text-slate-800">4. Apakah ada batasan jumlah foto?</p>
                    <p class="text-sm text-slate-600">Jumlah foto bergantung pada paket yang dipilih (Basic: 5, Premium: 20, Eksklusif: Unlimited).</p>
                </div>

                <div class="mt-8 p-6 bg-gradient-to-r from-rRed to-rOrange rounded-3xl text-white text-center">
                    <p class="font-medium mb-2">Masih punya pertanyaan lain?</p>
                    <a href="https://wa.me/628123456789" target="_blank" class="inline-block bg-white text-rRed px-6 py-2 rounded-full font-bold hover:scale-105 transition">
                        Chat WhatsApp CS
                    </a>
                </div>
            </div>
        `,
    },
};

function openModal(type) {
    const data = modalData[type];
    const overlay = document.getElementById("modal-overlay");
    const content = document.getElementById("modal-content");

    document.getElementById("modal-title").innerText = data.title;
    document.getElementById("modal-body").innerHTML = data.body;

    overlay.classList.remove("hidden");
    setTimeout(() => {
        content.classList.remove("scale-95", "opacity-0");
        content.classList.add("scale-100", "opacity-100");
    }, 10);
}

function closeModal() {
    const overlay = document.getElementById("modal-overlay");
    const content = document.getElementById("modal-content");

    content.classList.add("scale-95", "opacity-0");
    content.classList.remove("scale-100", "opacity-100");

    setTimeout(() => {
        overlay.classList.add("hidden");
    }, 300);
}

// Menutup modal jika klik di luar box
window.onclick = function (event) {
    const overlay = document.getElementById("modal-overlay");
    if (event.target == overlay) closeModal();
};
