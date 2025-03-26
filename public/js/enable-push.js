const enablePush = async () => {
    try {
        console.log('Registering service worker...');

        const registration = await navigator.serviceWorker.register('/service-worker.js');
        console.log('Service worker registered');

        const permission = await Notification.requestPermission();
        if (permission !== 'granted') {
            throw new Error('Permission not granted for Notification');
        }

        const subscribeOptions = {
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(
                '{{ env("VAPID_PUBLIC_KEY") }}'
            )
        };

        const pushSubscription = await registration.pushManager.subscribe(subscribeOptions);
        console.log('Received PushSubscription:', JSON.stringify(pushSubscription));

        await sendSubscriptionToBackEnd(pushSubscription);
        console.log('Push notification subscription successful');

    } catch (err) {
        console.error('Error enabling push notifications:', err);
    }
};

const sendSubscriptionToBackEnd = async (subscription) => {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const response = await fetch('/push-subscriptions', {
        method: 'POST',
        body: JSON.stringify(subscription),
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-Token': token
        }
    });

    if (!response.ok) {
        throw new Error('Bad status code from server.');
    }

    const responseData = await response.json();
    if (!(responseData && responseData.success)) {
        throw new Error('Bad response from server.');
    }
};

const urlBase64ToUint8Array = (base64String) => {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
};

// Check if service workers are supported
if ('serviceWorker' in navigator && 'PushManager' in window) {
    window.addEventListener('load', () => {
        const enablePushButton = document.querySelector('.js-enable-push');
        if (enablePushButton) {
            enablePushButton.addEventListener('click', () => {
                enablePush();
            });
        }
    });
} else {
    console.warn('Push notifications are not supported by this browser');
} 