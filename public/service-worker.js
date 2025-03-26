self.addEventListener('push', function (event) {
    if (event.data) {
        const data = event.data.json();

        const options = {
            body: data.body,
            icon: data.icon || '/favicon.ico',
            badge: data.badge || '/favicon.ico',
            data: data.data || {},
            actions: data.actions || [],
            vibrate: data.vibrate || [100, 50, 100],
            tag: data.tag || 'default',
            renotify: data.renotify || false
        };

        event.waitUntil(
            self.registration.showNotification(data.title, options)
        );
    }
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    const clickAction = event.notification.data.url || '/';

    event.waitUntil(
        clients.openWindow(clickAction)
    );
}); 