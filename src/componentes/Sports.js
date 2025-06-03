import React, { useState, useEffect } from 'react';
import ClubSwiper from './ClubSwiper';
import Footer from './Footer';
import Navbar from './Navbar';
import SportSwiper from './SportSwiper';

function Sports() {
    const [clubs, setClubs] = useState([]);

    useEffect(() => {
        fetch('https://localhost/school/api.php') // Replace with your API endpoint
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                setClubs(data.posts); // Assuming data.clubs is an array of club objects
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }, []);

    return (
        <>
        
            <Navbar />
            <div className='  bg-gradient-to-r from-orange-100 via-gray-100 to-pink-100 min-h-screen'>
            <div className="container mx-auto p-6 ">
                {clubs.length > 0 ? (
                    clubs.map(club => (
                        <div key={club.id} className="border border-gray-300 rounded-lg shadow-lg mb-8 p-4 bg-white">
                            <h2 className="text-3xl font-bold mb-4 text-gray-900">{club.title}</h2>
                            <p className="text-gray-700 mb-6 font-semibold"> {club.subject}</p>
                            <SportSwiper club={club} />
                        </div>
                    ))
                ) : (
                    <p className="text-gray-600">Loading club data...</p>
                )}
            </div>
            </div>
            <Footer />
       
        </>
    );
}

export default Sports;
