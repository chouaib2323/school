import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import Navbar from './Navbar';
import SwiperC from './SwiperC';
import Anounce from './Anounce';
import RecentPost from './RecentPost';
import Modules from './Modules';
import Footer from './Footer';
import Leveles from './Leveles';
import bg from '../images/Girl.png';
import arabic from '../images/arabic.png';
import english from '../images/english.png';
import french from '../images/french.png';
import islamic from '../images/islamic.png';
import math from '../images/math.png';
import mecanical from '../images/mecanical.png';
import philo from '../images/philo.png';
import physics from '../images/physics.png';
import science from '../images/science.png';
import Links from './Links';
import { useLanguage } from './LanguageContext';

function Home() {
  const [video, setVideo] = useState([]);
  const [error, setError] = useState(null);
  const [leveles, setLeveles] = useState([]);
  const [postsPH, setPostsPH] = useState([]);
  const [anounce, setAnounce] = useState([]);
  const [link, setlink] = useState([]);
  const [info, setInfo] = useState([]);
  const [activeIndex, setActiveIndex] = useState(0); // For posts
  const [activeAnounceIndex, setActiveAnounceIndex] = useState(0); // For announcements
  const { language } = useLanguage(); // Use the context here

  const modules = [
    { name: 'العربية', imgg: arabic },
    { name: 'الإنجليزية', imgg: english },
    { name: 'الفرنسية', imgg: french },
    { name: 'الإسلامية', imgg: islamic },
    { name: 'الرياضيات', imgg: math },
    { name: 'الميكانيكا', imgg: mecanical },
    { name: 'الفلسفة', imgg: philo },
    { name: 'الفيزياء', imgg: physics },
    { name: 'العلوم', imgg: science },
  ];

  useEffect(() => {
    fetch('https://localhost/school/api.php')
      .then((response) => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then((data) => {
        setVideo(data.video);
        setPostsPH(data.posts_with_photo);
        setAnounce(data.anouncment);
        setLeveles(data.levles);
        setInfo(data.informations[0]);
        setlink(data.research_links[0]);
      })
      .catch((error) => {
        setError(error.message);
      });
  }, []);

  // For post highlighting every 4 seconds
  useEffect(() => {
    const interval = setInterval(() => {
      setActiveIndex((prevIndex) => (prevIndex + 1) % postsPH.length);
    }, 4000);
    return () => clearInterval(interval);
  }, [postsPH]);

  // For announcement highlighting every 4 seconds
  useEffect(() => {
    const interval = setInterval(() => {
      setActiveAnounceIndex((prevIndex) => (prevIndex + 1) % anounce.length);
    }, 4000);
    return () => clearInterval(interval);
  }, [anounce]);

  return (
    <div>
      <Navbar />
      <section className="flex flex-col space-y-3 lg:flex-row w-full h-max py-5 border-orange-200">
        <div className="w-full lg:w-1/2">
          <SwiperC postsPH={postsPH} activeIndex={activeIndex} />
        </div>
        <div className="flex flex-col lg:flex-row w-full lg:w-1/2">
          <div className="w-full lg:w-1/2">
            <Link
              to="/Posts"
              className="hover:bg-orange-400 font-bold border bg-gray-500 rounded-md text-white p-2 py-2 block text-center"
            >
              اخر الاخبار
            </Link>
            <div className="overflow-y-scroll p-2 h-80 bg-slate-100">
              {postsPH
                .slice()
                .reverse()
                .slice(0, 3)
                .map((post, index) => (
                  <RecentPost
                    key={index}
                    title={post.title}
                    subject={post.subject}
                    image={post.photos[0].photo}
                    highlight={index === activeIndex}
                    link={`/Posts/${post.id}`}
                  />
                ))}
            </div>
          </div>

          <div className="w-full lg:w-1/2">
            <Link
              to="/Anouncements"
              className="hover:bg-orange-400 block font-bold border bg-gray-500 rounded-md text-white p-2 text-center"
            >
              الاعلانات
            </Link>
            {anounce
              .slice()
              .reverse()
              .slice(0, 3)
              .map((e, index) => (
                <Anounce
                  key={index}
                  title={e.title}
                  subject={e.subject}
                  highlight={index === activeAnounceIndex} // Highlight logic here
                  link={`/Anouncements/${e.id}`}
                  linktwo={`/Links/${e.id}`}
                />
              ))}
          </div>
        </div>
      </section>

      <section className="flex flex-col w-full items-center md:flex-row md:flex-wrap md:justify-center md:space-x-10 pt-5 pb-14">
        <div className="hover:bg-orange-300 mx-10 w-44 h-14 grid place-items-center shadow-lg rounded-tl-xl rounded-br-lg border font-semibold my-2">
          <Link to="/E_learning">انشطة علمية</Link>
        </div>
        <div className="hover:bg-orange-300 w-44 h-14 grid place-items-center shadow-lg rounded-tl-xl rounded-br-lg border font-semibold my-2">
          <a href={`${link?.link_url}`} target="_blank">
            مركز اللغات
          </a>
        </div>
        <div className="hover:bg-orange-300 w-44 h-14 grid place-items-center shadow-lg rounded-tl-xl rounded-br-lg border font-semibold my-2">
          <Link to="/Researches">البحوث</Link>
        </div>
        <div className="hover:bg-orange-300 w-44 h-14 grid place-items-center shadow-lg rounded-tl-xl rounded-br-lg border font-semibold my-2">
          <Link to="/Directors">الاساتذة</Link>
        </div>
        <div className="hover:bg-orange-300 w-44 h-14 grid place-items-center shadow-lg rounded-tl-xl rounded-br-lg border font-semibold my-2">
          <Link to="/LaboratoryList">المخابر</Link>
        </div>
        <div className="hover:bg-orange-300 w-44 h-14 grid place-items-center shadow-lg rounded-tl-xl rounded-br-lg border font-semibold my-2">
          <Link to="/Club">النوادي</Link>
        </div>
        <div className="hover:bg-orange-300 w-44 h-14 grid place-items-center shadow-lg rounded-tl-xl rounded-br-lg border font-semibold my-2">
          <Link to="/Sports">الأنشطة الرياضية</Link>
        </div>
        <div className="hover:bg-orange-300 w-44 h-14 grid place-items-center shadow-lg rounded-tl-xl rounded-br-lg border font-semibold my-2">
        <Link to='/InfinitLearning' >التكوين المتواصل</Link>
      </div>
      </section>
      <div   className={ `h-auto w-full bg-gradient-to-r from-orange-100 to-orange-300 flex flex-col lg:flex-row ${language=='en'?'rtl':'ltr'}`}>
        <div   className="  text-lg leading-relaxed text-gray-700 w-full lg:w-2/3 p-4 flex flex-col justify-center">
          <h1 className="  font-bold text-2xl	">رسالة ترحيب من مدرستنا</h1>
          <p className="font-medium mb-4">
          مرحبًا بكم في مدرستنا، حيث نلتزم بتوفير بيئة تعليمية وتثقيفية لطلابنا. يعمل فريقنا المخصص من المعلمين بلا كلل لإلهام حب التعلم ومساعدة كل طالب على تحقيق إمكاناته الكاملة. نحن نقدم منهجًا دراسيًا واسع النطاق يعزز التميز الأكاديمي والإبداع والنمو الشخصي. انضم إلينا في تعزيز مستقبل مشرق لمجتمعنا.
          </p>
          <Link
            to="/contact"
            className="inline-block w-32 px-4 py-2 font-semibold text-white bg-gray-500 rounded hover:bg-gray-600"
          >
            اتصل بنا
          </Link>
        </div>
        <div className="flex justify-center lg:justify-end w-full lg:w-1/3">
          <img src={bg} className="w-auto h-96" alt="School background" />
        </div>
      </div>
      
      <section className="w-full p-10 flex flex-col lg:flex-row">
      <div className="w-full lg:w-1/2">
  {video.length > 0 ? (
    <iframe
      className="w-full h-72 md:h-96" // Make the iframe responsive
      src={`${video[0].video_url}`}
     // Deprecated, but can be used for older browsers
      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
      referrerPolicy="strict-origin-when-cross-origin"
      allowFullScreen // Use camelCase for React props
    ></iframe>
  ) : (
    <p>Loading video...</p>
  )}
</div>

        <div className="w-full lg:w-1/2 p-3 flex flex-col justify-center items-center space-y-4">
          {video.length > 0 && (
            <>
              <h1 className="font-bold text-3xl text-orange-400">{video[0].title}</h1>
              <p className="font-bold text-gray-500 text-md text-center">
                {video[0].subject}
              </p>
            </>
          )}
        </div>
      </section>
      <div className="grid place-items-center py-3">
        <h1 className="font-bold text-2xl underline text-gray-600">المواد</h1>
      </div>
      <section className="py-10 w-full flex flex-wrap justify-center gap-4">
        {modules.map((module) => (
          <Modules key={module.name} module={module} />
        ))}
      </section>
      <section className="bg-slate-100 py-20 grid place-items-center">
        <div className="py-3">
          <h1 className="font-serif underline text-3xl text-orange-400">مستويات التعليم</h1>
        </div>
        <div className="bg-slate-100 flex flex-col lg:flex-row gap-4 justify-center">
          {leveles.map((e, index) => (
            <Leveles
              key={index}
              id={e.id}
              teacher_name={e.teacher_name}
              subject={e.subject}
              class_level={e.class_level}
              email={e.email}
              photo={e.photo}
            />
          ))}
        </div>
      </section>
      
      <Footer />
    </div>
  );
}

export default Home;
