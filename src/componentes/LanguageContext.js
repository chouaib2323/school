import React, { createContext, useState, useEffect, useContext } from 'react';

const LanguageContext = createContext();

export const LanguageProvider = ({ children }) => {
  const [language, setLanguage] = useState('ar');

  const direction = language === 'ar' ? 'rtl' : 'ltr';

  useEffect(() => {
    const handleTranslateChange = () => {
      const selectElement = document.querySelector('.goog-te-combo');
      const selectedLanguage = selectElement ? selectElement.value : 'en';
      setLanguage(selectedLanguage);
    };

    window.addEventListener('change', handleTranslateChange);

    return () => {
      window.removeEventListener('change', handleTranslateChange);
    };
  }, []);

  return (
    <LanguageContext.Provider value={{ language, setLanguage, direction }}>
      <div dir={direction}>{children}</div>
    </LanguageContext.Provider>
  );
};

export const useLanguage = () => useContext(LanguageContext);