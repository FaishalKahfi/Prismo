// Notification Permission Modal - Prismo
class NotificationPermissionModal {
    constructor() {
        this.modalId = 'notificationPermissionModal';
        this.storageKey = 'prismo_notification_permission_asked';
        this.modal = null;
    }

    // Check if we should show the modal
    shouldShow() {
        // Don't show if browser doesn't support notifications
        if (!('Notification' in window)) {
            return false;
        }

        // Don't show if already granted or denied
        if (Notification.permission !== 'default') {
            return false;
        }

        // Don't show if user already dismissed
        const hasAsked = localStorage.getItem(this.storageKey);
        if (hasAsked === 'true') {
            return false;
        }

        return true;
    }

    // Create modal HTML
    createModal() {
        const modalHTML = `
            <div id="${this.modalId}" class="notification-permission-overlay" style="display: none;">
                <div class="notification-permission-modal">
                    <div class="notification-permission-header">
                        <div class="notification-permission-icon">
                            🔔
                        </div>
                        <h3 class="notification-permission-title">Aktifkan Notifikasi</h3>
                    </div>
                    
                    <div class="notification-permission-body">
                        <p class="notification-permission-text">
                            Dapatkan pembaruan real-time untuk:
                        </p>
                        <ul class="notification-permission-features">
                            <li>📦 Status booking dan pesanan</li>
                            <li>💰 Promo dan voucher baru</li>
                            <li>✅ Konfirmasi pembayaran</li>
                            <li>📢 Pengumuman penting</li>
                        </ul>
                        <p class="notification-permission-note">
                            Anda dapat menonaktifkan notifikasi kapan saja di pengaturan browser.
                        </p>
                    </div>
                    
                    <div class="notification-permission-actions">
                        <button id="notificationPermissionAllow" class="notification-permission-btn notification-permission-btn-primary">
                            <span class="notification-permission-btn-icon">✓</span>
                            Izinkan Notifikasi
                        </button>
                        <button id="notificationPermissionLater" class="notification-permission-btn notification-permission-btn-secondary">
                            Nanti Saja
                        </button>
                    </div>
                </div>
            </div>
        `;

        // Add styles
        const styles = `
            <style>
                .notification-permission-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-color: rgba(0, 0, 0, 0.75);
                    z-index: 99999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 1rem;
                    animation: fadeIn 0.3s ease-in-out;
                    backdrop-filter: blur(4px);
                }

                @keyframes fadeIn {
                    from {
                        opacity: 0;
                    }
                    to {
                        opacity: 1;
                    }
                }

                @keyframes slideUp {
                    from {
                        transform: translateY(20px);
                        opacity: 0;
                    }
                    to {
                        transform: translateY(0);
                        opacity: 1;
                    }
                }

                .notification-permission-modal {
                    background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
                    border-radius: 1rem;
                    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
                    max-width: 500px;
                    width: 100%;
                    overflow: hidden;
                    animation: slideUp 0.4s ease-out;
                    border: 1px solid rgba(59, 130, 246, 0.2);
                }

                .notification-permission-header {
                    padding: 2rem 2rem 1.5rem;
                    text-align: center;
                    background: linear-gradient(180deg, rgba(59, 130, 246, 0.1) 0%, transparent 100%);
                }

                .notification-permission-icon {
                    font-size: 3rem;
                    margin-bottom: 1rem;
                    animation: bounce 1s ease-in-out infinite;
                }

                @keyframes bounce {
                    0%, 100% {
                        transform: translateY(0);
                    }
                    50% {
                        transform: translateY(-10px);
                    }
                }

                .notification-permission-title {
                    color: #ffffff;
                    font-size: 1.5rem;
                    font-weight: 700;
                    margin: 0;
                    letter-spacing: -0.02em;
                }

                .notification-permission-body {
                    padding: 1.5rem 2rem;
                    color: #e5e7eb;
                }

                .notification-permission-text {
                    margin: 0 0 1rem;
                    font-size: 1rem;
                    line-height: 1.5;
                    color: #d1d5db;
                }

                .notification-permission-features {
                    list-style: none;
                    padding: 0;
                    margin: 0 0 1.5rem;
                }

                .notification-permission-features li {
                    padding: 0.75rem 0;
                    font-size: 0.95rem;
                    color: #e5e7eb;
                    border-bottom: 1px solid rgba(75, 85, 99, 0.3);
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .notification-permission-features li:last-child {
                    border-bottom: none;
                }

                .notification-permission-note {
                    font-size: 0.875rem;
                    color: #9ca3af;
                    margin: 0;
                    font-style: italic;
                    line-height: 1.5;
                }

                .notification-permission-actions {
                    padding: 1.5rem 2rem 2rem;
                    display: flex;
                    flex-direction: column;
                    gap: 0.75rem;
                    background: rgba(17, 24, 39, 0.5);
                }

                .notification-permission-btn {
                    padding: 1rem 1.5rem;
                    border-radius: 0.75rem;
                    border: none;
                    font-size: 1rem;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.5rem;
                    font-family: inherit;
                }

                .notification-permission-btn-primary {
                    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
                    color: #ffffff;
                    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
                }

                .notification-permission-btn-primary:hover {
                    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
                    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
                    transform: translateY(-2px);
                }

                .notification-permission-btn-primary:active {
                    transform: translateY(0);
                    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
                }

                .notification-permission-btn-secondary {
                    background: rgba(75, 85, 99, 0.3);
                    color: #d1d5db;
                    border: 1px solid rgba(75, 85, 99, 0.5);
                }

                .notification-permission-btn-secondary:hover {
                    background: rgba(75, 85, 99, 0.5);
                    color: #ffffff;
                    border-color: rgba(75, 85, 99, 0.7);
                }

                .notification-permission-btn-icon {
                    font-size: 1.1rem;
                    font-weight: 700;
                }

                /* Mobile responsive */
                @media (max-width: 640px) {
                    .notification-permission-modal {
                        margin: 0 1rem;
                        max-width: calc(100% - 2rem);
                    }

                    .notification-permission-header {
                        padding: 1.5rem 1.5rem 1rem;
                    }

                    .notification-permission-icon {
                        font-size: 2.5rem;
                    }

                    .notification-permission-title {
                        font-size: 1.25rem;
                    }

                    .notification-permission-body {
                        padding: 1rem 1.5rem;
                    }

                    .notification-permission-text {
                        font-size: 0.95rem;
                    }

                    .notification-permission-features li {
                        padding: 0.6rem 0;
                        font-size: 0.9rem;
                    }

                    .notification-permission-note {
                        font-size: 0.8rem;
                    }

                    .notification-permission-actions {
                        padding: 1rem 1.5rem 1.5rem;
                    }

                    .notification-permission-btn {
                        padding: 0.875rem 1.25rem;
                        font-size: 0.95rem;
                    }
                }

                /* Tablet */
                @media (min-width: 641px) and (max-width: 768px) {
                    .notification-permission-modal {
                        max-width: 480px;
                    }
                }

                /* Desktop large */
                @media (min-width: 1024px) {
                    .notification-permission-modal {
                        max-width: 520px;
                    }

                    .notification-permission-actions {
                        flex-direction: row;
                    }

                    .notification-permission-btn {
                        flex: 1;
                    }
                }
            </style>
        `;

        // Inject styles and modal into document
        document.head.insertAdjacentHTML('beforeend', styles);
        document.body.insertAdjacentHTML('beforeend', modalHTML);

        this.modal = document.getElementById(this.modalId);
        this.attachEventListeners();
    }

