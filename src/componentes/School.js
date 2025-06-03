import React from 'react'
import Footer from './Footer';
import Navbar from './Navbar';
function School() {
  return (
    <div>
        <Navbar />
        <div class="bg-gray-100  ">
  <header class=" bg-gradient-to-r from-orange-300 to-orange-600 text-white  py-3">
    <div class="container mx-auto px-2">
      <h1 class="text-3xl font-bold">مدرستنا</h1>
    </div>
  </header>

  <main class="container mx-auto px-2">
    <section class="mb-8 pt-8">
      <h2 class="text-2xl font-bold mb-4">مقدمة</h2>
      <p class="text-gray-700">مرحبًا بكم في مدرستنا. نحن ملتزمون بتوفير تعليم عالي الجودة لطلابنا.</p>
    </section>

    <section class="mb-8">
      <h2 class="text-2xl font-bold mb-4">الرسالة والرؤية</h2>
      <p class="text-gray-700">مهمتنا هي تعزيز بيئة داعمة تعزز التميز الأكاديمي والنمو الشخصي والمسؤولية الاجتماعية.</p>
    </section>

    <section class="pb-8">
      <h2 class="text-2xl font-bold mb-4">تاريخنا</h2>
      <p class="text-gray-700">تتمتع مدرستنا بتاريخ حافل بالإنجازات الأكاديمية والمشاركة المجتمعية. تأسست في عام 1999، وتطورت لتصبح مؤسسة رائدة في المنطقة.</p>
    </section>

    <section class="mb-8">
      <h2 class="text-2xl font-bold mb-4">مرافقنا</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      
        <div class="bg-white p-4 shadow">
          <h3 class="font-bold text-xl">المكتبة</h3>
          <p class="text-gray-700">توفر مكتبتنا مجموعة واسعة من الكتب والمجلات والموارد الرقمية لدعم تعلم الطلاب.</p>
        </div>
       
        <div class="bg-white p-4 shadow">
          <h3 class="font-bold text-xl">المخابر العلمية</h3>
          <p class="text-gray-700">مختبرات علمية متطورة مجهزة للتعليم العملي في الفيزياء والكيمياء والأحياء.</p>
        </div>
      </div>
    </section>

    <section class="mb-8">
      <h2 class="text-2xl font-bold mb-4">هدفنا</h2>
      <p class="text-gray-700">نقدم منهجًا دراسيًا شاملاً يغطي مجموعة واسعة من المواد بما في ذلك العلوم والعلوم الإنسانية والفنون والتربية البدنية. تم تصميم منهجنا الدراسي لتطوير التفكير النقدي والإبداع ومهارات حل المشكلات.</p>
    </section>

    <section class="mb-8">
      <h2 class="text-2xl font-bold mb-4">الأنشطة اللامنهجية</h2>
      <p class="text-gray-700">نحن نشجع الطلاب على المشاركة في الأنشطة اللامنهجية مثل الرياضة والموسيقى والدراما والنوادي لتعزيز تنميتهم الشخصية والاجتماعية.</p>
    </section>

    <section className=' pb-8'>
      <h2 class="text-2xl font-bold mb-4">الإنجازات</h2>
      <p class="text-gray-700">حصل طلابنا وأعضاء هيئة التدريس لدينا على العديد من الجوائز والتقديرات لإنجازاتهم المتميزة في المجالات الأكاديمية والرياضية والخدمة المجتمعية.</p>
    </section>
  </main>
</div>
<Footer/>
    </div>
  )
}

export default School