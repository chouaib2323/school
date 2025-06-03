import React, { useEffect, useState } from 'react';
import Footer from './Footer';
import Navbar from './Navbar';

const LaboratoryList = () => {
    const [laboratories, setLaboratories] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetch('https://localhost/school/api.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                setLaboratories(data.laboratories);
                setLoading(false);
            })
            .catch(error => {
                setError(error);
                setLoading(false);
            });
    }, []);

    if (loading) return <div className="text-center mt-4">Loading...</div>;
    if (error) return <div className="text-center mt-4 text-red-600">Error fetching data</div>;

    return (
        <>
            <Navbar />
            <div className="container mx-auto p-4 min-h-screen">
                <h1 className="text-2xl font-bold mb-4">Laboratories</h1>
                <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    {laboratories.map((lab) => (
                        <div key={lab.id} className="bg-white shadow-md rounded p-4">
                            <img 
                                src={`https://localhost/school/${lab.image_url}`} 
                                alt={lab.name} 
                                className="w-full h-44 object-cover mb-4 rounded" 
                            />
                            <h2 className="text-xl font-bold mb-2">{lab.name}</h2>
                            <p className="text-gray-700 mb-2">description : {lab.description}</p>
                            <p className="text-gray-500 text-sm">created_at : {new Date(lab.created_at).toLocaleDateString()}</p>
                        </div>
                    ))}
                </div>
            </div>
            <Footer />
        </>
    );
};

export default LaboratoryList;
