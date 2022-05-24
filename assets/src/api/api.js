function fetchCollection(path) {
    return fetch('/' + path).then(resp => resp.json()).then(json => json['hydra:member']);
}

export function findConferences() {
    return fetchCollection('api/post/last/10');
}

export function findComments(conference) {
    return fetchCollection('api/comments?conference='+conference.id);
}