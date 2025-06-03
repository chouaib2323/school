import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import About from './componentes/About';
import Contact from './componentes/Contact';
import Home from './componentes/Home';
import School from './componentes/School';
import Library from './componentes/Libary';
import E_learning from './componentes/E_learning';
import Directors from './componentes/Directors';
import Faq from './componentes/Faq';
import Anouncements from './componentes/Anouncements';
import Posts from './componentes/Posts';
import AnnouncementDetail from './componentes/AnnouncementDetail';
import PostDetail from './componentes/PostDetail';
import Researches from './componentes/Researches';
import Club from './componentes/Club';
import LaboratoryList from './componentes/LaboratoryList';
import Links from './componentes/Links';
import ScrollToTop from './componentes/ScrollToTop';
import Sports from './componentes/Sports';
import ModuleDetail from './componentes/ModuleDetail';
import InfinitLearning from './componentes/InfinitLearning';
import LevelDetail from './componentes/LevelDetail';

function App() {
  return (
    <div className="App">
   
      <Router>
      <ScrollToTop />
        <Routes>
       
          <Route path="/" element={<Home />} />
          <Route path="/ModuleDetail/:name" element={<ModuleDetail />} />
          <Route path="/LevelDetail/:id" element={<LevelDetail />} />
          <Route path="/School" element={<School />} />
          <Route path="/InfinitLearning" element={<InfinitLearning />} />
          <Route path="/Library" element={<Library />} />
          <Route path="/Sports" element={<Sports />} />
          <Route path="/E_learning" element={<E_learning />} />
          <Route path="/Directors" element={<Directors />} />
          <Route path="/Contact" element={<Contact />} />
          <Route path="/About" element={<About />} />
          <Route path="/Faq" element={<Faq />} />
          <Route path="/Anouncements" element={<Anouncements />} />
          <Route path="/Anouncements/:id" element={<AnnouncementDetail />} />
          <Route path="/Links/:id" element={<Links />} />
          <Route path="/Posts" element={<Posts />} />
          <Route path="/Posts/:id" element={<PostDetail />} />
          <Route path="/Researches" element={<Researches />} />
          <Route path="/Club" element={<Club />} />
          <Route path="/LaboratoryList" element={<LaboratoryList />} />
        </Routes>
      </Router>
 
    </div>
  );
}

export default App;