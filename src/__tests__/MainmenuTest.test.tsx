// import React from 'react';
// import ReactDOM from 'react-dom';
import { createRoot } from 'react-dom/client';
import {cleanup, fireEvent, render, act } from '@testing-library/react';
import $, { noop } from 'jquery';
import Mainmenu from '../common/Mainmenu';
import { common, axiosLog } from '../common/common';

// DOM container for component
var el = document.createElement("div");
var component:any = {};

// 'act' set rerender where do fireEvent in 'act' block
act( () => {
  // ez csak egyszer fut a test sorozat legelején
  common.pushAxiosResult({data:{sid:'123'}});
  common.pushAxiosResult({data:{logedId:''}});
  const root = createRoot(el);
  component = root.render(<Mainmenu />);
  common.sessionStart();
});  

describe('Test Login component', () => {

  beforeEach(() => {
    // ez lefut minden egyes test előtt
  });

  test('render Mainmenu not loged', () => {
    common.setCookie('logedId','',1);
    let div = el.querySelector('#Mainmenu');
    expect(div).toBeTruthy();
    let div2 = el.querySelector('#dropdown1');
    expect(div2).toBeFalsy();
    expect($('#waiting').is(':visible')).toBeFalsy();
  });

  test('render Mainmenu  loged', () => {
    common.setCookie('logedId','1234',1);
    common.setCookie('logedNick','user1',1);
    common.setCookie('logedAvatar','avatar1',1);
    let div = el.querySelector('#Mainmenu');
    expect(div).toBeTruthy();
    let div2 = el.querySelector('#dropdown1');
    expect(div2).toBeFalsy();
    expect($('#waiting').is(':visible')).toBeFalsy();
  });
  
});