// import React from 'react';
// import ReactDOM from 'react-dom';
import { createRoot } from 'react-dom/client';
import { fireEvent, act } from '@testing-library/react';
import Policy from '../pages/Policy2';
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

describe('Test Policy2 component', () => {

  beforeEach(() => {
    // ez lefut minden egyes test előtt
  });

  test('render Policy2 ', () => {
    let btn = el.querySelector('#Policy2');
    expect(btn).toBeTruthy();
  });

  test('user click Policy3', () => {
    act( () => {
        let link = el.querySelector('#policy3Link');
        expect(link).toBeTruthy();
        link?fireEvent.click(link): console.log('btn not found'); 
    });
    expect(el.innerHTML.indexOf('id="Policy3"')).toBeGreaterThan(0);
  })

});