'use strict';

import '/assets/styles/app.scss';
import {getLastPosts, getMoreTalkedPosts} from './api/api';
import './functions/functions';

let outputLastPosts       = document.getElementById('lastPosts');
let outputMoreTalkedPosts = document.getElementById('moreTalkedPosts');

// getPosts = updateWithTimeout(getPosts, 10);

getLastPosts(10, outputLastPosts);

getMoreTalkedPosts(3, outputMoreTalkedPosts);


/* for (let i = 0; i < localStorage.length; i++) {
    let key = localStorage.key(i);
    alert(`${key}: ${localStorage.getItem(key)}`);
} */

/* localStorage.clear(); */

