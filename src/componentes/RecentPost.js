import React from 'react';
import { Link } from 'react-router-dom';
function RecentPost({ title, subject, highlight, image,link }) {
  return (
    <div className={`border-b-4  p-2 hover:bg-gray-500 hover:rounded-lg  border-gray-500 w-full hover:cursor-pointer ${highlight ? 'bg-orange-300 rounded-md' : ''}`}>
       <Link to={link} className=' flex justify-between items-center'>
        <div className=' w-40 h-full'> 
          <img src={`https://localhost/school/uploads/${image}`} className="p-2 w-full " alt="Post" />
        </div>
        <div className=' w-2/3'> 
 <h1 className="font-bold text-orange-600">{title}</h1>
        <p className='max-h-20 overflow-hidden font-bold'>{subject}</p>
      </div>
    </Link>
    </div>
  );
}

export default RecentPost;
