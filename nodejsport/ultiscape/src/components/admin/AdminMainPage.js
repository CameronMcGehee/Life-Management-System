import React from 'react';

import AdminTopBar from './AdminTopBar';
import AdminSideBar from './AdminSideBar';
import AdminMobileNavBar from './AdminMobileNavBar';
import AdminFooter from './AdminFooter';

import GeneralWelcomePage from '../general/pages/Welcome';

import AdminOverviewPage from '../admin/pages/Overview';
import AdminCustomersPage from '../admin/pages/Customers';
import AdminJobsPage from './pages/Jobs';
import AdminInvoicesPage from './pages/Invoices';
import AdminEstimatesPage from './pages/Estimates';

const AdminMainPage = ({page}) => {

  var pageComponent;
  switch (page) {
    case "welcome":
      pageComponent = <GeneralWelcomePage />
      break;
    case "customers":
      pageComponent = <AdminCustomersPage />
      break;
    case "jobs":
      pageComponent = <AdminJobsPage />
      break;
    case "invoices":
      pageComponent = <AdminInvoicesPage />
      break;
    case "estimates":
      pageComponent = <AdminEstimatesPage />
      break;
    default:
      pageComponent = <AdminOverviewPage />
      break;
  }

  return (
    <div class="adminBodyWrapper">
      <AdminTopBar showLogo='true' showBusinessSelector={true} showProfileButton={true}/>

      <AdminSideBar />
      
      <div class="cmsMainContentWrapper">
      {pageComponent}
      </div>

      <AdminMobileNavBar />

      <AdminFooter isLogin='false' />
            
    </div>
  )
}

export default AdminMainPage;