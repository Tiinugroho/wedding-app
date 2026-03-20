AOS.init({ duration: 1200, once: true });

// Guest Name Injection
const urlParams = new URLSearchParams(window.location.search);
const to = urlParams.get("to");
if (to) {
    document.getElementById("guest-name").innerText = to;
}

let currentIndex = 0;
const images = document.querySelectorAll(".gallery-item");

function openModal(index) {
    currentIndex = index;
    const modal = document.getElementById("imageModal");
    const img = document.getElementById("modalImg");

    modal.classList.remove("hidden");
    updateModalImage();

    // Animasi masuk
    setTimeout(() => {
        img.classList.add("show");
        img.classList.remove("scale-95", "opacity-0");
    }, 10);
}

function closeModal() {
    const modal = document.getElementById("imageModal");
    const img = document.getElementById("modalImg");

    img.classList.add("scale-95", "opacity-0");
    setTimeout(() => {
        modal.classList.add("hidden");
    }, 300);
}

function changeImage(step) {
    currentIndex += step;

    // Loop back to start/end
    if (currentIndex >= images.length) currentIndex = 0;
    if (currentIndex < 0) currentIndex = images.length - 1;

    // Transisi halus saat ganti gambar
    const img = document.getElementById("modalImg");
    img.style.opacity = "0";

    setTimeout(() => {
        updateModalImage();
        img.style.opacity = "1";
    }, 200);
}

function updateModalImage() {
    const img = document.getElementById("modalImg");
    img.src = images[currentIndex].src;
}

// Support Keyboard Navigation
document.addEventListener("keydown", (e) => {
    const modal = document.getElementById("imageModal");
    if (!modal.classList.contains("hidden")) {
        if (e.key === "ArrowRight") changeImage(1);
        if (e.key === "ArrowLeft") changeImage(-1);
        if (e.key === "Escape") closeModal();
    }
});

// RSVP Modals PopUp
document.getElementById("rsvpForm").addEventListener("submit", function (e) {
    e.preventDefault(); // Mencegah reload halaman

    // Menampilkan Modal
    const modal = document.getElementById("rsvpModal");
    modal.classList.remove("hidden");

    // Reset form setelah kirim
    this.reset();
});

function closeRsvpModal() {
    const modal = document.getElementById("rsvpModal");
    modal.classList.add("hidden");
}

// Mekanis Musik
const audio = document.getElementById("weddingMusic");
const btn = document.getElementById("musicControl");
const iconOn = document.getElementById("icon-on");
const iconOff = document.getElementById("icon-off");
let isPlaying = false;

function toggleMusic() {
    if (isPlaying) {
        audio.pause();
        iconOn.classList.add("hidden");
        iconOff.classList.remove("hidden");
        btn.classList.add("opacity-70");
    } else {
        audio.play();
        iconOn.classList.remove("hidden");
        iconOff.classList.add("hidden");
        btn.classList.remove("opacity-70");
    }
    isPlaying = !isPlaying;
}

function autoPlayInit() {
    if (!isPlaying) {
        toggleMusic();
        document.removeEventListener("click", autoPlayInit);
        document.removeEventListener("scroll", autoPlayInit);
    }
}
document.addEventListener("click", autoPlayInit);

// Story Love
function toggleStory() {
    const moreStories = document.getElementById("moreStories");
    const btnText = document.getElementById("btnText");
    const btnIcon = document.getElementById("btnIcon");
    const storySection = document.getElementById("story-section");

    if (moreStories.classList.contains("hidden")) {
        // OPEN
        moreStories.classList.remove("hidden");
        moreStories.style.opacity = "0";
        setTimeout(() => {
            moreStories.style.transition = "opacity 0.8s ease";
            moreStories.style.opacity = "1";
            AOS.refresh(); // Penting: Trigger animasi AOS untuk elemen baru
        }, 10);

        btnText.innerText = "Sembunyikan Cerita";
        btnIcon.style.transform = "rotate(180deg)";
    } else {
        // CLOSE
        moreStories.classList.add("hidden");
        btnText.innerText = "Lihat Cerita Lengkap";
        btnIcon.style.transform = "rotate(0deg)";

        // Scroll smooth kembali ke atas section story
        storySection.scrollIntoView({ behavior: "smooth" });
    }
}
