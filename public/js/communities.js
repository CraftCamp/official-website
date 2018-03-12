const createCommunity = event => {
    event.preventDefault();
    
    return fetch("/communities", {
            method: "POST",
            body: new FormData(event.target),
            credentials: 'include'
        })
        .then(response => response.json())
        .then(response => {
            if (!response.error) {
                window.location = `/communities/${response.slug}`;
                return;
            }
            document.querySelector(".form-error").innerHTML = `<p>${response.error.message}</p>`;
        })
        .catch(error => {
            console.log(error);
        })
    ;
};

const previewPicture = event => {
    if (!event.target.files || !event.target.files[0]) {
        return;
    }
    var reader = new FileReader();

    reader.onload = e => {
        var image = document.querySelector("#community-picture > label > img");
        image.classList.add('preview');
        image.src = e.target.result;
    };

    reader.readAsDataURL(event.target.files[0]);
};