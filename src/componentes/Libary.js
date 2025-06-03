import React, { useEffect, useState } from 'react';
import Book from './Book';
import Footer from './Footer';
import Navbar from './Navbar';

function Library() {
  const [library, setLibrary] = useState([]);
  const [searchQuery, setSearchQuery] = useState('');

  useEffect(() => {
    fetch('https://localhost/school/api.php') // Replace with your API endpoint
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        setLibrary(data.library);
      })
      .catch(error => {
        console.error('Error fetching data:', error);
      });
  }, []);

  // Function to filter library based on search query
  const filteredLibrary = library.filter(book =>
    book.title.toLowerCase().includes(searchQuery.toLowerCase())
  );

  return (
    <div>
      <Navbar />
      <div className="bg-gray-100">
        <header className="bg-gradient-to-r from-orange-300 to-orange-600 text-white py-4">
          <div className="container mx-auto px-2">
            <h1 className="text-3xl font-bold">مكتبتنا</h1>
          </div>
        </header>

        <main className="container mx-auto py-8 px-2">
          <section className="mb-8">
            <h2 className="text-2xl font-bold mb-4">مقدمة</h2>
            <p className="text-gray-700">
            مرحبًا بكم في مكتبة المدرسة. هنا، يمكنك العثور على مجموعة واسعة من الموارد لدعم رحلة التعلم الخاصة بك.
            </p>
          </section>

          <section className="mb-8 ">
            <h2 className="text-2xl font-bold mb-4">الكتب المتوفرة</h2>
            <input
              type="text"
              className="border border-gray-300 p-2 w-full mb-4"
              placeholder="Search by book title..."
              value={searchQuery}
              onChange={e => setSearchQuery(e.target.value)}
            />
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4  ">
              {filteredLibrary.map(book => (
                <Book
                  key={book.id}
                  src={`https://localhost/school/uploads/${book.image}`}
                  title={book.title}
                  name={book.author}
                  download={`https://localhost/school/uploads/${book.pdf}`}
                />
              ))}
            </div>
          </section>

          <section className="mb-4">
            <h2 className="text-2xl font-bold mb-4">خدمات المكتبة</h2>
            <p className="text-gray-700">
            تعرف على سياسات الاقتراض لدينا، وأحداثنا، والمزيد.
            </p>
          </section>

          <section className="mb-4">
            <h2 className="text-2xl font-bold mb-4">موظفو المكتبة</h2>
            <p className="text-gray-700">
            تعرف على فريقنا المتخصص من أمناء المكتبات.
            </p>
          </section>

          <section>
            <h2 className="text-2xl font-bold mb-4 ">الدعم</h2>
            <p className="text-gray-700">
            هل تحتاج إلى مساعدة؟ اطلع على الأسئلة الشائعة لدينا أو اتصل بنا للحصول على المساعدة.
            </p>
          </section>
        </main>
      </div>
      <Footer />
    </div>
  );
}

export default Library;
