const createMember = event => {
    event.preventDefault();
    
    return fetch("/members", {
            method: "POST",
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: formToJson(new FormData(event.target)),
            credentials: 'include'
        })
        .then(response => response.json())
        .then(response => {
            if (!response.error) {
                window.location = '/members/dashboard';
                return;
            }
            document.querySelector(".form-error").innerHTML = `<p>${response.error.message}</p>`;
        })
        .catch(error => {
            console.log(error);
        })
    ;
};