import React from 'react';

import { useState } from 'react';

const AdminLoginPage = () => {

  const [usernameEmail, setUsernameEmail] = useState('');
  const [password, setPassword] = useState('');

  const updateUsernameEmailField = async (e) => {
    setUsernameEmail(e.target.value);
  }

  const updatePasswordField = async (e) => {
    setPassword(e.target.value);
  }

  const submitClick = (e) => {
    e.preventDefault();
    // Fetch
  }
  
  return (
    <div className="cmsLoginFormArea defaultMainShadows">

      <h1 className="centered">Admin Login</h1>

      <form className="defaultForm" id="loginForm" style={{marginLeft: '2em', marginRight: '2em'}}>
          
          <label for="usernameEmail"><p>Username/Email</p></label>
          <input className="defaultInput" type="text" name="usernameEmail" id="usernameEmail" placeholder="Username/Email..." value={usernameEmail} onChange={updateUsernameEmailField} />
          <span id="usernameEmailError" className="underInputError" style={{display: 'none'}}>Enter a username or email.</span>
          <span id="noUsernameEmailError" className="underInputError" style={{display: 'none'}}>There is no account with this username or email.</span>
          
          <br /><br />
          
          <label for="password"><p>Password</p></label>
          <input className="defaultInput" type="password" name="password" id="password" placeholder="Password..." value={password} onChange={updatePasswordField} />
          <span id="passwordError" className="underInputError" style={{display: 'none'}}>Enter your password.</span>
          <span id="noMatchError" className="underInputError" style={{display: 'none'}}>Password is incorrect.</span>

          <input type="hidden" name="authToken" id="authToken" value="{{{adminLoginAuthToken}}}" />
          
          <br /><br />
          
          <button onClick={submitClick} className="smallButtonWrapper greenButton xyCenteredFlex centered defaultMainShadows" type="submit">Go!</button>

      </form>

      <br />

      <p className="centered">Don't have an account? <a href="/admin/createaccount">Create one here!</a></p>

    </div>
  )
}

export default AdminLoginPage;
