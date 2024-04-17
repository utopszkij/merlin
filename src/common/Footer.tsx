import React, { useEffect } from 'react';
import { useState } from "react";
import { common, msgStyle } from "../common/common";
import $ from 'jquery'; 
import { json } from 'stream/consumers';
  
const Footer = () => {
  	
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
    <div id="Footer" className="row">
        <div className="col-12">
            <section className="mb-4">
            <span>{ lng('SHARE') }</span>&nbsp;	
            <a className="btn btn-outline-light btn-floating m-1" title="Facebbok megosztás" id="shareFb"
                href={'https://www.facebook.com/sharer/sharer.php?u='+encodeURI(document.location.href)}
                role="button" target="_new"><em className="fab fa-facebook-f"></em></a>

            <a className="btn btn-outline-light btn-floating m-1" title="Twitter megosztás" id="shareTw"
                href={'https://twitter.com/intent/tweet?&url='+encodeURI(document.location.href)}
                role="button" target="_new"><i className="fab fa-twitter"></i></a>

            <a className="btn btn-outline-light btn-floating m-1" title="url küldése gmail -en" id="shareMail"
                href={'https://mail.google.com/mail/?view=cm&body='+encodeURI(document.location.href)}
                role="button" target="_new"><i className="fas fa-envelope"></i></a>
            </section>
        </div>

        <div className="col-12">	
                <a href="./impressum"><em className="fas fa-hand-point-right"></em>&nbsp;Imresszum</a>&nbsp;&nbsp;&nbsp;
                <a href="./policy"><em className="fas fa-hand-point-right"></em>&nbsp;{ lng('POLICY') }</a>&nbsp;&nbsp;&nbsp;
                <a href="./terms"><em className="fas fa-hand-point-right"></em>&nbsp;{ lng('TERMS') }</a>&nbsp;&nbsp;&nbsp;
                <a href="./protest"><em className="fas fa-hand-point-right"></em>&nbsp;{ lng('PROTEST') }</a>&nbsp;&nbsp;&nbsp;
                <a href="./licence"><em className="fas fa-hand-point-right"></em>&nbsp;{ lng('LICENCE') }</a>&nbsp;&nbsp;&nbsp;
                <a href="https://github.com/utopszkij/utopszkij_fw" target="_new">
                <em className="fas fa-hand-point-right"></em>&nbsp;{ lng('SOURCE') }</a>&nbsp;&nbsp;&nbsp;
        </div>	
        <div className="col-12">	
                Ⓒ 2024 { lng('COPYRIGHT') }
        </div>	
        <div className="col-12">	
                <a href="./ponzor"><em className="fas fa-hand-point-right"></em>&nbsp;{ lng('PONZOR') }</a>&nbsp;&nbsp;&nbsp;
        </div>	

    </div>
   );     

};

export default Footer;
