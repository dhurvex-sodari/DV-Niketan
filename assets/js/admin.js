// assets/js/admin.js - Admin Interactivity & Helpers

document.addEventListener('DOMContentLoaded', () => {
    // 1. File Upload Image Preview
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                const previewTarget = document.querySelector(this.getAttribute('data-preview-target'));
                if (previewTarget) {
                    reader.onload = function(e) {
                        previewTarget.src = e.target.result;
                        previewTarget.style.display = 'block';
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            }
        });
    });

    // 2. Delete Confirmation
    document.querySelectorAll('.confirm-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });

    // 3. Auto-hide alerts after 4 seconds
    const alerts = document.querySelectorAll('.alert-auto-dismiss');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    });
});
