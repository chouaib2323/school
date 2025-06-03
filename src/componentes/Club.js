import React, { useState, useEffect } from 'react';
import ClubSwiper from './ClubSwiper';
import Footer from './Footer';
import Navbar from './Navbar';

function Club() {
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
                setClubs(data.clubs); // Assuming data.clubs is an array of club objects
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }, []);

    return (
        <>
            <Navbar />
            <div className="container mx-auto p-6 min-h-screen">
                {clubs.length > 0 ? (
                    clubs.map(club => (
                        <div key={club.id} className="border border-gray-300 rounded-lg shadow-lg mb-8 p-4 bg-white">
                            <h2 className="text-3xl font-bold mb-4 text-gray-900">{club.name} club</h2>
                            <p className="text-gray-700 mb-6 font-semibold">club activities : {club.details}</p>
                            <ClubSwiper club={club} />
                        </div>
                    ))
                ) : (
                    <p className="text-gray-600">Loading club data...</p>
                )}
            </div>
            <Footer />
        </>
    );
}

export default Club;
