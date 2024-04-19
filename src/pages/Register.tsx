import React, { useState, useEffect } from 'react';
import { common } from "../common/common"; 


// https://hackernoon.com/implement-form-validation-in-react-without-any-libraries


const Register = () => {
	const lng = common.lng;
	const [username, setUsername] = useState('');
	const [password, setPassword] = useState('');
	const [password2, setPassword2] = useState('');
	const [realname, setRealname] = useState('');
	const [email, setEmail] = useState('');
	const [avatar, setAvatar] = useState('');
	const [twofactor, setTwofactor] = useState('0');
	const [accept, setAccept] = useState('0');
	
	const validator = () => {


		console.log('validator start');
		console.log(document);
		console.log(document.getElementById('avatar'));

		var lowerLetter = 0;
		var numberChar = 0;
		var specChar = 0;
		var capitalChar = 0;
	
		// Validate lowercase letters
		var lowerCaseLetters = /[a-zöüóőúéáűí]/g;
		if(password.match(lowerCaseLetters)) {
			lowerLetter = 1;
		} else {
			lowerLetter = 0;
		}	
		
		// Validate capital letters
		var upperCaseLetters = /[A-ZÖÜÓŐÚÉÁŰÍ]/g;
		if(password.match(upperCaseLetters)) {
			capitalChar = 1;
		} else {
			capitalChar = 0;
		}
		
		// Validate numbers
		var numbers = /[0-9]/g;
		if(password.match(numbers)) {
			numberChar = 1;
		} else {
			numberChar = 0;
		}

		// Validate spec char
		if (password.match(/[\§'"+!%/=()\\|Ä÷×äđĐ\[\]łŁ$ß¤<>#&@{},;\?\.:\-_\*\\]/g)) {
			specChar = 1;
		} else {
			specChar = 0;
		}

		console.log('validator', password, lowerLetter, capitalChar, numberChar, specChar);

		if (accept === '0') {
			common.popupMsg( lng('ACCEPT_REQUIRED') );
		} else if (password !== password2) {
			common.popupMsg( lng('TWO_PASSWORD_NOT_EQUALS') );
		} else 	if ((lowerLetter === 0) ||
		    (capitalChar === 0) ||
		    (numberChar === 0) ||
		    (specChar === 0)) {
			common.popupMsg( lng('PASSWORD_INVALID') );
		} else {		
			 common.callApi('api/registcheck',{
				"sid":common.data('sid'),
				"username": username,
				"email": email,
				"userid":'0'
			 },
			 (res:any) => { 
				if (res.ok) {
					let formData = new FormData();
					formData.append('sid',common.data('sid'));
					formData.append('username',username);
					formData.append('email',email);
					formData.append('userid','0');
					formData.append('realname',realname);
					formData.append('password',password);
					formData.append('twofactor',twofactor);
					formData.append('accept',accept);

					// file upload processing
					let avatar = document.getElementById('avatar') as HTMLInputElement;
					let files = avatar?.files;
					if (files) {
						if (files.length > 0) {
							formData.append('avatar',files[0]);   
						}	
					}
					common.callApi('api/doregist.php',formData,
					(res:any) => {
						if (res.ok) {
							common.popupMsg( lng('ACTIVATOR_EMAIL_SENDED') );
						} else {
							common.popupMsg(res.errorMsg);
						}
					});
				} else {
					common.popupMsg( lng(res.errorMsg) );
				}	  
			  })	
		}
	}


	return (
		<div id="Register">
			<div id="regist" className="form">
				<div className="row text-center">
					<h1 className="ribbon-banner">
						<em className="fa fa-user-plus" aria-hidden="true"></em>&nbsp;
						<span>{ lng('REGISTRATION') }</span>
					</h1>
				</div>	
				<form name="registration" id="registForm"  method="post" 
				      encType="multipart/form-data">
				<div className="row text-center">
					<div className="col-12">
					<label><em className="fa fa-user"></em>{ lng('USERNAME') }&nbsp;</label>
					<input type="text" name="username" id="username" onChange={ (e) => setUsername(e.target.value) } 
						required className="form-control" />
					</div>
				</div>	
				<div className="row text-center">
					<div className="col-12">
					<label><em className="fa fa-key"></em>{ lng('PASSWORD') }&nbsp;</label>
					<input type="password" name="password" id="password" required
						className="form-control password" 
						minLength={8}
						onChange={ (e) => setPassword(e.target.value) } />
					</div>	
				</div>	
				<div className="row text-center">
					<div className="col-12">
					<label><em className="fa fa-key"></em>{ lng('PASSWORD_RETYPE') }</label>
					<input type="password" name="password2" 
					className="form-control password" onChange={ (e) => setPassword2(e.target.value) }/>
					</div>	
				</div>	
				<div className="row text-center">
					<div className="col-12">
					<label><em className="fas fa-id-badge"></em>{ lng('REALNAME') }&nbsp;</label>
					<input type="text" name="realname" 
					required className="form-control" onChange={ (e) => setRealname(e.target.value) }/>
					</div>	
				</div>	
				<div className="row text-center">
					<div className="col-12">
					<label><em className="fas fa-envelope"></em>{ lng('EMAIL') }&nbsp;</label>
					<input type="email" name="email" 
					required className="form-control" onChange={ (e) => setEmail(e.target.value) }/>
					</div>	
				</div>	
				<div className="row text-center">
					<div className="col-12">{ lng('AVATARFILE') }</div>
					<div className="col-12">
						<label><em className="fas fa-camera"></em>&nbsp;</label>
						<input type="file" name="avatar" id="avatar" className="form-control"
						onChange={ (e) => setAvatar(e.target.value) }/>
					</div>	
				</div>	
				<div className="row text-center">
					<div className="col-12">
						<label>{ lng('TWOFACTOR') }&nbsp;</label>
						<select name="twofactor" className="form-control" onChange={ (e) => setTwofactor(e.target.value) }>
							<option value="0">{ lng('YES') }</option>
							<option value="1">{ lng('NO') }</option>
						</select>
					</div>
				</div>	

				<div className="row text-center">
					<div className="col-12">
						<label>&nbsp;</label>
						<input type="checkbox" className="form-check-input" name="accept" value="1" 
						onChange={ (e) => setAccept(e.target.value) }/>
						<strong>{ lng('POLICYACCEPT') }</strong>
						<br />;
						<a href="./policy" target="_new">
							<em className="fas fa-hand-point-right"></em>{ lng('POLICY') }
						</a>



					</div>
				</div>	

				<div className="row text-center">
					<div className="col-12">
						<button type="button" className="btn btn-primary" name="submitbtn"
						    onClick={ validator }> 
							<em className="fas fa-check"></em>&nbsp;{ lng('OK') }
						</button>    
					</div>
				</div>	
				</form>
				<div className="alert alert-warning">
						<p><strong>{ lng('REGISTHELP') }</strong></p>
				</div>
				<div className="alert alert-info">{ lng('REGISTHELP2') }</div>
			</div>
		</div>
	)
}
export default Register
