document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');
    const messageArea = document.getElementById('messageArea');
    const galleryGrid = document.getElementById('galleryGrid');
    const galleryUpload = document.getElementById('galleryUpload');
    const galleryInput = document.getElementById('galleryInput');
    const coverPhotoBtn = document.getElementById('coverPhotoBtn');
    const coverPhotoInput = document.getElementById('coverPhotoInput');
    const addCoverPhotoBtn = document.getElementById('addCoverPhoto');

    const galleryImages = [];
    let selectedGalleryItem = null;

    const config = {
        maxGalleryImages: 12,
        maxImageSize: 5 * 1024 * 1024,
        allowedImageTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp']
    };

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const tabId = tab.getAttribute('data-tab');
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            tabContents.forEach(content => {
                content.classList.remove('active');
                if (content.id === `${tabId}-tab`) {
                    content.classList.add('active');
                }
            });
        });
    });

    galleryInput.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            const files = Array.from(this.files);
            if (galleryImages.length + files.length > config.maxGalleryImages) {
                showMessage(`You can only upload a maximum of ${config.maxGalleryImages} gallery images`, 'error');
                return;
            }
            files.forEach(file => {
                if (validateImageFile(file)) {
                    addGalleryImage(file);
                }
            });
            this.value = '';
        }
    });

    galleryUpload.addEventListener('click', function() {
        galleryInput.click();
    });

    coverPhotoBtn?.addEventListener('click', function() {
        coverPhotoInput.click();
    });

    addCoverPhotoBtn?.addEventListener('click', function() {
        if (selectedGalleryItem !== null) {
            showMessage('Selected photo set as cover photo!', 'info');
            selectedGalleryItem = null;
            document.querySelectorAll('.gallery-item').forEach(item => {
                item.style.border = 'none';
            });
        } else {
            showMessage('Please select a photo first', 'error');
        }
    });

    function validateImageFile(file) {
        if (!config.allowedImageTypes.includes(file.type)) {
            showMessage(`File type not supported: ${file.type}`, 'error');
            return false;
        }
        if (file.size > config.maxImageSize) {
            showMessage(`Image ${file.name} exceeds the 5MB limit`, 'error');
            return false;
        }
        return true;
    }

    function addGalleryImage(file) {
        const imageId = Date.now().toString(36) + Math.random().toString(36).substr(2);
        const galleryItem = document.createElement('div');
        galleryItem.className = 'gallery-item';
        galleryItem.dataset.imageId = imageId;

        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);

        galleryItem.addEventListener('click', function() {
            if (selectedGalleryItem === imageId) {
                selectedGalleryItem = null;
                this.style.border = 'none';
            } else {
                if (selectedGalleryItem !== null) {
                    const prevSelected = document.querySelector(`.gallery-item[data-image-id="${selectedGalleryItem}"]`);
                    if (prevSelected) {
                        prevSelected.style.border = 'none';
                    }
                }
                selectedGalleryItem = imageId;
                this.style.border = '3px solid #3498db';
            }
        });

        const removeBtn = document.createElement('button');
        removeBtn.className = 'remove-btn';
        removeBtn.innerHTML = '×';
        removeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            removeGalleryImage(imageId);
        });

        galleryItem.appendChild(img);
        galleryItem.appendChild(removeBtn);
        galleryGrid.insertBefore(galleryItem, galleryUpload);

        galleryImages.push({ id: imageId, file: file });
        showMessage(`Added ${file.name} to your gallery`, 'info');
    }

    function removeGalleryImage(imageId) {
        const galleryItem = document.querySelector(`.gallery-item[data-image-id="${imageId}"]`);
        if (galleryItem) {
            const img = galleryItem.querySelector('img');
            if (img) URL.revokeObjectURL(img.src);
            galleryItem.remove();
        }
        const index = galleryImages.findIndex(item => item.id === imageId);
        if (index !== -1) galleryImages.splice(index, 1);
        if (selectedGalleryItem === imageId) selectedGalleryItem = null;
        showMessage('Image removed from gallery', 'info');
    }

    function showMessage(message, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}`;
        messageDiv.textContent = message;
        messageArea.innerHTML = '';
        messageArea.appendChild(messageDiv);
        if (type !== 'error') {
            setTimeout(() => {
                messageDiv.remove();
            }, 5000);
        }
    }

    document.querySelector('.profile-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData();
        formData.append('name', document.getElementById('displayName').value);
        formData.append('location', document.getElementById('location').value);
        formData.append('bio', document.getElementById('bio').value);
        fetch('profile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.ok ? showMessage('Profile settings saved successfully!', 'info') : showMessage('Error saving settings.', 'error'))
        .catch(() => showMessage('Server error.', 'error'));
    });

    async function loadLeaderboard() {
        try {
            const response = await fetch('fetch_leaderboard.php');
            const data = await response.json();
            const tbody = document.getElementById('leaderboard-body');
            tbody.innerHTML = '';
            data.forEach((item, index) => {
                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.place}</td>
                        <td>${item.travelers_count}</td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        } catch (error) {
            console.error("Error loading leaderboard:", error);
        }
    }

    document.querySelector('[data-tab="leaderboard"]')?.addEventListener('click', loadLeaderboard);
});
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-cancel').forEach(btn => {
        btn.addEventListener('click', function () {
            const bookingId = this.dataset.id;
            const type = this.dataset.type;

            if (confirm('Are you sure you want to cancel this booking?')) {
                fetch('cancel_booking.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: bookingId, type: type })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.closest('tr').remove(); // remove row from table
                    } else {
                        alert('Failed to cancel booking.');
                    }
                })
                .catch(err => {
                    console.error('Cancel error:', err);
                    alert('Error occurred while cancelling.');
                });
            }
        });
    });
});
