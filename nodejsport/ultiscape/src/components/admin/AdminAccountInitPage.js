import React from 'react';

import AdminTopBar from './AdminTopBar';
import AdminMobileNavBar from './AdminMobileNavBar';
import AdminFooter from './AdminFooter';

// import '../../css/app/main.css';
import '../../css/app/admin/adminLoginPage.css';
import '../../css/app/admin/adminRegisterPage.css';

import GeneralWelcomePage from '../general/pages/Welcome';
import AdminLoginPage from './pages/Login';

const AdminAccountInitPage = ({page}) => {

  var pageComponent;
  switch (page) {
    case "welcome":
      pageComponent = <GeneralWelcomePage />
      break;
    case "login":
      pageComponent = <AdminLoginPage />
      break;
    default:
      pageComponent = <AdminLoginPage />
      break;
  }

  return (
    <div className="appNoSidebarBodyWrapper">
      <AdminTopBar showLogo={true} showBusinessSelector={false} showProfileButton={true}/>
      
      <div className="cmsMainContentWrapper">
        <div className="maxHeight xyCenteredFlex flexDirectionColumn marginLeftRight90 styledText textColorThemeGray">
          {pageComponent}
        </div>
      </div>

      <AdminFooter isLogin='false' year={2022} version='WIP' />
            
    </div>
  )
}

export default AdminAccountInitPage;