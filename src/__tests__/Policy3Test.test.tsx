// import React from 'react';
// import ReactDOM from 'react-dom';
import { createRoot } from 'react-dom/client';
import { fireEvent, act } from '@testing-library/react';
import Policy from '../pages/Policy3';
import { common, axiosLog } from '../common/common';

// DOM container for component
const el = document.createElement("div");
var component:any = {};
var link3:any = null;

// 'act' set rerender where do fireEvent in 'act' block
act( () => {
  // ez csak egyszer fut a test sorozat leg elején
  common.pushAxiosResult({data:{sid:'123'}});
  const root = createRoot(el);
  component = root.render(<Policy />);
  common.sessionStart();
});  

describe('Test Policy3 component', () => {

  beforeEach(() => {
    // ez lefut minden egyes test előtt
  });

  test('render Policy3 ', () => {
    let btn = el.querySelector('#Policy3');
    expect(btn).toBeTruthy();
  });

  test('user click Policy2', () => {
    act( () => {
        let link = el.querySelector('#policy2Link');
        expect(link).toBeTruthy();
        link?fireEvent.click(link): console.log('btn not found'); 
    });
    expect(el.innerHTML.indexOf('id="Policy2"')).toBeGreaterThan(0);
  })

});