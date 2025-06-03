import React from 'react';

function Student({ student }) {
    return (
        <div className="bg-white shadow-lg rounded-lg p-6 mb-6 border border-gray-200">
            <h2 className="text-2xl font-bold mb-2">{`${student.first_name} ${student.last_name}`}</h2>
            <p className="text-gray-600 mb-4">Level ID: {student.level_id}</p>
            <h3 className="text-xl font-semibold mb-2">{student.research_title}</h3>
            <p className="text-gray-700 mb-4">{student.research_details}</p>
            {student.research_files.map(file => (
                <a
                    key={file.id}
                    href={`http://localhost/school/${file.file_path}`}
                    className="text-blue-500 underline"
                    download
                >
                    {file.file_name}
                </a>
            ))}
        </div>
    );
}

export default Student;
