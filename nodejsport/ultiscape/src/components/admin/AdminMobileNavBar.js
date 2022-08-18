import React from 'react'

import customersImg from '../../images/ultiscape/icons/users.svg';
import jobsImg from '../../images/ultiscape/icons/calendar_month.svg';
import documentImg from '../../images/ultiscape/icons/document.svg';
// import inventoryImg from '../../images/ultiscape/icons/archive.svg';
import moreImg from '../../images/ultiscape/icons/drag.svg';

const AdminMobileNavBar = () => {
  return (
    <div className="cmsMobileNavBarWrapper">

      <div className="mobileNavBarButtonArray">
          <a className="button" id="button1" href="{{{rootPath}}}admin/customers"><img src={customersImg} alt="Customers" /><p>Customers</p></a>
          <a className="button" id="button3" href="{{{rootPath}}}admin/jobs"><img src={jobsImg} alt="Jobs" /><p>Jobs</p></a>
          <a className="button" id="button4" href="{{{rootPath}}}admin/invoices"><img src={documentImg} alt="Invoices" /><p>Invoices</p></a>
          <a className="button" id="button2" href="{{{rootPath}}}admin/estimates"><img src={documentImg} alt="Estimates" /><p>Estimates</p></a>
          <span className="button" id="button5"><img src={moreImg} alt="More" /></span>
      </div>

      <span id="mobileNavBarMoreMenuHider">
          <div id="mobileNavBarMoreMenuWrapper">
              <p><a href="{{{rootPath}}}admin/inventory">Inventory</a></p><p>Item 2</p><p>Item 3</p><p>Item 4</p>
          </div>
      </span>


  </div>
  )
}

export default AdminMobileNavBar;