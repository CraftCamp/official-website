var destination = 0;

const doScroll = () => {
    if (window.scrollY === destination) {
        return;
    }
    window.scrollTo(0, (window.scrollY > destination)
        ? ((window.scrollY - 100) < destination) ? destination : window.scrollY - 100
        : ((window.scrollY + 100) > destination) ? destination : window.scrollY + 100
    );
    console.log(window.scrollY);
    requestAnimationFrame(doScroll);
};

const doScrollTo = to => {
    destination = to;
    requestAnimationFrame(doScroll);
};

const scrollToPresentation = () => {
    doScrollTo(document.querySelector('#presentation > section:first-child').offsetTop);
};