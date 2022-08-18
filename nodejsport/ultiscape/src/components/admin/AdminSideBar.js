import React from 'react';
import { Link } from 'react-router-dom';

import customersImg from '../../images/ultiscape/icons/users.svg';
import jobsImg from '../../images/ultiscape/icons/calendar_month.svg';
import documentImg from '../../images/ultiscape/icons/document.svg';
import inventoryImg from '../../images/ultiscape/icons/archive.svg';
import moreImg from '../../images/ultiscape/icons/drag.svg';

const AdminSideBar = () => {
  return (
    <div className="cmsSideBarWrapper">

        <Link className="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button1" to="/admin/customers"><img src={customersImg} alt='Customers'/><p>Customers</p></Link>
        <Link className="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button3" to="/admin/jobs"><img src={jobsImg} alt='Jobs'/><p>Jobs</p></Link>
        <Link className="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button4" to="/admin/invoices"><img src={documentImg} alt='Invoices'/><p>Invoices</p></Link>
        <Link className="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button2" to="/admin/estimates"><img src={documentImg} alt='Estimates'/><p>Estimates</p></Link>
        <a className="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button5"><img src={moreImg} alt="More..."/><p>More...</p></a>

        <div id="smallBottomLinks">
            <Link className="noUnderline" to="/admin/overview">Overview</Link> | <Link className="noUnderline" to="/admin/sitemap">Sitemap</Link>
        </div>

        <div id="sideBarMoreMenu">
            <Link className="noUnderline sideBarButton defaultAll4InsetShadow aniBold" id="button6" to="/admin/inventory"><img src={inventoryImg} alt='Inventory'/><p>Inventory</p></Link>
        </div>

    </div>
  )
}

export default AdminSideBar;