import React, { useEffect, useState } from 'react';
import { common } from "../common/common"; 
import config from "../config.json";
import $ from 'jquery'; 

// https://hackernoon.com/implement-form-validation-in-react-without-any-libraries


const Register = () => {
	const lng = common.lng;
	const [username, setUsername] = useState('');
	const [password, setPassword] = useState('');
	const [password2, setPassword2] = useState('');
	const [realname, setRealname] = useState('');
	const [email, setEmail] = useState('');
	const [two_factor, setTwo_factor] = useState(0);
	const [accept, setAccept] = useState('0');
	
	// onLoad event handler
	useEffect(() => {
		$('#waiting').hide();
	});	


	const validator = () => {

		var lowerLetter = 0;
		var numberChar = 0;
		var specChar = 0;
		var capitalChar = 0;
		$('#email').removeClass('is-invalid');
		$('#username').removeClass('is-invalid');
		$('#realname').removeClass('is-invalid');
		$('#email').removeClass('is-invalid');
		$('#accept').removeClass('is-invalid');
		$('#password').removeClass('is-invalid');
	
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

		let w:Array<string> = [];
		if (accept === '0') {
			 w.push('ACCEPT_REQUIRED');
			 $('#accept').addClass('is-invalid');
		}	 
		if (password !== password2) {
			w.push('TWO_PASSWORD_NOT_EQUALS');
			$('#password').addClass('is-invalid');
		}	
		if (username == '') {
			w.push('USERNAME_REQUIRED');
			$('#username').addClass('is-invalid');
		}
		if (realname == '') {
			w.push('REALNAME_REQUIRED');
			$('#realname').addClass('is-invalid');
		}
		if (email == '') {
			w.push('EMAIL_REQUIRED');
			$('#email').addClass('is-invalid');
		}
		if ((lowerLetter === 0) ||
		    (capitalChar === 0) ||
		    (numberChar === 0) ||
		    (specChar === 0)) {
			w.push('PASSWORD_INVALID');
			$('#password').addClass('is-invalid');
		}
		let avatar = document.getElementById('avatar') as HTMLInputElement;
		let files = avatar?.files;
		if (files) {
			if (files.length > 0) {
				if (files[0].size > (config.UPLOAD_LIMIT * 1048576)) {
					w.push('UPLOAD_FILE_TOO_LARGE'+files[0].size);
				}
			}	
		}	

		if (w.length == 0) {
			// local valid ok; next step remote valid
			common.callApi('api/registcheck.php',{
				"sid":common.data('sid'),
				"username": username,
				"email": email,
				"userid":'0'
			},
			(res:any) => { 
				if (res.data.ok) {
					// remote valid ok, next step store
					let formData = new FormData();
					formData.append('sid',common.data('sid'));
					formData.append('username',username);
					formData.append('email',email);
					formData.append('userid','0');
					formData.append('realname',realname);
					formData.append('password',password);
					formData.append('two_factor',two_factor.toString());
					formData.append('accept',accept);

					// file upload processing
					if (files) {
						if (files.length > 0) {
							formData.append('avatar',files[0]);   
						}	
					}
					common.callApi('api/doregist.php',formData,
					(res:any) => {
						if (res.data.ok) {
							common.popupMsg( lng('ACTIVATOR_EMAIL_SENDED') );
						} else {
							common.popupMsg( lng(res.data.errorMsg) );
						}
					});
				} else {
					// remote valid false
					common.popupMsg( lng(res.data.errorMsg) );
				}	
			})	  
		} else {
			// local valid false
			common.popupMsg( lng(w.toString()) ) ;
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
					<label><em className="fa fa-user"></em>{ lng('USERNAME') }*&nbsp;</label>
					<input type="text" name="username" id="username" onChange={ (e) => setUsername(e.target.value) } 
						required className="form-control" autoFocus />
					</div>
				</div>	
				<div className="row text-center">
					<div className="col-12">
					<label><em className="fa fa-key"></em>{ lng('PASSWORD') }*&nbsp;</label>
					<input type="password" name="password" id="password" required
						className="form-control password" 
						minLength={8}
						onChange={ (e) => setPassword(e.target.value) } />
					</div>	
				</div>	
				<div className="row text-center">
					<div className="col-12">
						<span className="help">{ lng('PASSWORD_HELP') }</span>
					</div>	
				</div>	
				<div className="row text-center">
					<div className="col-12">
					<label><em className="fa fa-key"></em>{ lng('PASSWORD_RETYPE') }*&nbsp;</label>
					<input type="password" name="password2" id="password2"
					className="form-control password" onChange={ (e) => setPassword2(e.target.value) }/>
					</div>	
				</div>	
				<div className="row text-center">
					<div className="col-12">
					<label><em className="fas fa-id-badge"></em>{ lng('REALNAME') }*&nbsp;</label>
					<input type="text" name="realname" id="realname"
					required className="form-control" onChange={ (e) => setRealname(e.target.value) }/>
					</div>	
				</div>	
				<div className="row text-center">
					<div className="col-12">
					<label><em className="fas fa-envelope"></em>{ lng('EMAIL') }*&nbsp;</label>
					<input type="email" name="email" id="email"
					required className="form-control" onChange={ (e) => setEmail(e.target.value) }/>
					</div>	
				</div>	
				<div className="row text-center">
					<div className="col-12">
						<label><em className="fas fa-camera"></em>{ lng('AVATARFILE') }&nbsp;</label>
						<input type="file" name="avatar" id="avatar" className="form-control" />
					</div>	
				</div>	
				<div className="row text-center">
					<div className="col-12">
						<span className="help">max. { config.UPLOAD_LIMIT}M { config.IMAGE_FILTER.toString() }</span>
					</div>
				</div>	

				<div className="row text-center">
					<div className="col-12">
						<label>{ lng('TWOFACTOR') }&nbsp;</label>
						<select name="two_factor" className="form-control" onChange={ (e) => setTwo_factor(e.target.selectedIndex) }>
							<option value="0">{ lng('NO') }</option>
							<option value="1">{ lng('YES') }</option>
						</select>
					</div>
				</div>	

				<div className="row text-center">
					<div className="col-12">
						<input type="checkbox" className="form-check-input" name="accept" id="accept" value="1" 
						onChange={ (e) => setAccept(e.target.value) }/>
						<strong>{ lng('POLICYACCEPT') }</strong>
						<br />
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
					<p><strong>{ lng('FORMHELP') }</strong></p>
					<p><strong>{ lng('REGISTHELP') }</strong></p>
					<p><strong>{ lng('REGISTHELP2') }</strong></p>
				</div>
			</div>
		</div>
	)
}
export default Register
