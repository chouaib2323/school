import React from 'react'
import Footer from './Footer';
import Navbar from './Navbar';
import Course from './Course';
import { useEffect } from 'react';
import { useState } from 'react';
function E_learning() {
  const [library, setLibrary] = useState([]);
  const [levels, setLevels] = useState([]);
  const [selectedLevel, setSelectedLevel] = useState(null);

  useEffect(() => {
    fetch('https://localhost/school/api.php') // Replace with your API endpoint
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        setLibrary(data.elearning);
        setLevels(data.levles);
      })
      .catch(error => {
        console.error('Error fetching data:', error);
      });
  }, []);

  const handleLevelClick = (levelId) => {
    setSelectedLevel(levelId);
  };

  const filteredCourses = selectedLevel
    ? library.filter(course => course.level_id === selectedLevel)
    : "";

  return (
    <div>
      <Navbar />
      <div className="bg-gray-100">
        <header className="bg-gradient-to-r from-orange-300 to-orange-600 text-white py-4">
          <div className="container mx-auto px-2">
            <h1 className="text-3xl font-bold">E-Learning</h1>
          </div>
        </header>

        <main className="container mx-auto py-8 px-2">
          <section className="mb-8">
            <h2 className="text-2xl font-bold mb-4">مقدمة</h2>
            <p className="text-gray-700">مرحبًا بكم في منصة التعلم الإلكتروني الخاصة بنا. قم بتعزيز مهاراتك من خلال دوراتنا التدريبية عبر الإنترنت.</p>
          </section>

          <section className="mb-8">
            <h2 className="text-2xl font-bold mb-4">اختر مستواك الدراسي</h2>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
              {levels.map(level => (
                <div
                  key={level.id}
                  className="bg-white p-4 shadow-md rounded-lg hover:bg-orange-300 cursor-pointer"
                  onClick={() => handleLevelClick(level.id)}
                >
                  <h3 className="font-bold text-xl">{level.class_level}</h3>
                </div>
              ))}
            </div>
          </section>

          <section className="mb-8">
            <h2 className="text-2xl font-bold mb-4">Courses</h2>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            {filteredCourses.length==0?'':filteredCourses.sort((a, b) => a.level_id - b.level_id).map(course => (
                <Course
                  key={course.id} // Make sure to use a unique key for each component in the array
                  title={course.course_title}
                  name={course.module}
                  level={course.level_id}
                  download={`https://localhost/school/uploads/${course.pdf}`}
                  description={course.description}
                />
              ))}
            </div>
          </section>

          <section className="mb-8">
            <h2 className="text-2xl font-bold mb-4">Resources</h2>
            <ul className="list-disc list-inside text-gray-700">
              <li><a href="#" className="text-blue-600 hover:underline">Online Textbooks</a></li>
              <li><a href="#" className="text-blue-600 hover:underline">Lecture Notes</a></li>
              <li><a href="#" className="text-blue-600 hover:underline">Video Tutorials</a></li>
            </ul>
          </section>

          <section>
            <h2 className="text-2xl font-bold mb-4">Technical Support</h2>
            <p className="text-gray-700">If you encounter any issues, please check our troubleshooting guides or contact our support team.</p>
          </section>
        </main>
      </div>
      <Footer />
    </div>
  );
}

export default E_learning;