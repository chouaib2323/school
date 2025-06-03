import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import Navbar from './Navbar';
import Footer from './Footer';
import SwiperC from './SwiperC';
import SwiperMain from './SwiperMain';

function PostDetail() {
  const { id } = useParams();
  const [post, setPost] = useState(null);

  useEffect(() => {
    fetch(`https://localhost/school/api.php?id=${id}`)
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        if (data && data.posts_with_photo) {
          setPost(data.posts_with_photo.find(item => item.id === id));
        } else {
          console.error('Post data not found in response');
        }
      })
      .catch(error => {
        console.error('Error fetching data:', error);
      });
  }, [id]);

  if (!post) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <p className="text-gray-600 text-lg">Loading...</p>
      </div>
    );
  }

  return (
    <>
      <Navbar />
      <div className="post-detail p-8 bg-gray-100 min-h-screen">
        <div className="container mx-auto">
          <div className="bg-white p-6 rounded-lg shadow-lg">
            <h1 className="text-4xl font-bold text-gray-800 mb-4">{post.title}</h1>
            <p className="text-gray-700 text-lg mb-6">{post.subject}</p>
            <SwiperMain id={id}/>
            <Link
              to="/Posts"
              className="inline-block bg-orange-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition duration-200"
            >
              Back to Posts
            </Link>
          </div>
        </div>
      </div>
      <Footer />
    </>
  );
}

export default PostDetail;
