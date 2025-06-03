import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import Footer from './Footer';
import Navbar from './Navbar';

function ModuleDetail() {
  const { name } = useParams(); // Get module name from URL
  const [moduleInfo, setModuleInfo] = useState(null);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch(`https://localhost/school/moduleapi.php?name=${name}`)
      .then(response => response.json())
      .then(data => setModuleInfo(data))
      .catch(error => setError(error.message));
  }, [name]);

  if (!moduleInfo?.id) {
    return (
      <div className="flex justify-center items-center h-screen">
        <p className="text-red-500 text-lg font-semibold">module not added yet</p>
      </div>
    );
  }

  if (!moduleInfo) {
    return (
      <div className="flex justify-center items-center h-screen">
        <p className="text-gray-500 text-lg font-semibold">Loading...</p>
      </div>
    );
  }
 

  return (
    <>
      <Navbar />
      <div className="max-w-4xl mx-auto p-5">
        <h1 className="text-3xl font-bold text-gray-800 mb-6 text-center">{moduleInfo.name}</h1>
        <p className="text-lg text-gray-600 mb-8 text-justify">{moduleInfo.introduction}</p>

        <div className="bg-gray-100 rounded-lg p-6 shadow-md">
          <h2 className="text-2xl font-semibold text-gray-700 mb-4">محاور المادة من كل سنة</h2>
          <ul className="space-y-4">
            {moduleInfo.themes.map((theme, index) => (
              <li
                key={index}
                className="p-4 bg-white rounded-md shadow-sm border border-gray-200"
              >
                <div className="text-lg font-medium text-gray-800">
                  <strong>{theme.year}:</strong> {theme.details}
                </div>
              </li>
            ))}
          </ul>
        </div>
      </div>
      <Footer />
    </>
  );
}

export default ModuleDetail;