const formToJson = form => {
    let jsonObject = {};

    for (const [key, value]  of form.entries()) {
        jsonObject[key] = value;
    }
    return JSON.stringify(jsonObject);
};

const toggleNotifications = () => {
    let notificationContainer = document.querySelector('#notifications');
    notificationContainer.classList.toggle('show');
    if (!notificationContainer.classList.contains('show')) {
        return;
    }
    
    let notifications = document.querySelectorAll('#notifications > div.unread');
    let ids = [];
    
    for (let notification of notifications) {
        notification.classList.remove('unread');
        ids.push(notification.getAttribute('data-id'));
    }
    return fetch('/users/me/notifications/read', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids: ids }),
        credentials: 'include'
    }).then(response => {
        if (response.ok) {
            let counter = document.querySelector('#notifications-counter');
            counter.querySelector('svg').style.color = 'black';
            counter.removeChild(counter.querySelector('span'));
        }
    });
};