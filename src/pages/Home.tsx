import React from 'react'
import { common } from '../common/common'

const Home = () => {
	const lng = common.lng;
	return (
		<div id="Home">
			<div className="row">
				<h2>Merlin web programozási keretrendszer</h2>
				<div className="col-8">
					<h3>Tulajdonságok</h3>
					<ul>
						<li>Kliens - szerver struktúra,</li>
						<li>User regist/login rendszer,</li>
						<li>Google kétfaktoros hitelesítés támogatása,</li>
						<li>Bejelentkezés Google, facebook fiókkal,</li>
						<li>"Nyers erő" támadás elleni védelem,</li>
						<li>Képek scroll pozició függő letöltése,</li>
						<li>PHP és React unittest rendszer,</li>
						<li>Könnyű telepíthetőség, nem szükséges konzol hozzáférés</li>
					</ul>
					<h3>Technológiák</h3>
					<ul>
						<li>PHP, MYSQL szerver, Javascript, React, kliens</li>
						<li>npm (csak lokális telpitéshez, teszteléshez kell)</li>
						<li>Nodejs (csak react unittesthez kell)</li>
						<li>Axios kliens-szerver komunikáció,</li>
						<li>Egyedi php/mysql interface (Laravel szerű),</li>
						<li>PHPUNIT php unittest,</li>
						<li>Jest react unittest,</li>
						<li>JQuery,</li>
						<li>Bootstrap,</li>
						<li>Font awesome</li>
					</ul>
				</div>
				<div className="col-4">
				<img src="images/merlin.png" />
				</div>
			</div>
			<div className="row">
				<div className="col-4"></div>
				<div className="col-4">
					<img className="logo" src="images/utopszkij_fw.png" />
				</div>
				<div className="col-4"></div>
			</div>

			<div id="portfolio" className="__row">
				<div className="col-12">
				<div className="container-fluid p-0">
						<div className="row g-0">
							<h3 className="text-center">Egyéb projektjeim</h3>
							<div className="col-lg-4 col-sm-6">
								<a className="portfolio-box" href="https://netpolgar.hu" title="Netpolgár">
									<img className="img-fluid" src="images/netpolgar-logo.png" alt="netpolgar" />
									<div className="portfolio-box-caption">
										<div className="project-category text-white-50">NET-demokrácia</div>
										<div className="project-name">Netpolgár</div>
									</div>
								</a>
							</div>
							<div className="col-lg-4 col-sm-6">
								<a className="portfolio-box" href="https://befalom.hu" 
								title="Szakácskönyv">
									<img className="img-fluid" src="images/befalom-logo.png" alt="befalom.hu" />
									<div className="portfolio-box-caption">
										<div className="project-category text-white-50">Gasztronómia</div>
										<div className="project-name">Szakácskönyv</div>
									</div>
								</a>
							</div>
							<div className="col-lg-4 col-sm-6">
								<a className="portfolio-box" href="https://maryland.siriusworld.hu/" title="Maryland">
									<img className="img-fluid" src="images/maryland-logo.jpg" alt="maryland" />
									<div className="portfolio-box-caption">
										<div className="project-category text-white-50">Kreatív hobby</div>
										<div className="project-name">Maryland</div>
									</div>
								</a>
							</div>
							<div className="col-lg-4 col-sm-6">
								<a className="portfolio-box" href="https://raktar.siriusworld.hu" title="Raktár">
									<img className="img-fluid" src="images/raktar-logo.jpg" alt="Raktár" />
									<div className="portfolio-box-caption">
										<div className="project-category text-white-50">Logisztika</div>
										<div className="project-name">Raktár kezelés</div>
									</div>
								</a>
							</div>
							<div className="col-lg-4 col-sm-6">
								<a className="portfolio-box" href="http://piac.infora.hu/index.php" title="Piac">
									<img className="img-fluid" src="images/piac-logo.jpg" alt="piac" />
									<div className="portfolio-box-caption">
										<div className="project-category text-white-50">piac</div>
										<div className="project-name">Piac szimuláció</div>
									</div>
								</a>
							</div>
							<div className="col-lg-4 col-sm-6">
								<a className="portfolio-box" href="https://balmix.hu" title="Balmix">
									<img className="img-fluid" src="images/balmix.png" alt="BALMIX" />
									<div className="portfolio-box-caption p-3">
										<div className="project-category text-white-50">WEB folyóirat</div>
										<div className="project-name">BALMIX</div>
									</div>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>	

			<div className="row message">
				<form action="./api/contact.php" method="post">
					<input type="hidden" name="subject" value="contact" />
					<div className="col-12">
						<h2>Kapcsolat felvétel</h2>
					</div>	
					<div className="col-2">
						<img src="images/tibor.jpg" />
					</div>
					<div className="col-10">	
						<label>A te E-mail címed:</label>
						<input type="text" name="myEmail" id="myEmail" className="form-control" />
						<br />
						<label>A te neved:</label>
						<input type="text" name="myName" id="myName" className="form-control" />
						<br />
						<label>Üzenet:</label>
						<textarea id="emailBody" name="messageBody" className="form-control"></textarea>
					</div>
					<div className="col-12">&nbsp;</div>
					<div className="col-5"></div>
					<div className="col-2"><button type="submit" className="btn btn-primary">
						<em className="fas fa-envelope"></em>{ lng('SEND') }</button>
					</div>
					<div className="col-5"></div>
				</form>	
			</div>	
		</div>
		
	)
}
export default Home
