import './css/app/main.css';

import { BrowserRouter, Routes, Route } from 'react-router-dom';

import GeneralMainPage from './components/general/GeneralMainPage';
import AdminAccountInitPage from './components/admin/AdminAccountInitPage';
import AdminMainPage from './components/admin/AdminMainPage';

function App() {
  return (
    <BrowserRouter>

      <Routes>
        {/* General UltiScape Pages */}
        <Route path="/" element={<GeneralMainPage PageName="Welcome" />}/>

        {/* Admin Account Pages */}
        <Route path="/admin" element={<AdminAccountInitPage page="login" />}/>
        <Route path="/admin/login" element={<AdminAccountInitPage page="login" />}/>
        <Route path="/admin/createaccount" element={<AdminAccountInitPage page="createaccount" />}/>

        {/* Admin Main Pages */}
        <Route path="/admin/overview" element={<AdminMainPage page="overview" />}/>
        <Route path="/admin/customers" element={<AdminMainPage page="customers" />}/>
        <Route path="/admin/jobs" element={<AdminMainPage page="jobs" />}/>
        <Route path="/admin/invoices" element={<AdminMainPage page="invoices" />}/>
        <Route path="/admin/estimates" element={<AdminMainPage page="estimates" />}/>
        <Route path="*" element={<h1>404 Not Found</h1>}/>
      </Routes>

    </BrowserRouter>
  );
}

export default App;
