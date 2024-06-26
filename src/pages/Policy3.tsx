import React, { useEffect, useState } from 'react';
import { common } from "../common/common"; 
import Policy2 from './Policy2';

const Policy3 = () => {
	const [SITENAME, setSITENAME] = useState("");
	const [SIGNO, setSIGNO] = useState("");
	const [task, setTask] = useState("policy3")

   	// onLoad event handler                                                       
	   useEffect(() => {
		setSITENAME(common.data('SITENAME'));
		setSIGNO(common.data('SIGNO'));
		common.scrollTo(0,0);
	});	


	return (
		<div id="Policy3" className="row">
			{(task === 'policy2') &&
				<Policy2 />
			}		
			{(task === 'policy3') &&
				<div className="row" id="Policy3">
					<div className="col-12">
						<h1>Adatkezelési nyilvántartás</h1>    

						<p>A { SITENAME } szoftverben alkalmazott adatkezelések</p>

						<h2>Adatkezelés célja: Új felhasználó regisztrálása</h2>
						<ul>
							<li>Adat forrása: képernyőn beírt adatok</li>
							<li>Kezelt adatok: bejelentkezési név, valódi név, email, jelszó, avatar képfájl</li>
							<li>Adatkezelés: Beírt adatok tárolása adatbázisba a users rekordba és az
								avatar képfájl tárolása az images/user könyvtrába. A jelszót hash256 -formában tároljuk.</li>
							<li>Jogalap: felhasználó a képernyőn elfogadta az adatkezelési leírásban foglaltakat</li>
							<li>Megőrzési idő: a rendszer üzemeltetés végéig, illetve a felhasználó törlési kéréséig</li>
							<li>Érintett rendszer(ek): ez a rendszer</li>
							<li>Továbbítás: email ellenörző email -t küld a felhasználó által megadott email címre</li>
							<li>Adatkezelésileírás idevonatkozó fejezete:5.1</li>
							<li>Felhasználó hozzájárulás szükséges: igem</li>
						</ul>

						<h2>Adatkezelés célja: Bejelentkezés bejelentkezési név és jelszó segítségével</h2>
						<ul>
							<li>Adat forrása: képernyőn beírt adatok</li>
							<li>Kezelt adatok: bejelentkezési név és jelszó</li>
							<li>Adatkezelés: users rekord olvasása az adatbázisból, bejelentkezett user adatainak 
								tárolása PHP sessionba és cookie -ba.
							</li>
							<li>Jogalap: felhasználói kezdeményezés</li>
							<li>Megőrzési idő: kijelentkezésig</li>
							<li>Érintett rendszer(ek): ez a rendszer</li>
							<li>Továbbítás: nincs</li>
							<li>Adatkezelésileírás idevonatkozó fejezete:5.2</li>
							<li>Felhasználó hozzájárulás szükséges:képernyőn megjelenő tájékoztatás szerint igen</li>
						</ul>

						<h2>Adatkezelés célja: Bejelentkezés Facebook-al</h2>
						<ul>
							<li>Adat forrása: Facebook</li>
							<li>Kezelt adatok: bejelentkezési név</li>
							<li>Adatkezelés: Ha még nincs users rekord akkor létrehozzuk,
								users rekord olvasása az adatbázisból, bejelentkezett user adatainak 
								tárolása PHP sessionba.
							</li>
							<li>Jogalap: felhasználói kezdeményezés</li>
							<li>Megőrzési idő: kijelentkezésig</li>
							<li>Érintett rendszer(ek): facebook és ez a rendszer</li>
							<li>Továbbítás: bejelentkezési kérelem küldése a facebook -felé</li>
							<li>Adatkezelésileírás idevonatkozó fejezete:5.3</li>
							<li>Felhasználó hozzájárulás szükséges:igen</li>
						</ul>

						<h2>Adatkezelés célja: Bejelentkezés Google -al</h2>
						<ul>
							<li>Adat forrása: Google</li>
							<li>Kezelt adatok: bejelentkezési név</li>
							<li>Adatkezelés: Ha még nincs users rekord akkor létrehozzuk,
								users rekord olvasása az adatbázisból, bejelentkezett user adatainak 
								tárolása PHP sessionba.
							</li>
							<li>Jogalap: felhasználói kezdeményezés</li>
							<li>Megőrzési idő: kijelentkezésig</li>
							<li>Érintett rendszer(ek): google és ez a rendszer</li>
							<li>Továbbítás: bejelentkezési kérelem küldése a google -felé</li>
							<li>Adatkezelésileírás idevonatkozó fejezete:5.3</li>
							<li>Felhasználó hozzájárulás szükséges:igen</li>
						</ul>

						<h2>Adatkezelés célja: Kijelentkezés</h2>
						<ul>
							<li>Adat forrása: nincs</li>
							<li>Kezelt adatok: A bejelentkezett user adatai a PHP sessionban</li>
							<li>Adatkezelés: sessionból adatok törlése</li>
							<li>Jogalap: felhasználói kezdeményezés</li>
							<li>Megőrzési idő: nincs</li>
							<li>Érintett rendszer(ek): ez a rendszer</li>
							<li>Továbbítás:nincs</li>
							<li>Adatkezelésileírás idevonatkozó fejezete:</li>
							<li>Felhasználó hozzájárulás szükséges:nem</li>
						</ul>

						<h2>Adatkezelés célja: felhasználói profil adatok megjelenítése</h2>
						<ul>
							<li>Adat forrása: adatbázis users és groups rekordok</li>
							<li>Kezelt adatok: bejelentkezési név, valódi név, email, kapcsolodó group -ok,
								email ellenörzött?, fiók engedélyezett?, jelszó
							</li>
							<li>Adatkezelés: adatok megjelenítése a képernyőn (kivéve a jelszót)</li>
							<li>Jogalap: felhasználói kezdeményezés</li>
							<li>Megőrzési idő: a képernyő bezárásáig</li>
							<li>Érintett rendszer(ek): ez a rendszer</li>
							<li>Továbbítás:nincs</li>
							<li>Adatkezelésileírás idevonatkozó fejezete:5.6</li>
							<li>Felhasználó hozzájárulás szükséges:nem</li>
						</ul>

						<h2>Adatkezelés célja: Felhasználói profil adatok módosítása</h2>
						<ul>
							<li>Adat forrása:a képernyőn beírt adatok</li>
							<li>Kezelt adatok:bejelentkezési név, valódi név, email, kapcsolodó group -ok,
								email ellenörzött?, fiók engedélyezett?, jelszó</li>
							<li>Adatkezelés:adatok tárolása adatbázisba és az images/users könyvtárba. 
								A jelszót hash256 formában tároljuk.</li>
							<li>Jogalap: felhasználói kezdeményezés</li>
							<li>Megőrzési idő: a rendszer működési ideje illetve felhasználói törlés kérés</li>
							<li>Érintett rendszer(ek): ez a rendszer</li>
							<li>Továbbítás:nincs</li>
							<li>Adatkezelésileírás idevonatkozó fejezete:5.6</li>
							<li>Felhasználó hozzájárulás szükséges:nem</li>
						</ul>

						<h2>Adatkezelés célja: Felhasználói adatok törlése</h2>
						<ul>
							<li>Adat forrása:felhasználói kezdeményezés</li>
							<li>Kezelt adatok:bejelentkezési név, valódi név, email, kapcsolodó group -ok,
								email ellenörzött?, fiók engedélyezett?, jelszó</li>
							<li>Adatkezelés:bejelentkezési név, valódi név, email anonimizálása, 
								kapcsolodó group -ok törlése,
								email ellenörzött?, fiók engedélyezett = false, jelszó randomizálása</li>
							<li>Jogalap: felhasználói kezdeményezés</li>
							<li>Megőrzési idő:a rendszer működési ideje</li>
							<li>Érintett rendszer(ek):ez a rendszer</li>
							<li>Továbbítás:nincs</li>
							<li>Adatkezelésileírás idevonatkozó fejezete:5.8</li>
							<li>Felhasználó hozzájárulás szükséges:igen</li>
						</ul>

						<h2>Adatkezelés célja: E-mail ellenörzés</h2>
						<ul>
							<li>Adat forrása:az email ellenörző (aktiváló) emailben lévő linkben lévő "code"</li>
							<li>Kezelt adatok: email ellenörzött?</li>
							<li>Adatkezelés:  email ellenörzött = true beállítása az adatbázisban</li>
							<li>Jogalap: felhasználói kezdeményezés</li>
							<li>Megőrzési idő:a rendszer működési ideje</li>
							<li>Érintett rendszer(ek):ez a rendszer</li>
							<li>Továbbítás:nincs</li>
							<li>Adatkezelésileírás idevonatkozó fejezete:5.1</li>
							<li>Felhasználó hozzájárulás szükséges:nem</li>
						</ul>

						<h2>Adatkezelés célja: Elfelejtett jelszó</h2>
						<ul>
							<li>Adat forrása:képernyőn beírt bejelentkezési név</li>
							<li>Kezelt adatok: user rekord az adatbázisban</li>
							<li>Adatkezelés:  email küldése a user rekordban szereplő email címre.
								az ebben szereplő link -el a user a profil oldalát tudja elérni, ahol új jelszót adhat meg.
							</li>
							<li>Jogalap: felhasználói kezdeményezés</li>
							<li>Megőrzési idő:a rendszer működési ideje</li>
							<li>Érintett rendszer(ek):ez a rendszer</li>
							<li>Továbbítás:email küldés a felhasználó által a regisztráláskor megadott címre</li>
							<li>Adatkezelésileírás idevonatkozó fejezete:5.2</li>
							<li>Felhasználó hozzájárulás szükséges:nem</li>
						</ul>

						<p><strong>{ SIGNO }</strong></p>

						<h2>Kapcsolódó dokumentumok:</h2>
						<p><var id="policy2Link" className="link" onClick={() => { setTask('policy2') }}>Adatkezelési szabályzat</var></p>
						<p><a href="./policy">Adatkezelési leírás</a></p>
					</div>
				</div>	
			}	
		</div>	
	)
}
export default Policy3;
