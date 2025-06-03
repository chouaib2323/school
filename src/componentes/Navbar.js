import React, { useState, useEffect } from 'react';
import { NavLink } from 'react-router-dom';
import instagram from "../images/instagram.png";
import twitter from "../images/twitter.png";
import facebook from "../images/facebook.png";
import youtube from "../images/youtube.png";
import { useLanguage } from './LanguageContext';

function Navbar() {
  const { language, setLanguage } = useLanguage(); // Use the context here
  const [data, setData] = useState({});
  const [menuOpen, setMenuOpen] = useState(false);
  const [links, setLinks] = useState({});
  const [logo, setLogo] = useState([]);

  useEffect(() => {
    fetch('https://localhost/school/api.php')
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        setData(data.informations);
        setLinks(data.elearning_links);
        setLogo(data.logo)
      })
      .catch(error => {
        console.error('Error fetching data:', error);
      });
  }, []);

  const toggleMenu = () => {
    setMenuOpen(!menuOpen);
  };

  const closeMenu = () => {
    setMenuOpen(false);
  };

  function resetTranslation() {
    const iframe = document.querySelector('.goog-te-banner-frame');
    if (iframe) {
      const iframeContent = iframe.contentDocument || iframe.contentWindow.document;
      const resetButton = iframeContent.querySelector('.goog-te-banner-frame');
      if (resetButton) resetButton.click();
    }
    document.cookie = 'googtrans=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/';
    window.location.reload();
  }

  return (
    <div className='border-b-2 drop-shadow-lg z-50'>
      <div className='bg-gray-500 w-full p-2'>
        <div className='text-white container mx-auto flex justify-between items-center'>
          <div className='md:flex font-bold'>
            <div className='px-4'>
              <span className='text-orange-400'>رقم الهاتف</span> : 213{data[0]?.phone}
            </div>
            <div>
              <span className='text-orange-400'>Email </span>: {data[0]?.email}
            </div>
          </div>
          
          <div className="flex justify-center md:justify-start border-b-2 py-2">
            <a target="_blank" href={data[0]?.instagram} className="mx-1 bg-gradient-to-tr from-purple-500 to-yellow-400 rounded-2xl">
              <img className='w-7 h-auto' src={instagram} alt="Instagram" />
            </a>
            <a target="_blank" href={data[0]?.facebook} className="mx-1 bg-blue-600 rounded-2xl">
              <img className='w-7 h-auto' src={facebook} alt="Facebook" />
            </a>
            <a target="_blank" href={data[0]?.twiter} className="mx-1">
              <img className='w-7 h-auto' src={twitter} alt="Twitter" />
            </a>
            <a target="_blank" href={data[0]?.youtube} className="mx-1 bg-red-500 rounded-2xl">
              <img className='w-7 h-auto' src={youtube} alt="YouTube" />
            </a>
          </div>
        </div>
      </div>
      <div className='w-full h-20 flex justify-between items-center border px-4'>
      <div className=" h-full  w-56 md:flex hidden md:place-items-center  ">
          <img className='w-auto h-full mx-2 ' src={`https://localhost/school/uploads/${logo[0]?.filename}`} alt="Logo" />
          <h1 className=' font-extrabold text-lg'>{data[0]?.school}</h1>
        </div>
        
        <div className="md:flex md:justify-center md:w-full items-center space-x-5 hidden font-semibold">
          <NavLink exact to='/' activeClassName='active' className='nav-link'>الصفحة الرئيسية</NavLink>
          <NavLink to='/School' activeClassName='active' className='nav-link'>عن المدرسة</NavLink>
          <NavLink to='/Library' activeClassName='active' className='nav-link'>المكتبة</NavLink>
          <div className="relative group">
            <NavLink
              to='/E_learning'
              activeClassName='active'
              className='nav-link'
            >
              E-learning
            </NavLink>
            <div
              className="dropdown-menu absolute top-10  border-orange-400 z-50 text-black flex text-md border-2 bg-orange-300 p-2 rounded-md font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-300"
            >
              <a  target="_blank" rel="noopener noreferrer" href={links[0]?.link_1} className='block mx-2'>{links[0]?.link_name1}</a>
              <a target="_blank" rel="noopener noreferrer" href={links[0]?.link_2} className='block'>{links[0]?.link_name2}</a>
            </div>
          </div>
          <NavLink to='/Directors' activeClassName='active' className='nav-link'>المشرفين</NavLink>
          <NavLink to='/About' activeClassName='active' className='nav-link'>About us</NavLink>
          <NavLink to='/Contact' activeClassName='active' className='nav-link'>اتصل بنا</NavLink>
        </div>
        <button className="md:hidden focus:outline-none" onClick={toggleMenu}>
          <svg className="w-6 h-6 text-black cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
            {menuOpen ? (
              <path d="M6 18L18 6M6 6l12 12" />
            ) : (
              <path d="M3 12h18M3 6h18M3 18h18" />
            )}
          </svg>
        </button>
        <div value='arabic' onClick={() => { setLanguage('ar'); resetTranslation() }} className='bg-orange-300 p-3 rounded-lg font-semibold hover:cursor-pointer'>العربية</div>
        <div className="items-center rounded-md px-3 h-full">
          <div id="google_translate_element"></div>
        </div>
       

      </div>
      
      {menuOpen && (
        <div className="md:hidden bg-gray-200 py-2">
          <ul className="flex flex-col items-center space-y-2">
            <li><NavLink exact to="/" className="text-xl" onClick={closeMenu}>Home</NavLink></li>
            <li><NavLink to="/School" className="text-xl" onClick={closeMenu}>School</NavLink></li>
            <li><NavLink to="/Library" className="text-xl" onClick={closeMenu}>Library</NavLink></li>
            <li><div className=' flex'><NavLink to="/E_learning" className="text-xl" onClick={closeMenu}>E-learning</NavLink>
            <div
              className="  border-orange-400 z-50 text-orange-600 flex text-xl  font-semibold  group-hover:opacity-100 transition-opacity duration-300"
            >
              <a  target="_blank" rel="noopener noreferrer" href={links[0]?.link_1} className='block mx-2'>{links[0]?.link_name1}</a>
              <a target="_blank" rel="noopener noreferrer" href={links[0]?.link_2} className='block'>{links[0]?.link_name2}</a>
            </div>
            </div></li>
            <li><NavLink to="/Directors" className="text-xl" onClick={closeMenu}>Directors</NavLink></li>
            <li><NavLink to="/About" className="text-xl" onClick={closeMenu}>About us</NavLink></li>
            <li><NavLink to="/Contact" className="text-xl" onClick={closeMenu}>Contact us</NavLink></li>
          </ul>
        </div>
      )}
    </div>
  );
}

export default Navbar;
