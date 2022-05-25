function fetchCollection(path) {
    return fetch('/' + path).then(resp => resp.json()).then(json => json['hydra:member']);
}

export function findLastPosts() {
    return fetchCollection('api/post/last/10');
}

export function findComments(post) {
    return fetchCollection('api/comments?post='+post.id);
}