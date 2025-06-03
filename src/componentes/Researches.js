import React, { useState, useEffect } from 'react';
import Footer from './Footer';
import Navbar from './Navbar';
import Student from './Student';

function Researches() {
    const [students, setStudents] = useState([]);

    useEffect(() => {
        fetch('https://localhost/school/api.php') // Replace with your API endpoint
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                setStudents(data.students);
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }, []);

    return (
        <>
            <Navbar />
            <div className="container mx-auto p-6 min-h-screen">
                <h1 className="text-3xl font-bold mb-6">ابحاث الطلاب</h1>
                {students.length > 0 ? (
                    students.map(student => (
                        <Student key={student.id} student={student} />
                    ))
                ) : (
                    <p className="text-gray-600">No student research data available.</p>
                )}
            </div>
            <Footer />
        </>
    );
}

export default Researches;
