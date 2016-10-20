require('./bootstrap');
window.Laravel = {
    csrfToken: document.querySelector("meta[name='csrf_token']").getAttribute('content')
}