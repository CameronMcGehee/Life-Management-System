import React from 'react'

import ultiscapeLogo from '../../images/ultiscape/logos/mainLogoTopBarWhiteTrans.png';
import chevronDown from '../../images/ultiscape/icons/chevron_down.svg';
import bsImg from '../../images/ultiscape/etc/noLogo.png';
import pfpImg from '../../images/ultiscape/icons/user_male.svg';

const AdminTopBar = ({showLogo, showBusinessSelector, showProfileButton}) => {

  var logoOutput = '';
  if (showLogo) {
    logoOutput = 
      <a className="noUnderline" id="ultiscapeLogoImageWrapper" href="{{{rootPath}}}"><img src={ultiscapeLogo} alt="UltiScape Logo"/></a>;
  } else {
    logoOutput = '';
  }

  var businessSelectorOutput = '';
  if (showBusinessSelector) {
    businessSelectorOutput = 
      <div className="yCenteredFlex flexDirectionRow" id="businessSelectorButton">
          <img id="businessSelectorSelectedImg" src={bsImg}  alt="Business Logo"/><img src={chevronDown} alt="Down Arrow"/>
      </div>;
  } else {
    businessSelectorOutput = '';
  }

  var profileButtonOutput = '';
  if (showProfileButton) {
    profileButtonOutput = 
    <div id="profileButtonWrapper">
      <img id="profilePictureButton" src={pfpImg} alt="Profile" /><img src={chevronDown} className="whiteChevron" alt="Down Arrow"/>
    </div>
  } else {
    profileButtonOutput = '';
  }

  return (
    <div className="adminTopBarWrapper defaultInsetShadow">
      <div className="xyCenteredFlex" id="ultiscapeLogoWrapper">
          {logoOutput}
      </div>

      <div>
          {/* Spacer/Blank Cell */}
      </div>

      <div className="yCenteredFlex flexDirectionRow" id="businessSelectorButtonWrapper">
          {businessSelectorOutput}
      </div>

      <div className="yCenteredFlex flexDirectionRow" id="profileButtonWrapper">
          {profileButtonOutput}
      </div>

      {/* <AdminTopBarDropdowns /> */}

    
    </div>
  )
}

export default AdminTopBar;
