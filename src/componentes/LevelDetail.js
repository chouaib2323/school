import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import Navbar from './Navbar';
import Footer from './Footer';

function LevelDetail() {
  const { id } = useParams(); // Get the level ID from the URL
  const [levelDetail, setLevelDetail] = useState(null);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch(`https://localhost/school/api.php?id=${id}`)  // Use dynamic 'id' from useParams
      .then((response) => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then((data) => {
        setLevelDetail(data.levles.find(item => item.id === id)); // Make sure 'levels' is returned correctly from your API
      })
      .catch((error) => {
        setError(error.message);
      });
  }, [id]);

  if (error) {
    return <div className="flex justify-center items-center min-h-screen text-red-500 text-xl">{error}</div>;
  }

  if (!levelDetail) {
    return <div className="flex justify-center items-center min-h-screen text-lg">Loading...</div>;
  }

  return (
    <div className="bg-gray-50 min-h-screen">
      <Navbar />
      <div className="container mx-auto p-8">
        {/* Header Section */}
        <div className="text-center py-10">
          <h1 className="text-4xl font-extrabold text-gray-800">{levelDetail.class_level}</h1>
          <p className="text-lg text-gray-600 mt-2">{levelDetail.teacher_name}</p>
          <p className="text-md text-gray-500 mt-1">{levelDetail.email}</p>
        </div>

        {/* Image Section */}
        <div className="flex justify-center my-6">
          <img
            src={`https://localhost/school/uploads/${levelDetail.photo}`}
            alt={levelDetail.class_level}
            className="rounded-lg shadow-xl max-w-md transition-transform transform hover:scale-105 duration-500"
          />
        </div>

        {/* Content Section */}
        <div className="bg-white rounded-lg shadow-lg p-6 md:p-10 mt-8">
          <h2 className="text-2xl font-semibold text-gray-800 mb-4">Subject Overview</h2>
          <p className="text-gray-600 text-lg leading-relaxed">{levelDetail.subject}</p>

          <h3 className="text-xl font-semibold text-gray-800 mt-8 mb-4">Additional Details</h3>
          <p className="text-gray-500 text-md leading-relaxed">{levelDetail.details}</p>
        </div>
      </div>

      <Footer />

      {/* Floating Decorative Elements */}
      
    </div>
  );
}

export default LevelDetail;
