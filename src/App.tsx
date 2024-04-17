'use client';
import React, { useEffect } from 'react';
import { Routes, Route } from "react-router-dom";
import { lazy, Suspense } from 'react';
import { common  } from "./common/common";
import { lngTokens } from "./common/hu";
import config from "./config.json";

import $ from 'jquery'; 
import 'bootstrap/dist/css/bootstrap.min.css';

const Home = lazy(() => import('./pages/Home'));
const Login = lazy(() => import('./pages/Login'));
const Logout = lazy(() => import('./pages/Logout'));
const Register = lazy(() => import('./pages/Register'));
const Description = lazy(() => import('./pages/Description'));
const Policy = lazy(() => import('./pages/Policy'));
const Impressum = lazy(() => import('./pages/Impressum'));
const Terms = lazy(() => import('./pages/Terms'));
const Protest = lazy(() => import('./pages/Protest'));
const Licence = lazy(() => import('./pages/Licence'));
const Ponzor = lazy(() => import('./pages/Ponzor'));

const Mainmenu = lazy(() => import('./common/Mainmenu'));
const Footer = lazy(() => import('./common/Footer'));

function App() {
	// set language tokens
	common.setLngTokens(lngTokens);

   	// onLoad event handler                                                       
	useEffect(() => {
		common.sessionStart();
		common.data('SITEURL',config.SITEURL);
		common.data('SITENAME',config.SITENAME);
		common.data('ADATKEZELO',config.ADATKEZELO);
		common.data('ADATFELDOLGOZO',config.ADATFELDOLGOZO);
		common.data('SIGNO',config.SIGNO);
		$('#waiting').hide();
	});	

	return (
		<>
		<Suspense fallback={< hr />}>
			<Mainmenu />
			<div id="pageContent">
			<Routes>
				<Route path="/" element={<Home />} />
				<Route path="/home" element={<Home />} />
				<Route path="/login" element={<Login />} />
				<Route path="/logout" element={<Logout />} />
				<Route path="/register" element={<Register />} />
				<Route path="/impressum" element={<Impressum />} />
				<Route path="/profile" element={<Register />} />
				<Route path="/description" element={<Description />} />
				<Route path="/policy" element={<Policy />} />
				<Route path="/terms" element={<Terms />} />
				<Route path="/protest" element={<Protest />} />
				<Route path="/licence" element={<Licence />} />
				<Route path="/ponzor" element={<Ponzor />} />
			</Routes>
			</div>
			<Footer />
		</Suspense>
		</>
	);

}
export default App;
