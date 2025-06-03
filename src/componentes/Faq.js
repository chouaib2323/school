import React from 'react';
import Navbar from './Navbar';
import Footer from './Footer';

const FAQ = () => {
  const faqs = [
    {
      question: 'What are the library hours?',
      answer: 'The library is open from 8:00 AM to 4:00 PM on weekdays.',
    },
    {
      question: 'How can I contact the school administration?',
      answer: 'You can contact us via email at admin@school.com or call us at +123456789.',
    },
    {
      question: 'Is there a dress code policy?',
      answer: 'Yes, we have a dress code policy. Students are required to wear school uniforms.',
    },
    {
      question: 'Are there after-school activities?',
      answer: 'Yes, we offer a variety of after-school activities including sports, clubs, and tutoring sessions.',
    },
    {
      question: 'What is the procedure for admission?',
      answer: 'Please visit our admissions page for detailed information on admission procedures.',
    },
  ];

  return (
    <div>
      <Navbar />
      <div className="bg-gray-100 min-h-screen">
        <header className="bg-gradient-to-r from-orange-300 to-orange-600 text-white py-4">
          <div className="container mx-auto">
            <h1 className="text-3xl font-bold">Frequently Asked Questions</h1>
          </div>
        </header>

        <main className="container mx-auto py-8">
          <section className="mb-8">
            {faqs.map((faq, index) => (
              <div key={index} className="bg-white shadow-md rounded-lg p-6 mb-4">
                <h2 className="text-xl font-bold mb-2">{faq.question}</h2>
                <p className="text-gray-700">{faq.answer}</p>
              </div>
            ))}
          </section>
        </main>
      </div>
      <Footer />
    </div>
  );
};

export default FAQ;
