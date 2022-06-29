'use strict';

import '/assets/styles/app.scss';
import {getLastPosts, getMoreTalkedPosts, getPost} from './api/api';
import './functions/functions';

let outputLastPosts       = document.getElementById('lastPosts');
let outputMoreTalkedPosts = document.getElementById('moreTalkedPosts');
let outputPost            = document.getElementById('post');

// getPosts = updateWithTimeout(getPosts, 10);

if (outputLastPosts) {
    getLastPosts(10, outputLastPosts);
}

if (outputMoreTalkedPosts) {
    getMoreTalkedPosts(3, outputMoreTalkedPosts);
}

if (outputPost) {
    let postId = window.location.href.split('/').pop();
    getPost(postId, outputPost);
}

/* for (let i = 0; i < localStorage.length; i++) {
    let key = localStorage.key(i);
    alert(`${key}: ${localStorage.getItem(key)}`);
} */

/* localStorage.clear(); */

