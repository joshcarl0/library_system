/**
 * real-time notification polling for Student & Faculty Dashboards
 */
(function() {
    // Determine which script tag loaded this file and read its data-api-url
    const scriptTag = document.currentScript ||
        document.querySelector('script[src*="notifications.js"]');
    const API_URL = (scriptTag && scriptTag.dataset.apiUrl)
        ? scriptTag.dataset.apiUrl
        : '/library_system/index.php?action=api_get_notifications';

    const NOTIF_DOT   = document.getElementById('notif-dot');
    const NOTIF_COUNT = document.getElementById('notif-count');
    const NOTIF_LIST  = document.getElementById('notif-list');
    const POLL_INTERVAL = 30000; // 30 seconds

    function formatTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { month: 'short', day: '2-digit' }) + ', ' + 
               date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
    }

    function updateNotifications() {
        fetch(API_URL)
            .then(response => response.json())
            .then(data => {
                if (data.error) return;

                // Update Unread Count & Dot
                if (data.unreadCount > 0) {
                    if (NOTIF_DOT) NOTIF_DOT.style.display = 'block';
                    if (NOTIF_COUNT) {
                        NOTIF_COUNT.textContent = `${data.unreadCount} New`;
                        NOTIF_COUNT.style.display = 'inline-block';
                    }
                } else {
                    if (NOTIF_DOT) NOTIF_DOT.style.display = 'none';
                    if (NOTIF_COUNT) NOTIF_COUNT.style.display = 'none';
                }

                // Update Notification List
                if (NOTIF_LIST && data.notifications) {
                    if (data.notifications.length === 0) {
                        NOTIF_LIST.innerHTML = `
                            <div class="p-4 text-center text-muted small">
                                <i class="bi bi-bell-slash d-block fs-2 mb-2 opacity-25"></i>
                                No notifications yet
                            </div>`;
                    } else {
                        let html = '';
                        data.notifications.forEach(notif => {
                            let rnIcon = 'bi-bell';
                            let rnColor = 'text-primary bg-primary-subtle';
                            
                            if (notif.type === 'system') {
                                rnIcon = 'bi-megaphone';
                                rnColor = 'text-success bg-success-subtle';
                            } else if (notif.type === 'loan') {
                                rnIcon = 'bi-journal-check';
                                rnColor = 'text-warning bg-warning-subtle';
                            }

                            const isUnread = notif.is_read == 0 ? 'unread' : '';
                            const timeStr = formatTime(notif.created_at);

                            html += `
                                <a href="/library_system/index.php?action=student_notifications" class="notification-item ${isUnread}">
                                    <div class="ni-icon ${rnColor}">
                                        <i class="bi ${rnIcon}"></i>
                                    </div>
                                    <div class="ni-content">
                                        <div class="ni-text">${escapeHtml(notif.title)}</div>
                                        <div class="ni-time">${timeStr}</div>
                                    </div>
                                </a>`;
                        });
                        NOTIF_LIST.innerHTML = html;
                    }
                }
            })
            .catch(err => console.error('Error fetching notifications:', err));
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Initial call and start interval
    if (NOTIF_LIST) {
        setInterval(updateNotifications, POLL_INTERVAL);
    }
})();
