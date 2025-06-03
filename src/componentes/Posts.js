import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import Navbar from './Navbar';
import Footer from './Footer';

function Posts() {
  const [posts, setPosts] = useState([]);

  useEffect(() => {
    fetch('https://localhost/school/api.php')
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        if (data && data.posts_with_photo) {
          setPosts(data.posts_with_photo);
        } else {
          console.error('Posts data not found in response');
        }
      })
      .catch(error => {
        console.error('Error fetching data:', error);
      });
  }, []);

  return (
    <>
      <Navbar />
      <div className="posts-container p-4 bg-gray-100 min-h-screen">
        <div className="container mx-auto">
          <div className="flex justify-between items-center mb-6">
            <h1 className="text-3xl font-bold text-gray-800">الاخبار</h1>
          </div>
          {posts.length === 0 ? (
            <p className="text-center text-gray-600">No posts available</p>
          ) : (
            posts.map(post => (
              <Link
                key={post.id}
                to={`/Posts/${post.id}`}
                className="block post bg-white p-6 mb-4 shadow-md rounded-lg cursor-pointer  "
              >
                <h3 className="text-xl font-semibold text-gray-900">{post.title}</h3>
                <p className="text-gray-700 mt-2 max-h-16 overflow-hidden ">{post.subject}</p>
              </Link>
            ))
          )}
        </div>
      </div>
      <Footer />
    </>
  );
}

export default Posts;
