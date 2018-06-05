const joinProject = slug => fetch(`/projects/${slug}/join`, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    credentials: 'include'
}).then(response => response.json())
.then(membership => {
    var list = document.querySelector("#project-members > section");
    
    var element = document.createElement('div');
    element.classList.add('member');
    element.innerText = membership.user.username;
    list.appendChild(element);
    
    document.querySelector('#project-members > footer').removeChild(document.querySelector('#join-button'));
});

const putProjectDetails = slug => fetch(`/projects/${slug}/details`, {
    method: 'PUT',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        need_description: editors.needDescription.getData(),
        target_description: editors.targetDescription.getData(),
        goal_description: editors.goalDescription.getData()
    }),
    credentials: 'include'
}).then(response => response.json())
.then(data => {
    window.location = `/projects/${slug}/workspace`;
});