import React from 'react';
import { Link } from 'react-router-dom';

function Modules({ module }) {
  return (
    <Link to={`/ModuleDetail/${module.name}`}>
      <div className="shadow-lg text-center rounded-tl-3xl bg-white hover:bg-slate-100 border w-40 py-20">
        <img src={module.imgg} alt={module.name} className="w-10 h-auto mx-auto bg-orange-500 rounded-md" />
        <div className="block mt-2 text-lg font-semibold text-gray-500">
          {module.name}
        </div>
      </div>
    </Link>
  );
}

export default Modules;
