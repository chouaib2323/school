import React from 'react';
import instagram from "../images/instagram.png";
import twitter from "../images/twitter.png";
import facebook from "../images/facebook.png";
import { Link } from 'react-router-dom';
import { useEffect,useState } from 'react';
import youtube from "../images/youtube.png";
function Footer() {
  const [info, setInfo] = useState([]);
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
        setInfo(data.informations[0]);
        setLogo(data.logo)
      })
    
  }, []);

  return (
    <footer className="bg-gray-600 text-white py-4 px-24">
      <div className="container mx-auto flex flex-col md:flex-row items-center justify-between py-4 gap-6">
        <div className="text-center md:text-left space-y-5">
          <h3 className="mb-2 font-bold text-xl">Contact us</h3>
          <div className="flex justify-center md:justify-start  border-b-2 pb-2">
            <a href={info.instagram} className="text-2xl mx-1 bg-gradient-to-tr from-purple-600 to-yellow-400 rounded-3xl" target="_blank"><img className='w-10 h-auto' src={instagram} alt="Instagram" /></a>
            <a href={info.facebook} className="text-2xl mx-1 bg-blue-600 rounded-3xl" target="_blank"><img className='w-10 h-auto' src={facebook} alt="Facebook" /></a>
            <a href={info.twiter} className="text-2xl mx-1" target="_blank"><img className='w-10 h-auto' src={twitter} alt="Twitter" /></a>
            <a target="_blank" href={info.youtube} className="text-2xl mx-1 bg-red-600 rounded-3xl"><img className='w-10 h-auto' src={youtube} alt="Twitter" /></a>
          </div>
          <div className="mt-4">
            <p className="flex flex-col md:flex-row items-center justify-center md:justify-start space-x-2">
              <span>🕑 08:00 - 16:30</span>
              <span className="ml-2">Sunday - Thursday</span>
            </p>
          </div>
        </div>
        <div className="w-full md:w-auto">
          <iframe className='w-full h-52 md:w-96 md:h-48' src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3332.5653917735417!2d-111.79468922587976!3d33.35630235415982!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x872ba91735bdc7c9%3A0x5276a7b2c341bb0f!2sPark%20University%20Gilbert!5e0!3m2!1sfr!2sdz!4v1719946449675!5m2!1sfr!2sdz" width="400" height="300" allowFullScreen="" loading="lazy" referrerPolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div className="  ">
          <img className='w-auto h-28 rounded-sm ' src={`https://localhost/school/uploads/${logo[0]?.filename}`} alt="Logo" />
        </div>
        <div className="text-center md:text-left   ">
          <h1 className='font-bold text-md underline'>Links You Want To Visit </h1>
          <ul className="space-y-1">
            <li><Link to='/About' className="text-orange-500 font-bold">About Us</Link></li>
            <li><Link to='/Contact' className="text-orange-500 font-bold">Contact Us</Link></li>
            <li><Link to='/Faq' className="text-orange-500 font-bold">FAQ</Link></li>
          </ul>
         
        </div>
        
      </div>
      <div className="mt-8 text-center">
        <p>&copy; All rights reserved . our school</p>
      </div>
    </footer>
  );
}

export default Footer;