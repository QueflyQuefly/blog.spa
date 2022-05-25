import '/assets/styles/app.scss';

import {h, render} from 'preact';
import {Router, Link} from 'preact-router';
import {useState, useEffect} from 'preact/hooks';

import {findLastPosts} from './api/api';
import Home from './pages/home';
import Post from './pages/post';

function App() {
    const [posts, setPosts] = useState(null);

    useEffect(() => {
        findLastPosts().then((posts) => setPosts(posts));
    }, []);


    if (posts === null) {
        return <div className="text-center pt-5">Loading...</div>;
    }

    return (
        <div>
            <nav className="bg-light border-bottom text-center">
                {posts.map((post) => (
                    <Link className="nav-conference" href={'/post/'+post.id}>
                        {post.title} {post.id}
                    </Link>
                ))}
            </nav>

            <Router>
                <Home path="/" posts={posts} />
                <Post path="/post/:id" posts={posts} />
            </Router>
        </div>
    )
}

render(<App />, document.getElementById('app'));