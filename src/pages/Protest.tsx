import React from 'react';
import { common } from "../common/common"; 

const Protest = () => {
	const lng = common.lng;
	return (
		<div id="Protest" className="row">
			<h2>{ lng('PROTEST') }</h2>
			<div className="row message">
					<form action="./api/contact.php" method="post">
						<input type="hidden" name="subject" value="protest" />
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
export default Protest
