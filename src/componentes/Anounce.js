import React from 'react';
import { Link } from 'react-router-dom';

function Anounce({ title, subject, link, linktwo, highlight }) {
  return (
    <div className={`rounded-md border w-full h-28 p-2 ${highlight ? 'bg-orange-300' : 'bg-slate-100'}`}>
      <div className="overflow-hidden h-16">
        <h1 className="font-bold underline">{title}</h1>
        <p className="font-semibold">{subject}</p>
      </div>
      <div className="border-black py-2 space-x-3">
        <Link to={link} className="font-bold cursor-pointer text-black">
          Details
        </Link>
        <Link to={linktwo} className="font-bold cursor-pointer text-black">
          Consult link
        </Link>
      </div>
    </div>
  );
}

export default Anounce;
