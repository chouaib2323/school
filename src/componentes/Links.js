import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import Navbar from './Navbar';
import Footer from './Footer';

function AnnouncementDetail() {
  const { id } = useParams();
  const [announcement, setAnnouncement] = useState(null);

  useEffect(() => {
    fetch(`https://localhost/school/api.php`) // Adjust API endpoint as needed
      .then(response => response.json())
      .then(data => {
        if (data && data.anouncment) {
          setAnnouncement(data.anouncment.find(item => item.id === id));
        } else {
          console.error('Announcement data not found in response');
        }
      })
      .catch(error => {
        console.error('Error fetching data:', error);
      });
  }, [id]);

  if (!announcement) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gradient-to-r from-indigo-300 via-purple-300 to-pink-300">
        <p className="text-gray-600 text-lg animate-pulse">Loading...</p>
      </div>
    );
  }

  return (
    <>
      <Navbar />
      <div className="announcement-detail p-8 bg-gradient-to-r from-orange-100 via-gray-100 to-pink-100 min-h-screen">
        <div className="container mx-auto">
          <div className="bg-white p-6 rounded-lg shadow-xl">
           
            <h2 className="text-3xl font-bold text-gray-800 mb-4">Important Links:</h2>
            {announcement.links && announcement.links.map((e) => (
              <div key={e.id} className="bg-gray-100 p-4 mb-4 rounded-lg shadow-inner">
                <h3 className="text-2xl font-semibold text-gray-900 mb-2">{e.link_title}</h3>
                <a href={e.link_url} className="text-blue-500 hover:underline text-lg">{e.link_url}</a>
              </div>
            ))}
            
            <Link
              to="/Anouncements"
              className="inline-block mt-6 text-white bg-orange-500 hover:bg-orange-600 px-4 py-2 rounded-full transition duration-200 transform hover:scale-105"
            >
              Back to Announcements
            </Link>
          </div>
        </div>
      </div>
      <Footer />
    </>
  );
}

export default AnnouncementDetail;
