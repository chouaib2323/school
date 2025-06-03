import React from 'react';
import Footer from './Footer';
import Navbar from './Navbar';
import { useEffect } from 'react';
import { useState } from 'react';
function About() {

  const [about, setabout] = useState([]);


  useEffect(() => {
    fetch('https://localhost/school/api.php') // Replace with your API endpoint
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        setabout(data.about_us);
 
      })
      .catch(error => {
        console.error('Error fetching data:', error);
      });
  }, []);
  console.log(about[0]&&about[0].text)
  return (
    <div>
      <Navbar />
      <section className="container mx-auto px-4 py-20 bg-slate-50">
        <h1 className="text-3xl font-bold mb-4">About Us</h1>
        <p className="text-lg mb-4">
         {about[0]&&about[0].text}
        </p>
      </section>
      <Footer />
    </div>
  );
}

export default About;
