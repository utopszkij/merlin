import React, { useEffect }  from 'react';
import { common } from "../common/common"; 

const Impressum = () => {

	const SITENAME = common.data('SITENAME');
	const ADATKEZELO = common.data('ADATKEZELO');
	const ADATFELDOLGOZO = common.data('ADATFELDOLGOZO');

   	// onLoad event handler                                                       
	   useEffect(() => {
		window.scrollTo(0,0);
	});	


	return (
		<div id="Impressum" className="row">
			<div className="col-12">
				<h1>Impresszum</h1>
				<h2>{ SITENAME }</h2>
				<p></p>
				<h2>Szerző</h2>
				<p>Fogler Tibor - tibor.fogler@gmail.com</p>
				<h2>Adat kezelő</h2>
				<div><strong dangerouslySetInnerHTML={{__html: ADATKEZELO}}></strong></div>
				<h2>Adat feldolgozó:</h2>
				<div><strong dangerouslySetInnerHTML={{__html: ADATFELDOLGOZO}}></strong></div>
				<p></p>
				<h2>Felhasznált opensource termékek</h2>
				<ul>
						<li>PHP 7 (https://php.net)</li>	  
						<li>MYSQL 5 (https://mysql.com)</li>	  
						<li>Bootstrap 5.1.3 (https://getbootstrap.com/)</li>	  
						<li>Font awesome 5.14.4 (https://fontawesome.com)</li>
						<li>React 13.4 (https://react.com/)</li>	  
						<li>Axios v1.2.0 (https://github.com/axios/axios)</li>
						<li>phpmailer 6.7.1 SMTP mailer (https://github.com/PHPMailer/PHPMailer)</li>
						<li>ckeditor 5 (https://ckeditor.com/)</li>
						<li>jquery 2.2.4 (https://jquery.com/)</li>
						<li>javascript qrcode creator 0.0.1 (https://github.com/davidshimjs/qrcodejs)</li>
						<li>google two factor authenticator (https://github.com/PHPGangsta/GoogleAuthenticator)</li>
						<li>dizájn: start bootsrap (https://startbootstrap.com) </li>
						<li>- https://www.cleanpng.com</li>
						<li>- https://pixabay.com/</li>
				</ul>	
			</div>
        </div>    
	)
}
export default Impressum
