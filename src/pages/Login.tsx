import React, { useEffect } from 'react';
import { useState } from "react";
import { common, msgStyle } from "../common/common";
import Register from './Register';
import $ from 'jquery'; 
  
type DoLoginResult = {
	data:{
		loged:boolean,
		errorMsg:string,
		id:string,
		nick:string,
		avatar:string,
		groups:string    // list of groupIds
	}
}

type MailSenderResult = {
	data:{
		mailsended:boolean
	}
}

const Login = () => {
  	
  //variables whose value can change during runtime
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [task, setTask] = useState("login");
  const [key, setKey] = useState("");
  const lng = common.lng;
  const sid = common.getCookie('sid');	

  // onLoad event handler
	   useEffect(() => {
		$('#waiting').hide();
	});	


  /**
   * success login store loged user into cookie
   * if defined cookie.redirect goto redirect
   *    else goto /home
   * @param res 
   */	
  const successLogin = (res:DoLoginResult) => {
	common.setCookie('logedId',res.data.id,10);
	common.setCookie('logedNick',res.data.nick,10);
	common.setCookie('logedAvatar',res.data.avatar,10);
	common.setCookie('logedGroups',res.data.groups,10);
	let redirect = common.getCookie('redirect');
	if ((redirect === '') || (redirect === undefined)) {
		redirect = './home';
	}
	common.gotoNewPage(redirect) ;
  }	

  /** validate import fields
	* if OK call api
	* else show errorMsg, set error class, set focus
	* @return void 
  */	
  const doLogin = () => {
	common.setCookie('logedId','',10);
	common.setCookie('logedNick','',10);
	common.setCookie('logedAvatar','',10);
	common.setCookie('logedGroups','',10);
	if (email === '') {
		common.popupMsg(lng('NICK_OR_EMAIL_REQUIRED'), msgStyle.error);
	} else if (password === '') {
		common.popupMsg(lng('PASSWORD_REQUIRED')+'/'+password+'/', msgStyle.error);
	} else {
		common.callApi('api/dologin.php',{
			'sid': sid,
			'email': email,
			'password':password},
		(res:DoLoginResult) => { 
			if (res.data.loged) {
				successLogin(res);
			} else {
				if (res.data.errorMsg == 'TWO_FACTOR') {
					setTask('two_factor');
				} else {
					common.popupMsg(lng(res.data.errorMsg), msgStyle.error);
				}   
			}	
		});	
	}
  };

  /**
   * user click regist button
   */
  const registClick = () => {
	setTask('regist');
  }

  /**
   * user click forgetPassword button
   */
  const forgetPasswordClick = () => {
	if (email === '') {
		common.popupMsg(lng('NICK_OR_EMAIL_REQUIRED'), msgStyle.error);
	} else {
		common.callApi('api/forgetpassword.php',{
			'sid': sid,
			'email': email},
		(res:MailSenderResult) => { 
			if (res.data.mailsended) {
				common.popupMsg(lng('EMAIL_SENDED'), msgStyle.success);
			} else {
				common.popupMsg(lng('WRONG_DATA'), msgStyle.error);
			}	
		});	
	}
  }

  /**
   * user click resebdEmail button
   */
  const resendEmailClick = () => {
	if (email === '') {
		common.popupMsg(lng('NICK_OR_EMAIL_REQUIRED'), msgStyle.error);
	} else {
		common.callApi('api/sendactivatoremail.php',{
			'sid': sid,
			'email': email},
		(res:MailSenderResult) => { 
			if (res.data.mailsended) {
				common.popupMsg(lng('EMAIL_SENDED'), msgStyle.success);
			} else {
				common.popupMsg(lng('WRONG_DATA'), msgStyle.error);
			}	
		});	
	}
   }

   /**
	* user send two factor key
	*/
   const doTwoFactorKey = () => {
		common.callApi('api/dotwofactorkey',{
			'sid':sid,
			'key':key
		}, 
		(res:DoLoginResult) => {
			if (res.data.loged) {
				successLogin(res);
			} else {
				common.popupMsg(lng(res.data.errorMsg), msgStyle.error);
			}
		})
   }

  return (

		<>
		{(task === 'regist') &&
			( <Register />)
		}
		{(task === 'two_factor') &&
			( 
				<div id="twoFactor" className="form">
				<div className="row text-center">
					<h1>
							<em className="fas fa-sign-in-alt"></em>&nbsp;
							<span>{ lng('LOGIN') }</span>
					</h1>
				</div>	
				<form name="login" action="index.php" method="post" 
					id="formLogin">
					<div className="row text-center">
						<div className="col-12">
							<em className="fas fa-user"></em>&nbsp;
							<label>{ lng('TWO_FACTOR_KEY') }</label><br />
							<input type="text" name="key" id="key" 
								onChange={(e) => setKey(e.target.value)} autoFocus
								required />
						</div>	
					</div>	
					<div className="row text-center">
						<div className="col-12">
							<button type="button" className="btn btn-success" name="submit" id="doLogin" onClick={ doTwoFactorKey }>
								<em className="fas fa-check"></em>&nbsp;{ lng('OK') }
							</button>   
						</div>
					</div>	
				</form>
			</div>
			)
		}
		{(task == 'login') &&
		(
		<div id="login" className="form">
			<div className="row text-center">
				<h1>
						<em className="fas fa-sign-in-alt"></em>&nbsp;
						<span>{ lng('LOGIN') }</span>
				</h1>
			</div>	
			<form name="login" action="index.php" method="post" 
				id="formLogin">
				<div className="row text-center">
					<div className="col-12">
						<em className="fas fa-user"></em>&nbsp;
						<label>{ lng('NICKNAME_OR_EMAIL') }</label><br />
						<input type="text" name="email" id="email" value={ email }
							onChange={(e) => setEmail(e.target.value)} autoFocus
							required />
					</div>	
				</div>	
				<div className="row text-center">
					<div className="col-12">
						<em className="fas fa-key"></em>&nbsp;
						<label>{ lng('PASSWORD') }</label><br />
						<input type="password" id="password" name="password" value={ password }
								onChange={(e) => setPassword(e.target.value)}
								required />
					</div>
				</div>	
				<div className="row text-center">
					<div className="col-12">
						<a href="./poliicy" target="_new">{ lng('ACCEPT1') }</a>&nbsp;
						{ lng('ACCEPT2')}
					</div>
				</div>	

				<div className="row text-center">
					<div className="col-12">
						<button type="button" className="btn btn-success" name="submit" id="doLogin" onClick={ doLogin }>
							<em className="fas fa-check"></em>&nbsp;{ lng('OK') }
						</button>   
					</div>
				</div>	
				<br /><br />
				<div className="row text-center">
				<div className="col-12">
						<a className="btn btn-secondary" href="https://netpolgar.hu/auth/facebook?state=SITEURL/redirect"> 
							{ lng('FBLOGIN') }			
						</a>
					</div>
					<div className="col-12">
						<a href="'https://netpolgar.hu/auth/google?state='+SITEURL+'/'+atob(redirect)" 
							className="btn btn-secondary"> 
							{ lng('GOOGLELOGIN') }			
						</a>
					</div>	
				</div>	
			</form>
			<div className="alert alert-info">{ lng('REGISTHELP2') }</div>
			<div className="row">
				<div className="col-12 text-center">
					<button type="button" id="doRegister" onClick={ registClick } className="btn btn-secondary">
							{ lng('DOREGIST') }
					</button>
				</div>
			</div>
			<div className="row">
				<div className="col-12 text-center">
					<var className="btn btn-secondary" id="resendEmail" onClick={ resendEmailClick }>
							{ lng('RESEND_ACTIVATOR') }
					</var>
				</div>
			</div>
			<div className="row">
				<div className="col-12 text-center">
					<var className="btn btn-secondary" id="forgetPassword" onClick={ forgetPasswordClick }>
							{ lng('FORGET_PASSWORD') }
					</var>
				</div>
			</div>
		</div>
		)}
		</>
  		)
};

export default Login;
