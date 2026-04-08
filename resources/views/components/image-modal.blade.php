<!-- Image Modal Component - Reusable -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center" onclick="closeImageModal()">
    <div class="relative max-w-5xl max-h-screen p-4" onclick="event.stopPropagation()">
        <button onclick="closeImageModal()"
                class="absolute -top-2 -right-2 bg-white text-gray-800 rounded-full w-10 h-10 flex items-center justify-center text-2xl font-bold hover:bg-gray-200 transition shadow-lg z-10">
            ×
        </button>
        <img id="modalImage" src="" alt="Foto Besar" class="max-w-full max-h-screen rounded-lg shadow-2xl">
    </div>
</div>

<script>
function openImageModal(src) {
    event.stopPropagation();
    event.preventDefault();
    document.getElementById('modalImage').src = src;
    const modal = document.getElementById('imageModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden'; // Prevent scroll
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = ''; // Restore scroll
}

// Close on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>

<style>
#imageModal {
    backdrop-filter: blur(4px);
}
#modalImage {
    cursor: default;
}
</style>
