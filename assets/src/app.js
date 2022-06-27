import '/assets/styles/app.scss';

'use strict';

let urlForLastPosts       = 'api/post/last/';
let urlForMoreTalkedPosts = '/api/post/moreTalked/';
let outputLastPosts       = document.getElementById('lastPosts');
let outputMoreTalkedPosts = document.getElementById('moreTalkedPosts');

function formatDate(timestamp) {
    let dateOfTimestamp     = new Date(timestamp * 1000);
    let intermediateArchive = [
      '0' + dateOfTimestamp.getDate(),
      '0' + (dateOfTimestamp.getMonth() + 1),
      ''  + dateOfTimestamp.getFullYear(),
      '0' + dateOfTimestamp.getHours(),
      '0' + dateOfTimestamp.getMinutes()
    ].map(component => component.slice(-2));

    return intermediateArchive.slice(0, 3).join('.') + ' в ' + intermediateArchive.slice(3).join(':');
}

function convertPostsToString(posts) {
    let stringOfPosts = '';

    if (posts == undefined) {
        return 'Нет постов для отображения';
    }

    for (let post of posts) {
        stringOfPosts += `
            <div class="card mb-3">
                <div class="row g-0" style="margin: 1rem 0">
                    <div class="col-md-4">
                        <img  class="img-fluid rounded-start" alt="Картинка для поста">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">${post.title.slice(0, 120)}</h5>
                            <p class="card-text">${post.content.slice(0, 250) + '...'}</p>
                            <p class="card-text"><small>${post.author}</small></p>
                            <p class="card-text"><small class="text-muted">${formatDate(post.date_time)}</small></p>
                            <p class="card-text"><small>Рейтинг: ${post.rating}, оценок: ${post.count_ratings}, комментариев: ${post.count_comments}</small></p>
                            <a href="${'/post/' + post.id}" class="btn btn-primary">Перейти</a>
                        </div>
                    </div>
                </div>
            </div>`
        ;
    }

    return stringOfPosts;
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

function getPosts(url, amount, output) {
    let key = 'getPosts' + hash(url, amount);
    let stringOfPosts

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

function hash() {
    return [].join.call(arguments);
}

function cachingDecorator(someFunction) {

    return function () {
        let key = someFunction.name + hash(...arguments);

        if (localStorage.getItem(key) !== null) {
            return localStorage.getItem(key);
        }

        let uncachedValue = someFunction.apply(this, arguments);

        localStorage.setItem(key, uncachedValue);

        return uncachedValue;
    };
}

function updateWithTimeout(someFunction, timeoutInSeconds) {
    return function updatedWithInterval() {
        let someValue = someFunction.apply(this, arguments);

        setTimeout(updatedWithInterval, timeoutInSeconds * 1000);

        return someValue;
    };
}

// getPosts = updateWithTimeout(getPosts, 10);

getPosts(urlForLastPosts, 10, outputLastPosts);

getPosts(urlForMoreTalkedPosts, 3, outputMoreTalkedPosts);


/* for (let i = 0; i < localStorage.length; i++) {
    let key = localStorage.key(i);
    alert(`${key}: ${localStorage.getItem(key)}`);
} */

/* localStorage.clear(); */

