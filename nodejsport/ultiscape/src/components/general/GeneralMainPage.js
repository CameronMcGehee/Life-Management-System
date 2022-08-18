import React from 'react';

import GeneralWelcomePage from './pages/Welcome';

const GeneralMainPage = ({page}) => {

  var pageComponent;

  switch (page) {
    case "welcome":
      pageComponent = <GeneralWelcomePage />
      break;
    default:
      pageComponent = <GeneralWelcomePage />
      break;
  }
  return (
    pageComponent
  )
}

export default GeneralMainPage;