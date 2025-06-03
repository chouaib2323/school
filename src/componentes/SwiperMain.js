
import React, { useState, useEffect } from 'react';
import { Swiper, SwiperSlide } from 'swiper/react';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/navigation';
import { Pagination, Navigation } from 'swiper/modules';

function SwiperMain({ id }) {
    const [postsPH, setPostsPH] = useState([]);

    useEffect(() => {
        fetch('https://localhost/school/api.php') // Replace with your API endpoint
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                setPostsPH(data.posts_with_photo);
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }, []);

    // Find the post with the given id
    const post = postsPH.find(post => post.id === id);

    return (
        <div>
            {post && (
                <Swiper
                    spaceBetween={30}
                    centeredSlides={true}
                    slidesPerView={1}
                    pagination={{ clickable: true }}
                    modules={[Pagination, Navigation]}
                    className="mySwiper w-2/3 h-auto"
                >
                    {post.photos.map((photo) => (
                        <SwiperSlide key={photo.id}>
                            <img
                                src={`https://localhost/school/uploads/${photo.photo}`}
                                className="w-full h-auto object-cover"
                                alt={post.title}
                            />
                        </SwiperSlide>
                    ))}
                </Swiper>
            )}
        </div>
    );
}

export default SwiperMain;
