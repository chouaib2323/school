import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import Navbar from './Navbar';
import Footer from './Footer';
        
function Announcements() {
  const [announcements, setAnnouncements] = useState([]);
  useEffect(() => {
    fetch('https://localhost/school/api.php')
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        if (data && data.anouncment) {
          setAnnouncements(data.anouncment);
        } else {
          console.error('Announcement data not found in response');
        }
      })
      .catch(error => {
        console.error('Error fetching data:', error);
      });
  }, []);

  return (
    <>
      <Navbar />
      <div className="announcements-container p-4 bg-gray-100 min-h-screen">
        <div className="container mx-auto">
          <div className="flex justify-between items-center mb-6">
            <h1 className="text-3xl font-bold text-gray-800">الاعلانات</h1>
          </div>
          {announcements.length === 0 ? (
            <p className="text-center text-gray-600">No announcements available</p>
          ) : (
            announcements.map(announcement => (
              <Link
                key={announcement.id}
                to={`/Anouncements/${announcement.id}`}
                className="block announcement bg-white p-6 mb-4 shadow-md rounded-lg cursor-pointer"
              >
                <h3 className="text-xl font-semibold text-gray-900">{announcement.title}</h3>
                <p className="text-gray-700 mt-2">{announcement.subject}</p>
              </Link>
            ))
          )}
        </div>
      </div>
      <Footer />
    </>
  );
}

export default Announcements;
