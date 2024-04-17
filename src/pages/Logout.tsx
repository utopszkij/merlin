import React, { useEffect } from 'react';
import { common } from "../common/common";
import $ from 'jquery'; 
  
const Logout = () => {
  	
  //variables whose value can change during runtime
  const sid = common.getCookie('sid');	

  // onLoad event handler
	useEffect(() => {
		$('#waiting').hide();
		common.setCookie('logedId','',10);
		common.setCookie('logedNick','',10);
		common.setCookie('logedAvatar','',10);
		common.setCookie('logedGroups','',10);
		common.callApi('api/dologout.php',{'sid': sid}, (res:any) => { 
			common.gotoNewPage('./home'); 
		});
	});	

	return (
        <></>
    )
}
export default Logout;
