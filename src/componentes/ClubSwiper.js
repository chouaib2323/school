import React from 'react';
import { Swiper, SwiperSlide } from 'swiper/react';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/navigation';
import { Pagination, Navigation } from 'swiper/modules';

function ClubSwiper({ club }) {
    return (
        <div className="mb-8">
             
            {club.photos.length > 0 ? (
                <Swiper
                    spaceBetween={30}
                    centeredSlides={true}
                    slidesPerView={1}
                    pagination={{ clickable: true }}
                    modules={[Pagination]}
                    className="mySwiper w-2/3 h-auto border-b-4 border-gray-200"
                >
                    {club.photos.map((photo, index) => (
                        <SwiperSlide key={index}>
                            <div className="flex flex-col items-center p-2 rounded-lg">
                                <img
                                    src={`https://localhost/school/${photo.photo}`}
                                    className="w-full max-h-80 object-cover rounded-lg"
                                    alt={`Club photo ${index + 1}`}
                                />
                            </div>
                        </SwiperSlide>
                    ))}
                </Swiper>
            ) : (
                <p className="text-gray-600">No photos available for this club.</p>
            )}
        </div>
    );
}

export default ClubSwiper;