    // Attach event listeners
    attachEventListeners() {
        const allowBtn = document.getElementById('notificationPermissionAllow');
        const laterBtn = document.getElementById('notificationPermissionLater');

        allowBtn.addEventListener('click', () => this.handleAllow());
        laterBtn.addEventListener('click', () => this.handleLater());

        // Close on overlay click
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.handleLater();
            }
        });

        // Close on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.modal.style.display === 'flex') {
                this.handleLater();
            }
        });
    }

    // Handle allow button
    async handleAllow() {
        try {
            // Request permission through browser notification manager if available
            if (window.browserNotification && typeof window.browserNotification.requestPermission === 'function') {
                await window.browserNotification.requestPermission();
            } else {
                // Fallback to direct Notification API
                await Notification.requestPermission();
            }

            // Mark as asked
            localStorage.setItem(this.storageKey, 'true');

            // Close modal
            this.close();
        } catch (error) {
            console.error('Error requesting notification permission:', error);
            alert('Terjadi kesalahan saat meminta izin notifikasi. Silakan coba lagi.');
        }
    }

    // Handle later button
    handleLater() {
        // Mark as asked so it doesn't show again immediately
        localStorage.setItem(this.storageKey, 'true');
        this.close();
    }

    // Show modal
    show() {
        if (this.modal) {
            this.modal.style.display = 'flex';
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }
    }

    // Close modal
    close() {
        if (this.modal) {
            this.modal.style.display = 'none';
            // Restore body scroll
            document.body.style.overflow = '';
        }
    }

    // Initialize and show if needed
    init() {
        if (this.shouldShow()) {
            this.createModal();
            // Show modal after a short delay for better UX
            setTimeout(() => {
                this.show();
            }, 2000); // Show after 2 seconds
        }
    }

    // Reset permission prompt (for testing)
    static reset() {
        localStorage.removeItem('prismo_notification_permission_asked');
        console.log('Notification permission prompt reset. Reload page to see it again.');
    }
}

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        const permissionModal = new NotificationPermissionModal();
        permissionModal.init();
    });
} else {
    const permissionModal = new NotificationPermissionModal();
    permissionModal.init();
}

// Expose reset function globally for debugging
window.resetNotificationPrompt = () => NotificationPermissionModal.reset();
