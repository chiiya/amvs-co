function matchYoutubeUrl(url) {
    var p = /^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
    if(url.match(p)){
        return url.match(p)[1];
    }
    return false;
}

function matchVimeoUrl(url) {
    var p = /^(?:https?:\/\/)?(?:m\.|www\.)?vimeo\.com\/(\d+)($|\/)/;
    if (url.match(p)) {
        return url.match(p)[1];
    }
    return false;
}

function matchDriveUrl(url) {
    var p = /^(?:https?:\/\/)?(?:m\.|www\.)?drive\.google\.com\/(?:open\?id=|file\/d\/)(\w+)($|\/.+)/;
    if (url.match(p)) {
        return url.match(p)[1];
    }
    return false;
}

export { matchYoutubeUrl, matchVimeoUrl, matchDriveUrl };