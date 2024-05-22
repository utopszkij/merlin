import React, { useEffect } from 'react';
import { useState } from "react";
import { common, msgStyle } from "../common/common";
// import Profile from './Profile';
import $ from 'jquery'; 
import { json } from 'stream/consumers';
  
const Mainmenu = () => {
  	

  const lng = common.lng;
  const sid = common.getCookie('sid');	
  const logedId = common.getCookie('logedId');	
  const logedNick = common.getCookie('logedNick');	
  const logedAvatar = common.getCookie('logedAvatar');	
  
  // onLoad event handler
   useEffect(() => {
    $('#waiting').hide();
  });	


    return (
    <div id="Mainmenu">
        <nav className="navbar navbar-expand-lg navbar-light bg-light">
        <div className="container-fluid">
            <a className="navbar-brand" href="#">
                <img src="images/merlin.png" className="logo" title="Merlin" />
            </a>
            <button className="navbar-toggler" type="button" data-bs-toggle="collapse"
                 onClick={ () => {$('#navbarSupportedContent').toggle()}}
                 data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span className="navbar-toggler-icon"></span>
            </button>
            <div className="collapse navbar-collapse" id="navbarSupportedContent">
            <ul className="navbar-nav me-auto mb-2 mb-lg-0">
            <li className="nav-item">
                <a className="nav-link active" aria-current="page" href="./home">{ lng('HOME') }</a>
                </li>
                <li className="nav-item">
                <a className="nav-link active" aria-current="page" href="./description">{ lng('DESCRIPTION') }</a>
                </li>
                <li className="nav-item">
                <a className="nav-link active" aria-current="page" href="./groups">{ lng('GROUPS') }</a>
                </li>

                { (logedId > '') &&
                ( <li className="nav-item dropdown">
                    <a className="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                        onClick = { () => { $('#dropdown1').toggle(); return false }}
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img src={ logedAvatar } className="avatar" />    
                        { logedNick }
                    </a>
                    <ul className="dropdown-menu" aria-labelledby="navbarDropdown" id="dropdown1">
                        <li><a className="dropdown-item" href="./profile">{ lng('PROFILE') }</a></li>
                        <li><a className="dropdown-item" href="./logout">{ lng('LOGOUT') }</a></li>
                    </ul>
                </li>
                )
                }
                { (( logedId <= '') || ( logedId === undefined)) &&
                    (
                    <li className="nav-item">
                    <a className="nav-link active" aria-current="page" href="login">{ lng('LOGIN') }/{ lng('REGIST') }</a>
                    </li>
                    )
                }
            </ul>
            </div>
        </div>
        </nav>        
    </div>
   );     

};

export default Mainmenu;
