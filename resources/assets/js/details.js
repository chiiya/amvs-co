const   likes = document.getElementsByClassName('like'),
        unlikes = document.getElementsByClassName('unlike'),
        unfilled = document.getElementsByClassName('amv__like--unfilled'),
        filled = document.getElementsByClassName('amv__like--filled')
        data = {
            amv_id : document.head.querySelector("[name=amv]").id
        };

var like = function() {
    Array.from(unfilled).forEach(function(element) {
      element.style.display="none";
    });
    Array.from(filled).forEach(function(element) {
      element.style.display="block";
    });
    Vue.http.post(`/api/amvs/${data.amv_id}/likes`, data, {
        headers: {
            'Content-Type': 'application/json'
        }
        });
}

var unlike = function() {
    Array.from(unfilled).forEach(function(element) {
      element.style.display="block";
    });
    Array.from(filled).forEach(function(element) {
      element.style.display="none";
    });
    Vue.http.delete(`/api/amvs/${data.amv_id}/likes`);
}

Array.from(likes).forEach(function(element) {
      element.addEventListener('click', like);
});

Array.from(unlikes).forEach(function(element) {
      element.addEventListener('click', unlike);
});
