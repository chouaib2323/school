import React, { useRef, useEffect } from 'react';
import { Swiper, SwiperSlide } from 'swiper/react';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/navigation';
import { Pagination, Navigation } from 'swiper/modules';
import { Link } from 'react-router-dom';
import { useLanguage } from './LanguageContext';

function SwiperC({ postsPH, activeIndex }) {
  const swiperRef = useRef(null);
  const { language } = useLanguage(); // Use the context here

  useEffect(() => {
    if (swiperRef.current) {
      swiperRef.current.slideTo(activeIndex);
    }
  }, [activeIndex]);

  const handleSwiper = (swiper) => {
    swiperRef.current = swiper;
  };

  return (
    <div>
      <Swiper
        key={language}  // Add the language as the key to trigger re-render on language change
        onSwiper={handleSwiper}
        spaceBetween={30}
        centeredSlides={true}
        slidesPerView={1}
        pagination={{
          clickable: true,
        }}
        modules={[Pagination, Navigation]}
        className="mySwiper w-full h-auto border-b-4 z-0"
      >
        {postsPH.slice().reverse().slice(0, 3).map((post, index) => (
          <SwiperSlide key={index} dir={language === 'ar' ? 'rtl' : 'ltr'}>
            <div className="flex flex-col items-center text-center p-2 rounded-lg">
              <img
                src={`https://localhost/school/uploads/${post.photos[0].photo}`}
                className="w-1/2 max-h-80"
                alt={post.title}
              />
              <Link to={`/Posts/${post.id}`}>
                <h1 className="font-bold mt-4 cursor-pointer hover:text-orange-500">
                  {post.title}
                </h1>
              </Link>
              <p className="h-20 py-1 overflow-hidden w-full">{post.subject}</p>
            </div>
          </SwiperSlide>
        ))}
      </Swiper>
    </div>
  );
}

export default SwiperC;
