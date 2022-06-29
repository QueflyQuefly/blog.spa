"use strict";

import {convertPostsToString, hash} from '../functions/functions';

let urlForLastPosts       = 'api/post/last/';
let urlForMoreTalkedPosts = '/api/post/more_talked/';
let urlForPost            = '/api/post/';

function getPosts(url, amount, output) {
    //let key = 'getPosts' + hash(url, amount);
    let stringOfPosts;

    /* if (localStorage.getItem(key) = null) {
        output.innerHTML = localStorage.getItem(key);
        alert( localStorage.getItem(key));
    } else { */
        fetch(url + amount).then(
            (response) => {
                response.text().then(
                    (text) => {
                        stringOfPosts = convertPostsToString(JSON.parse(text));
                        output.innerHTML = stringOfPosts;
                        //localStorage.setItem(key, stringOfPosts);
                    }
                );
            }
        )
    /* } */
}

export function getLastPosts(amount, output) {
    getPosts(urlForLastPosts, amount, output);
}

export function getMoreTalkedPosts(amount, output) {
    getPosts(urlForMoreTalkedPosts, amount, output);
}

export function getPost(postId, output) {
    getPosts(urlForPost, postId, output);
}

/* function getPosts(url, amount) {
    let request = new XMLHttpRequest();
    let response;

    request.open('GET', url + amount);
    request.responseType = 'text';

    let key = hash(url, amount);
    
    request.onload = function() {
        let response      = JSON.parse(request.response);
        let uncachedValue = convertPostsToString(response);

        localStorage.setItem(key, uncachedValue);
    };
    
    request.send();
} */