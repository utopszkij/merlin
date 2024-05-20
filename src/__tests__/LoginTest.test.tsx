// import React from 'react';
// import ReactDOM from 'react-dom';
import { createRoot } from 'react-dom/client';
import {cleanup, fireEvent, render, act } from '@testing-library/react';
import $, { noop } from 'jquery';
import Login from '../pages/Login';
import { common, axiosLog } from '../common/common';

// DOM container for component 
// (mivel a doregist funkció modositják a képernyő tartalmat, ha a további teszthez
// ujból az eredeti tartalom kell, akkor kell az el2 -t használni)
const el = document.createElement("div");
const el2 = document.createElement("div");
var component:any = {};
var component2:any = {};

// 'act' set rerender where do fireEvent in 'act' block
act( () => {
  // ez csak egyszer fut a test sorozat leg elején
  common.pushAxiosResult({data:{sid:'123'}});
  const root = createRoot(el);
  component = root.render(<Login />);
  const root2 = createRoot(el2);
  component2 = root2.render(<Login />);
  common.sessionStart();
});  

describe('Test Login component', () => {

  beforeEach(() => {
    // ez lefut minden egyes test előtt
  });

  test('render login ', () => {
    let btn = el.querySelector('#doLogin');
    expect(btn).toBeTruthy();
    expect($('#waiting').is(':visible')).toBeFalsy();
  });

  test('user click doLogin, nick/email empty', () => {
    act( () => {
      let w1 = el.querySelector('#email');
      w1?fireEvent.change(w1, {target: {value: ''}}): noop;
      let w2 = el.querySelector('#password');
      w2?fireEvent.change(w2, {target: {value: '123'}}): noop;
      let btn = el.querySelector('#doLogin');
      expect(btn).toBeTruthy();
      btn?fireEvent.click(btn): console.log('btn not found'); 
    });
    expect(document.body.innerHTML.indexOf('NICK_OR_EMAIL_REQUIRED')).toBeGreaterThan(0);
  })

  test('user click doLogin, password empty', () =>{
    act( () => {
      let w1 = el.querySelector('#email');
      w1?fireEvent.change(w1, {target: {value: '23'}}): noop;
      let w2 = el.querySelector('#password');
      w2?fireEvent.change(w2, {target: {value: ''}}): noop;
      let btn = el.querySelector('#doLogin');
      btn?fireEvent.click(btn): console.log('btn not found'); 
    });
    expect(document.body.innerHTML.indexOf('PASSWORD_REQUIRED')).toBeGreaterThan(0);
  })

  test('user click doLogin, wrong login', () =>{
    act( () => {
      let w1 = el.querySelector('#email');
      w1?fireEvent.change(w1, {target: {value: '23'}}): noop;
      let w2 = el.querySelector('#password');
      w2?fireEvent.change(w2, {target: {value: '23'}}): noop;
      common.pushAxiosResult({data:{ok:false, errorMsg:'testMsg'}});
      let btn = el.querySelector('#doLogin');
      expect(btn).toBeTruthy();
      btn?fireEvent.click(btn): console.log('btn not found'); 
    });
    expect(document.body.innerHTML.indexOf('testMsg')).toBeGreaterThan(0);
  })


  test('user click doLogin, correct', () =>{
    axiosLog.splice(0,99);
    act( () => {
      let w1 = el.querySelector('#email');
      w1?fireEvent.change(w1, {target: {value: '23'}}): noop;
      let w2 = el.querySelector('#password');
      w2?fireEvent.change(w2, {target: {value: '23'}}): noop;
      common.pushAxiosResult({data:{ok:true, id:'123', nick:'user1', avatar:'avatar1', groups:'registered, admin'}});
      let btn = el.querySelector('#doLogin');
      expect(btn).toBeTruthy();
      btn?fireEvent.click(btn): console.log('btn not found'); 
    });
    expect(common.getCookie('logedId')).toEqual('123');
    expect(common.getCookie('logedNick')).toEqual('user1');
    expect(common.getCookie('logedAvatar')).toEqual('avatar1');
    expect(common.getCookie('logedGroups')).toEqual('registered, admin');
    expect(axiosLog[0].url).toEqual('api/dologin.php');
    expect(common.getCookie('href')).toEqual('./home');
  })

 
  test('user click forget password, empty nick/email', () =>{
    act( () => {
      let w1 = el.querySelector('#email');
      w1?fireEvent.change(w1, {target: {value: ''}}): noop;
      let btn = el.querySelector('#forgetPassword');
      expect(btn).toBeTruthy();
      btn?fireEvent.click(btn): console.log('btn not found'); 
    });
    expect(document.body.innerHTML.indexOf('NICK_OR_EMAIL_REQUIRED')).toBeGreaterThan(0);
  })
  
  test('user click forget password, wrong data', () =>{
    axiosLog.splice(0,99);
    act( () => {
      let w1 = el.querySelector('#email');
      w1?fireEvent.change(w1, {target: {value: '23'}}): noop;
      common.pushAxiosResult({data:{errorMsg:'NOT_FOUND'}});
      let btn = el.querySelector('#forgetPassword');
      expect(btn).toBeTruthy();
      btn?fireEvent.click(btn): console.log('btn not found'); 
    });
    expect(document.body.innerHTML.indexOf('NOT_FOUND')).toBeGreaterThan(0);
    expect(axiosLog[0].url).toEqual('api/forgetpassword.php');
  })
  

  test('user click forget password, correct data', () =>{
    axiosLog.splice(0,99);
    act( () => {
      let w1 = el.querySelector('#email');
      w1?fireEvent.change(w1, {target: {value: '23'}}): noop;
      common.pushAxiosResult({data:{errorMsg:''}});
      let btn = el.querySelector('#forgetPassword');
      expect(btn).toBeTruthy();
      btn?fireEvent.click(btn): console.log('btn not found'); 
    });
    expect(document.body.innerHTML.indexOf('EMAIL_SENDED')).toBeGreaterThan(0);
    expect(axiosLog[0].url).toEqual('api/forgetpassword.php');
  })
  
  test('user click resend email, empty nick/email', () =>{
    act( () => {
      let w1 = el.querySelector('#email');
      w1?fireEvent.change(w1, {target: {value: ''}}): noop;
      let btn = el.querySelector('#resendEmail');
      expect(btn).toBeTruthy();
      btn?fireEvent.click(btn): console.log('btn not found'); 
    });
    expect(document.body.innerHTML.indexOf('NICK_OR_EMAIL_REQUIRED')).toBeGreaterThan(0);
  })
  
  test('user click resend email, wrong data', () =>{
    axiosLog.splice(0,99);
    act( () => {
      let w1 = el.querySelector('#email');
      w1?fireEvent.change(w1, {target: {value: '23'}}): noop;
      let btn = el.querySelector('#resendEmail');
      expect(btn).toBeTruthy();
      common.pushAxiosResult({data:{errorMsg:'NOT_FOUND'}})
      btn?fireEvent.click(btn): console.log('btn not found'); 
    });
    expect(document.body.innerHTML.indexOf('NOT_FOUND')).toBeGreaterThan(0);
    expect(axiosLog[0].url).toEqual('api/sendactivatoremail.php');
  })
  
  test('user click resend email, correct data', () =>{
    axiosLog.splice(0,99);
    act( () => {
      let w1 = el.querySelector('#email');
      w1?fireEvent.change(w1, {target: {value: '23'}}): noop;
      let btn = el.querySelector('#resendEmail');
      expect(btn).toBeTruthy();
      common.pushAxiosResult({data:{errorMsg:''}})
      btn?fireEvent.click(btn): console.log('btn not found'); 
    });
    expect(document.body.innerHTML.indexOf('EMAIL_SENDED')).toBeGreaterThan(0);
  })

  test("user click 'regist' show '#register' DOM element ", () => {    
    act( () => {
      let btn = el.querySelector('#doRegister');
      expect(btn).toBeTruthy();
      btn?fireEvent.click(btn): console.log('btn not found'); 
    });
    expect(el.querySelector('#Register')).toBeTruthy();
  })

  test('user click doLogin, two_factor ', () =>{
    act( () => {
      let w1 = el2.querySelector('#email');
      w1?fireEvent.change(w1, {target: {value: '23'}}): noop;
      let w2 = el2.querySelector('#password');
      w2?fireEvent.change(w2, {target: {value: '23'}}): noop;
      common.pushAxiosResult({data:{loged:false, errorMsg:'TWO_FACTOR'}});
      let btn = el2.querySelector('#doLogin');
      expect(btn).toBeTruthy();
      btn?fireEvent.click(btn): console.log('btn not found'); 
    });
    expect(el2.querySelector('#twoFactor')).toBeTruthy();
  })  


});