'use client';

import { EventHandler } from 'react';
import axios from 'axios';
import $ from 'jquery'; 
import config from '../config.json';

/**
 * methods::
 *   pushAxiosResult(obj):void, popAxiosResult():object, clearAxiosResult():void
 *   pushAxiosError(obj):void, popAxiosError():object, clearAxiosError():void
 *   popupMsg(html:string, style?:msgStyle): void
 *   popupConfirm(html:string, yesfun:callableFunction,
 *                             nofun?callableFunction,
 *                             btnYesStyle: btnStyle, btnNoStyle: btnStyle):void                                 
 *   popupClose()
 *   callApi(url:string, par:object, thenFun: function): object
 *   setLngTokens(par:object)
 *   lng(token:string): string
 *   setCookie(name:string, value:string, days:number)
 *   getCookie(name:string)
 *   gotoNewPage(url)
 *   scrollTo(x,y)
 */

export enum msgStyle {
    info = 'alert-info',
    error = 'alert-danger',
    success = 'alert-success'
}

export enum btnStyle {
    primary = 'btn-primary',
    secondary = 'btn-secondary',
    danger = 'btn-danger'
}

var _axiosResults: Array<{data?:any, error?:any}> = [];
var _data: any = {};
export let axiosLog: Array<{url:string, par:any}> = [];
export var common = {

    _lngTokens : {},

    /**
     * get or set common data
     * @param name 
     * @param value 
     * @returns 
     */
    data: (name:string, value?:any) =>{
        let w = name as keyof typeof _data;
        if (value !== undefined) {
            _data[w] = value;
        }
        return _data[w];
        
    },

    /**
     * show message in popup windpw
     * @param html string 
     * @param optional className msgStyle.info | msgStyle.error | msgStyle.success
     * @returns 
     */
    popupMsg: (html:string, className?: msgStyle): void => {
        // popupMsg create for unittest
        if ($('#popupMsg').html() === undefined) {
            let popupMsg = document.createElement('div');
            popupMsg.id = 'popupMsg';
            let popupVar = document.createElement('var');
            popupMsg.append(popupVar);
            document.body.append(popupMsg);
        }
        if (className === undefined) className = msgStyle.info;
        $('#popupMsg var')?.html(html);
        $('#popupMsg')?.removeClass('alert-danger');
        $('#popupMsg')?.removeClass('alert-success');
        $('#popupMsg')?.removeClass('alert-info');
        $('#popupMsg')?.addClass(className);
        $('.popupClose button')?.on('click',common.popupClose);
        $('#popupMsg')?.show();
        return;
    },

    /**
     * confirm in popup windpw
     * @param html string 
     * @param yesfun callable Function () => void
     * @param optional nofun callable Function () => void
     * @param optional btnYesClassName btnStyle.primary | btnStyle.secondary | btnStyle.danger
     * @param optional btnNoclassName  
     * @returns 
     */
    popupConfirm: (html:string, 
        yesfun: EventHandler<any>,
        nofun?: EventHandler<any>,
        btnYesClassName?: btnStyle,
        btnNoClassName?: btnStyle): void => {
        // popupConfirm create for unittest
        if ($('#popuConfig').html() === undefined) {
            let popupConfirm = document.createElement('div');
            popupConfirm.id = 'popupConfirm';
            let popupVar = document.createElement('var');
            popupConfirm.append(popupVar);
            let btnYes = document.createElement('button');
            btnYes.id = 'btnYes';
            popupConfirm.append(btnYes);
            let btnNo = document.createElement('button');
            btnNo.id = 'btnNo';
            popupConfirm.append(btnNo);
            document.body.append(popupConfirm);
        }
        if (nofun === undefined) nofun = () => {};
        if (btnYesClassName === undefined) btnYesClassName = btnStyle.primary;
        if (btnNoClassName === undefined) btnNoClassName = btnStyle.secondary;
        $('#popupConfirm var')?.html(html);
        $('#popupConfirm #btnYes')?.on('click',yesfun)
        $('#popupConfirm #btnYes')?.removeClass('btn-primary');
        $('#popupConfirm #btnYes')?.removeClass('btn-secondary');
        $('#popupConfirm #btnYes')?.removeClass('btn-danger');
        $('#popupConfirm #btnYes')?.addClass(btnYesClassName);
        $('#popupConfirm #btnNo')?.on('click',nofun);
        $('#popupConfirm #btnNo')?.removeClass('btn-primary');
        $('#popupConfirm #btnNo')?.removeClass('btn-secondary');
        $('#popupConfirm #btnNo')?.removeClass('btn-danger');
        $('#popupConfirm #btnNo')?.addClass(btnNoClassName);
        $('.popupClose button')?.on('click',common.popupClose);
        $('#popupConfirm')?.show();
        return;
    },

    /**
     * popupMsg, popupConfirm close
     */
    popupClose: () => {
        $('#popupConfirm')?.hide();
        $('#popupMsg')?.hide();
    },

    /**
     * set lngTokens
     * @param par object
     */
    setLngTokens: (par: object): void => {
        common._lngTokens = par;
    },

    /**
     * translate token by _lngTokens
     * @param token string
     * @returns string
     */
    lng: (token:string): string => {
        let w = token as keyof typeof common._lngTokens;
        if (common._lngTokens[w] === undefined) {
            return token;
        } else {
            return common._lngTokens[w];
        }
    },

    /**
     * push object into axiosResult for unittest
     * @param optional par object  {data:{...}} | {error:{....}}
     * @returns void
     */
    pushAxiosResult: (par:object):void => {
        _axiosResults.push(par);
        return;
    },

    callApi: (url:string, par:object, thenFun:CallableFunction):any => {
        if (axiosLog.length < 10) {
            axiosLog.push({'url':url, 'par':par})
        }
        if (_axiosResults.length === 0) {
            $('#waiting').show();
            let result = axios.post(config.SITEURL+'/public/'+url, 
                              par, 
                              {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                              }  
            );
            result.then((res:any) =>{
                $('#waiting').hide();
                if (typeof res.data === 'string') {
                    common.popupMsg(res.data, msgStyle.error);
                } else {
                    thenFun(res);
                }
            });
            result.catch( (error:any) => {
                $('#waiting').hide();
                console.log('axios error ',url);
                console.log('par:');
                console.log(par)
                console.log(error);
            } );
            return result;
        } else {
            let w = _axiosResults[0];
            _axiosResults.splice(0,1);
            if (w.error === undefined) thenFun(w);
            return {
                then: ( fun: (par:object) => void) => {
                    
                },
                catch: (fun: (par:object) => void) => {
                    if (w.error !== undefined) fun(w);
                }
            }
        }
    },
    
    /**
     * set data into Cookie call only in useEffect!
     * @param cname string
     * @param cvalue string
     * @param exdays number
     */
    setCookie: (cname:string, cvalue:string, exdays:number) => {
        const d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        let expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";sameSite=Lax;path=/";
    },
    
    /**
     * get data from cookie use only in useEffect !
     * @param cname string
     * @returns string | ''
     */
    getCookie: (cname:string) => {
        let name = cname + "=";
        let decodedCookie = decodeURIComponent(document.cookie);
        let ca = decodedCookie.split(';');
        for(let i = 0; i <ca.length; i++) {
          let c = ca[i];
          while (c.charAt(0) === ' ') {
            c = c.substring(1);
          }
          if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
          }
        }
        return "";
    },

    /**
     * session start call only in useEffect !
     * @params none
     * @returns  data('sid'), data('session') 
     */
    sessionStart: () => {
        let sid = common.getCookie('sid');
        if ((sid === undefined) || (sid === '') || (sid === 'undefined')) {
            common.callApi('api/getsid.php',{},
            (res:{data:{sid:string}}) => {
                if (typeof res.data === 'string') {
                    common.popupMsg(res.data, msgStyle.error);
                } else {
                            sid = res.data.sid;
                            common.setCookie('sid',sid,config.COOKIE_EXPIRE_DAYS);
                            common.data('sid',sid);
                            common.data('session',{});
                }            
            });
        } else {
            common.data('sid',sid);
            common.callApi('api/getsession.php',{'sid':sid},
            (res:any) => {
                if (typeof res.data === 'string') {
                    common.popupMsg(res.data, msgStyle.error);
                } else {
                    common.data('session',res.data);
                }    
            })
        }	
    },

    /**
     * in browser: navigate into new page
     * in unit test: set 'href' coookie
     * @param url 
     */
    gotoNewPage(url:string) {
        if ((typeof exports) !== 'object' ) {
            // run in browser, not in jest test
            document.location.href = url;
        } else {
            // run in jest test
            common.setCookie('href',url,1);
        }	
    },

    /**
     * window.scrollTo (not sen errorMsg in unittest)
     * @param x 
     * @param y 
     */
    scrollTo(x:number, y:number) {
        Object.defineProperty(window, 'scrollTop', {value:x, writable: true});
        Object.defineProperty(window, 'scrollLeft', {value:y, writable: true});
    }

}














