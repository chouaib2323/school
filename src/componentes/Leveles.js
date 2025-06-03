import React from 'react';
import { Link } from 'react-router-dom';

function Leveles({ id, class_level, subject, teacher_name, email, photo }) {
  return (
    <div className="max-w-xs mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
      <Link to={`/LevelDetail/${id}`}>
        <div className="p-4 flex flex-col items-center">
          <div className="rounded-full bg-gray-200 p-5 mb-4">
            <img
              src={`https://localhost/school/uploads/${photo}`}
              alt="Science Icon"
              className="w-16 h-16"
            />
          </div>
          <h1 className="text-lg font-bold text-gray-800">{class_level}</h1>
          <p className="text-sm text-gray-600 mb-4">{subject}</p>
          <div className="border-t border-gray-300 w-full mb-4"></div>
          <div className="w-full text-center">
            <h2 className="text-sm font-semibold text-gray-700">{teacher_name}</h2>
            <p className="text-xs text-gray-500">{email}</p>
          </div>
        </div>
      </Link>
    </div>
  );
}

export default Leveles;
