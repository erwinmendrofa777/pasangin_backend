<script>
// Lightbox State Variables
let zoomScale = 1;
let rotateAngle = 0;
let isPanning = false;
let startX = 0;
let startY = 0;
let translateX = 0;
let translateY = 0;

function updateImageTransform() {
    const lightboxImg = document.getElementById('chat-lightbox-img');
    if (lightboxImg) {
        lightboxImg.style.transform = `translate(${translateX}px, ${translateY}px) scale(${zoomScale}) rotate(${rotateAngle}deg)`;
    }
}

// Lightbox: Buka gambar atau video ukuran penuh di overlay
function openChatLightbox(src, type = 'image') {
    const overlay = document.getElementById('chat-lightbox');
    const lightboxImg = document.getElementById('chat-lightbox-img');
    const lightboxVideo = document.getElementById('chat-lightbox-video');
    const controls = document.querySelector('.chat-lightbox-controls');
    
    // Reset zoom, rotate, and pan state
    zoomScale = 1;
    rotateAngle = 0;
    translateX = 0;
    translateY = 0;
    updateImageTransform();
    
    if (type === 'video') {
        if (lightboxImg) lightboxImg.style.display = 'none';
        if (lightboxVideo) {
            lightboxVideo.src = src;
            lightboxVideo.style.display = 'block';
            lightboxVideo.load();
            lightboxVideo.play().catch(e => console.log("Auto-play blocked or failed", e));
        }
        if (controls) controls.style.display = 'none';
    } else {
        if (lightboxVideo) {
            lightboxVideo.style.display = 'none';
            lightboxVideo.pause();
            lightboxVideo.src = "";
        }
        if (lightboxImg) {
            lightboxImg.src = src;
            lightboxImg.style.display = 'block';
        }
        if (controls) controls.style.display = 'flex';
    }
    
    overlay.classList.add('active');
}

// Lightbox: Tutup overlay dan hentikan video jika ada
function closeChatLightbox() {
    const overlay = document.getElementById('chat-lightbox');
    if (overlay) {
        overlay.classList.remove('active');
    }
    const lightboxVideo = document.getElementById('chat-lightbox-video');
    if (lightboxVideo) {
        lightboxVideo.pause();
        lightboxVideo.src = "";
    }
}

function initLightbox() {
    const lightboxWrapper = document.getElementById('chat-lightbox-wrapper');
    const lightboxImg = document.getElementById('chat-lightbox-img');
    const closeBtn = document.getElementById('chat-lightbox-close');

    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            closeChatLightbox();
        });
    }

    if (lightboxWrapper) {
        // Zoom with mouse scroll wheel
        lightboxWrapper.addEventListener('wheel', function(e) {
            e.preventDefault();
            const zoomIntensity = 0.1;
            if (e.deltaY < 0) {
                // Zoom In
                zoomScale = Math.min(zoomScale + zoomIntensity * zoomScale, 5);
            } else {
                // Zoom Out
                zoomScale = Math.max(zoomScale - zoomIntensity * zoomScale, 0.5);
            }
            updateImageTransform();
        }, { passive: false });

        // Close when clicking directly on wrapper background
        lightboxWrapper.addEventListener('click', function(e) {
            if (e.target === this) {
                closeChatLightbox();
            }
        });
    }

    // Mouse panning events
    if (lightboxImg) {
        lightboxImg.addEventListener('mousedown', function(e) {
            if (e.button !== 0) return; // Only drag with left click
            isPanning = true;
            startX = e.clientX - translateX;
            startY = e.clientY - translateY;
            lightboxImg.style.transition = 'none'; // Disable transition while dragging for instant feedback
            e.preventDefault();
        });

        // Touch events for mobile zooming/panning
        let touchStartX = 0;
        let touchStartY = 0;
        let initialTouchDist = 0;
        let initialScale = 1;

        lightboxImg.addEventListener('touchstart', function(e) {
            if (e.touches.length === 1) {
                isPanning = true;
                touchStartX = e.touches[0].clientX - translateX;
                touchStartY = e.touches[0].clientY - translateY;
                lightboxImg.style.transition = 'none';
            } else if (e.touches.length === 2) {
                isPanning = false;
                initialTouchDist = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                initialScale = zoomScale;
            }
        });

        lightboxImg.addEventListener('touchmove', function(e) {
            if (isPanning && e.touches.length === 1) {
                translateX = e.touches[0].clientX - touchStartX;
                translateY = e.touches[0].clientY - touchStartY;
                updateImageTransform();
                e.preventDefault();
            } else if (e.touches.length === 2) {
                const dist = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                const factor = dist / initialTouchDist;
                zoomScale = Math.min(Math.max(initialScale * factor, 0.5), 5);
                updateImageTransform();
                e.preventDefault();
            }
        });

        lightboxImg.addEventListener('touchend', function() {
            isPanning = false;
            lightboxImg.style.transition = 'transform 0.1s ease-out';
        });
    }

    window.addEventListener('mousemove', function(e) {
        if (!isPanning) return;
        translateX = e.clientX - startX;
        translateY = e.clientY - startY;
        updateImageTransform();
    });

    window.addEventListener('mouseup', function() {
        if (isPanning) {
            isPanning = false;
            const img = document.getElementById('chat-lightbox-img');
            if (img) {
                img.style.transition = 'transform 0.1s ease-out';
            }
        }
    });

    // Control buttons
    const zoomInBtn = document.getElementById('zoom-in-btn');
    const zoomOutBtn = document.getElementById('zoom-out-btn');
    const zoomResetBtn = document.getElementById('zoom-reset-btn');
    const zoomRotateBtn = document.getElementById('zoom-rotate-btn');

    if (zoomInBtn) {
        zoomInBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            zoomScale = Math.min(zoomScale + 0.25, 5);
            updateImageTransform();
        });
    }

    if (zoomOutBtn) {
        zoomOutBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            zoomScale = Math.max(zoomScale - 0.25, 0.5);
            updateImageTransform();
        });
    }

    if (zoomResetBtn) {
        zoomResetBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            zoomScale = 1;
            translateX = 0;
            translateY = 0;
            updateImageTransform();
        });
    }

    if (zoomRotateBtn) {
        zoomRotateBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            rotateAngle = (rotateAngle + 90) % 360;
            updateImageTransform();
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeChatLightbox();
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLightbox);
} else {
    initLightbox();
}
</script>
