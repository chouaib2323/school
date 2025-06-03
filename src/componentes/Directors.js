import React from 'react'
import Director from './Director';
import Footer from './Footer';
import Navbar from './Navbar';
import { useEffect } from 'react';
import { useState } from 'react';
function Directors() {
  const [Libary, setLibary] = useState([]);


  useEffect(() => {
    fetch('https://localhost/school/api.php') // Replace with your API endpoint
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        setLibary(data.directors);
 
      })
      .catch(error => {
        console.error('Error fetching data:', error);
      });
  }, []);
  return (
    <div>
        <Navbar />
        <div class="bg-gray-100">
  <header class="bg-gradient-to-r from-orange-300 to-orange-600 text-white py-4">
    <div class="container mx-auto px-2">
      <h1 class="text-3xl font-bold">المشرفين</h1>
    </div>
  </header>

  <main class="container mx-auto py-8 px-2">
    <section class="mb-8">
      <h2 class="text-2xl font-bold mb-4">رسالة الترحيب</h2>
      <p class="text-gray-700">مرحبًا بكم في مدرستنا. هدفنا هو توفير أفضل تجربة تعليمية لطلابنا.</p>
    </section>

    <section class="mb-8">
      <h2 class="text-2xl font-bold mb-4">الملفات الشخصية</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      
      {Libary.map((e)=> <Director src={`https://localhost/school/uploads/${e.image}`} modules={e.modules} email={e.email}  name={e.name} bio={e.biography} />)}

      </div>
    </section>

  
    <section class="mb-8">
      <h2 class="text-2xl font-bold mb-4">الإنجازات</h2>
      <p class="text-gray-700">تحت قيادتنا، حققت المدرسة العديد من الإنجازات وحصلت على العديد من الجوائز.</p>
    </section>

    <section>
      <h2 class="text-2xl font-bold mb-4">المبادرات والبرامج</h2>
      <p class="text-gray-700">نحن نعمل بشكل مستمر على مبادرات وبرامج جديدة لتعزيز التجربة التعليمية لطلابنا.</p>
    </section>
  </main>
</div>
<Footer/>
    </div>
  )
}

export default Directors