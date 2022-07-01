"use strict";

let errorString = '<p style="text-align: center; font-size: 14pt; margin-top: 2vh;">Нет информации для отображения</p>';

export function formatDate(timestamp) {
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

export function convertPostsToString(posts) {
    let stringOfPosts = '';

    if (posts == undefined || isEmpty(posts)) {
        return errorString;
    }

    if (! Array.isArray(posts)) {
        return `<h1 class="display-6" style='margin: 2rem 0'>${posts.title}</h1>
            <p><small>Рейтинг: ${posts.rating}, количество оценок: ${posts.count_ratings}, количество комментариев: ${posts.count_comments}</small></p>
            <p>Автор: ${posts.author}</p>
            <p><small class="text-muted">${formatDate(posts.date_time)}</small></p>
            <p>${posts.content}</p>`
        ;
    }

    for (let post of posts) {
        stringOfPosts += `<div class="card mb-3">
                <div class="row g-0" style="margin: 1rem 0">
                    <div class="col-md-4">
                        <img  class="img-fluid rounded-start" alt="Картинка для поста">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">${post.title.slice(0, 120)}</h5>
                            <p class="card-text">${post.content.slice(0, 250) + '...'}</p>
                            <p class="card-text"><small>${post.author} </small> <small class="text-muted"> ${formatDate(post.date_time)}</small></p>
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

export function hash() {
    return [].join.call(arguments);
}

export function cachingDecorator(someFunction) {

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

export function updateWithTimeout(someFunction, timeoutInSeconds) {
    return function updatedWithInterval() {
        let someValue = someFunction.apply(this, arguments);

        setTimeout(updatedWithInterval, timeoutInSeconds * 1000);

        return someValue;
    };
}

export function isEmpty(obj) {
    for (let key in obj) {
      return false;
    }

    return true;
}