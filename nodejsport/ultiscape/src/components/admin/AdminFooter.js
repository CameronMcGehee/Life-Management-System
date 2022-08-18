import React from 'react'

const AdminFooter = ({isLogin, year, version}) => {

  var classToUse;
  if (isLogin) {
    classToUse = 'cmsLoginFooterWrapper';
  } else {
    classToUse = 'cmsFooterWrapper';
  }

  classToUse += ' defaultInsetShadow';
  return (
    <div className={classToUse}>
        <p>Copyright &copy <a class="noUnderline" target="_blank" href="https://cameronmcgehee.com">McGehee Enterprises</a> {year} - <b>v{version}</b></p>
    </div>
  )
}

export default AdminFooter;