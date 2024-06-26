import React, { useEffect, useState } from 'react';
import { common } from "../common/common"; 
import Policy3 from './Policy3';

const Policy2 = () => {

	const [SITENAME, setSITENAME] = useState("");
	const [ADATKEZELO, setADATKEZELO] = useState("");
	const [SIGNO, setSIGNO] = useState("");
	const [task, setTask] = useState("policy2")

   	// onLoad event handler                                                       
	   useEffect(() => {
			setSITENAME(common.data('SITENAME'));
			setADATKEZELO(common.data('ADATKEZELO'));
			setSIGNO(common.data('SIGNO'));
			common.scrollTo(0,0);
	});	


	return (
		<div id="Policy2" className="row">
			{(task === 'policy3') &&
				<Policy3 />
			}		
			{(task === 'policy2') &&
			<div>
 
				<h1>ADATKEZELÉSI SZABÁLYZAT</h1>
				<p >
				<br/>
				</p>
				<h2>1. A Szabályzat célja</h2>
				<p><br/>
				A { document.location.hostname } szoftver müködtetése során 
				</p>
				<p><strong dangerouslySetInnerHTML={{__html: ADATKEZELO}}></strong> 
				&nbsp;ADATKEZELŐ  működésének szabályozása. 
				</p>
				<p><br/>
				
				</p>
				<p>jelen
				Szabályzattal az aláírásának napjától alkalmazandó, az
				Európai Parlament és Tanács (EU) 2016/679. számú Rendeletnek (a
				továbbiakban: GDPR), illetve a vonatkozó magyar jogszabályoknak,
				különös tekintettel az információs önrendelkezési jogról és
				az információszabadságról szóló 2011. évi CXII. törvénynek
				(a továbbiakban: Infotv.) való megfelelés érdekében az alábbi
				adatkezelési szabályzatot fogadja el az alulírott helyen és
				időben.</p>
				<p>A jelen Szabályzat célja, hogy rögzítse az Adatkezelő által
				alkalmazott adatvédelmi és adatkezelési elveket és szabályokat,
				valamint az Adatkezelő adatvédelmi és adatkezelési politikáját.</p>
				<p>Jelen
				Szabályzat az Adatkezelő valamennyi munkavállalójára, illetve
				vele megbízási jogviszonyban lévő személyekre nézve kötelező.</p>
				<p>A
				szabályzat kiadásának célja továbbá, hogy megismerésével és
				betartásával a adatkezelő munkavállalói képesek legyenek a
				magánszemélyek adatainak kezelését jogszerűen végezni.</p>
				<p><br/>
				
				</p>
				<h2>2. Adatkezelő adatai</h2>
				<p><strong dangerouslySetInnerHTML={{__html: ADATKEZELO}}></strong></p>
				<p><br/>
				
				</p>
				<p>Ez a szabályzat a természetes személyeknek a személyes adatok kezelése
				tekintetében történő védelmére és a személyes adatok szabad
				áramlására vonatkozó szabályokat állapít meg. A szabályzatban
				foglaltakat kell alkalmazni a konkrét adatkezelési tevékenységek
				során, valamint az adatkezelést szabályozó utasítások és
				tájékoztatások kiadásakor.</p>
				<p><br/>
				
				</p>
				<p>Adatvédelmi tisztviselő alkalmazási (kijelölési) kötelezettség kiterjed
				minden közhatalmi szervre vagy egyéb, közfeladatot ellátó
				szervre (függetlenül attól, hogy milyen adatokat dolgoz fel),
				valamint egyéb olyan adatkezelőkre, amelyek fő tevékenysége az
				egyének szisztematikus, nagymértékű megfigyelése, vagy amelyek a
				személyes adatok különleges kategóriáit nagy számban kezelik.</p>
				<p><br/>
				
				</p>
				<p>Figyelembe véve az Adatkezelő jelen szoftverrel kapcsolatos tevékenységét,
				a kezelt adatokat - adatvédelmi tisztviselő kinevezése nem
				kötelező a GDPR vonatkozó rendelkezései alapján.</p>
				<p><br/>
				
				</p>
				<p><br/>
				
				</p>
				<h2>3. Lényeges fogalmak, meghatározások</h2>
				<p><br/>
				
				</p>
				<p>a
				GDPR (General Data Protection Regulation) az Európai Unió új
				Adatvédelmi Rendelete</p>
				<p><br/>
				
				</p>
				<p><b>adatkezelő:</b>
				az a természetes vagy jogi személy, közhatalmi szerv, ügynökség
				vagy bármely egyéb szerv, amely a személyes adatok kezelésének
				céljait és eszközeit önállóan vagy másokkal együtt
				meghatározza; ha az adatkezelés céljait és eszközeit az uniós
				vagy a tagállami jog határozza meg, az adatkezelőt vagy az
				adatkezelő kijelölésére vonatkozó különös szempontokat az
				uniós vagy a tagállami jog is meghatározhatja;</p>
				<p><br/>
				
				</p>
				<p><b>adatkezelés</b>:
				a személyes adatokon vagy adatállományokon automatizált vagy nem
				automatizált módon végzett bármely művelet vagy műveletek
				összessége, így a gyűjtés, rögzítés, rendszerezés, tagolás,
				tárolás, átalakítás vagy megváltoztatás, lekérdezés,
				betekintés, felhasználás, közlés, továbbítás, terjesztés
				vagy egyéb módon történő hozzáférhetővé tétel útján,
				összehangolás vagy összekapcsolás, korlátozás, törlés,
				illetve megsemmisítés;</p>
				<p><br/>
				
				</p>
				<p><b>adatfeldolgozó</b>:
				az a természetes vagy jogi személy, közhatalmi szerv, ügynökség
				vagy bármely egyéb szerv, amely az adatkezelő nevében személyes
				adatokat kezel;</p>
				<p><br/>
				
				</p>
				<p><b>személyes adat</b>: azonosított vagy azonosítható természetes személyre
				(érintett) vonatkozó bármely információ; azonosítható az a
				természetes személy, aki közvetlen vagy közvetett módon,
				különösen valamely azonosító, például név, szám,
				helymeghatározó adat, online azonosító vagy a természetes
				személy testi, fiziológiai, genetikai, szellemi, gazdasági,
				kulturális vagy szociális azonosságára vonatkozó egy vagy több
				tényező alapján azonosítható;</p>
				<p><br/>
				
				</p>
				<p><b>harmadik fél</b>: az a természetes vagy jogi személy, közhatalmi szerv,
				ügynökség vagy bármely egyéb szerv, amely nem azonos az
				érintettel, az adatkezelővel, az adatfeldolgozóval vagy azokkal a
				személyekkel, akik az adatkezelő vagy adatfeldolgozó közvetlen
				irányítása alatt a személyes adatok kezelésére felhatalmazást
				kaptak;</p>
				<p><br/>
				
				</p>
				<p><b>az adatkezelés korlátozása</b>: a tárolt személyes adatok
				megjelölése jövőbeli kezelésük korlátozása céljából;</p>
				<p><br/>
				
				</p>
				<p><b>álnevesítés</b>:
				a személyes adatok olyan módon történő kezelése, amelynek
				következtében további információk felhasználása nélkül többé
				már nem állapítható meg, hogy a személyes adat mely konkrét
				természetes személyre vonatkozik, feltéve hogy az ilyen további
				információt külön tárolják, és technikai és szervezési
				intézkedések megtételével biztosított, hogy azonosított vagy
				azonosítható természetes személyekhez ezt a személyes adatot nem
				lehet kapcsolni;  
				</p>
				<p><br/>
				
				</p>
				<p><b>nyilvántartási rendszer</b>: a személyes adatok bármely módon – centralizált,
				decentralizált vagy funkcionális vagy földrajzi szempontok szerint
				– tagolt állománya, amely meghatározott ismérvek alapján
				hozzáférhető;</p>
				<p><br/>
				
				</p>
				<p><b>adatvédelmi incidens</b>: a biztonság olyan sérülése, amely a továbbított,
				tárolt vagy más módon kezelt személyes adatok véletlen vagy
				jogellenes megsemmisítését, elvesztését, megváltoztatását,
				jogosulatlan közlését vagy az azokhoz való jogosulatlan
				hozzáférést eredményezi;</p>
				<p><br/>
				
				</p>
				<p><br/>
				
				</p>
				<h2>4. Az adatkezelés elvei (GDPR 5. cikke alapján)</h2>
				<p><br/>
				
				</p>
				<p>	</p>
				<p>A személyes adatok kezelését jogszerűen és tisztességesen,
				valamint az érintett számára átlátható módon kell végezni.</p>
				<p><br/>
				
				</p>
				<p>A személyes adatok gyűjtése csak meghatározott, egyértelmű és
				jogszerű célból történhet.</p>
				<p><br/>
				
				</p>
				<p>A személyes adatok kezelésének célja megfelelő és releváns
				legyen, és csak a szükséges mértékű lehet.</p>
				<p><br/>
				
				</p>
				<p>A személyes adatoknak pontosnak és naprakésznek kell lenniük. A
				pontatlan személyes adatokat haladéktalanul törölni kell.</p>
				<p><br/>
				
				</p>
				<p>A személyes adatok tárolásának olyan formában kell történnie,
				hogy az érintettek azonosítását csak szükséges ideig tegye
				lehetővé. A személyes adatok ennél hosszabb ideig történő
				tárolására csak akkor kerülhet sor, ha a tárolás közérdekű
				archiválás céljából, tudományos és történelmi kutatási
				célból vagy statisztikai célból történik.</p>
				<p><br/>
				
				</p>
				<p>A személyes adatok kezelését oly módon kell végezni, hogy
				megfelelő technikai vagy szervezési intézkedések alkalmazásával
				biztosítva legyen a személyes adatok megfelelő biztonsága, az
				adatok jogosulatlan vagy jogellenes kezelésével, véletlen
				elvesztésével, megsemmisítésével vagy károsodásával szembeni
				védelmet is ideértve.</p>
				<p><br/>
				
				</p>
				<p>Az adatvédelem elveit minden azonosított vagy azonosítható
				természetes személyre vonatkozó információ esetében alkalmazni
				kell.</p>
				<p><br/>
				
				</p>
				<p>A adatkezelő adatkezelést végző alkalmazottja kártérítési,
				szabálysértési és büntetőjogi felelősséggel tartozik a
				személyes adatok jogszerű kezeléséért. Amennyiben az alkalmazott
				tudomást szerez arról, hogy az általa kezelt személyes adat
				hibás, hiányos, vagy időszerűtlen, köteles azt helyesbíteni,
				vagy helyesbítését az adat rögzítéséért felelős munkatársnál
				kezdeményezni.</p>
				<p><br/>
				
				</p>
				<p><br/>
				
				</p>
				<p>	</p>
				<h2>5. Adatkezelések leírása</h2>
				<p><br/>
				
				</p>
				<p>Az adatkezelő szolgáltatásait használó természetes személyek,
				vagy jogi személyek képviselőinek személyes adatai tekintetében
				végzett adatkezelések leírása a adatkezelő weboldalának
				adatkezelési tájékoztatóban megtalálható.</p>
				<p><br/>
				
				</p>
				<p>Figyelemmel az Info tv. 5. § (5) bekezdésére, a adatkezelő az adatkezelés
				megkezdésétől legalább háromévente felülvizsgálja, hogy az
				általa, illetve a megbízásából vagy rendelkezése alapján
				eljáró adatfeldolgozó által kezelt személyes adat kezelése az
				adatkezelés céljának megvalósulásához szükséges-e.</p>
				<p><br/>
				
				</p>
				<p><br/>
				
				</p>
				<h2>6. A adatkezelő feladatai a megfelelő adatvédelem érdekében </h2>
				<p>Az adatvédelmi tudatosság. Biztosítani kell a szakmai felkészültséget
				a jogszabályoknak való megfeleléshez. 
				</p>
				<p><br/>
				
				</p>
				<p>Át kell tekinteni az adatkezelés célját, szempontrendszerét, a
				személyes adatkezelés koncepcióját. Az adatvédelmi és
				adatkezelési szabályzattal összhangban kell biztosítani jogszerű
				adatkezelést. 
				</p>
				<p><br/>
				
				</p>
				<p>Az érintett személynek nyújtott tájékoztatás tömör, könnyen
				hozzáférhető és könnyen érthető legyen, ezért azt világos és
				közérthető nyelven kell megfogalmazni és megjeleníteni.</p>
				<p><br/>
				
				</p>
				<p>Az átlátható adatkezelés követelménye, hogy az érintett személy
				tájékoztatást kapjon az adatkezelés tényéről és céljairól.
				A tájékoztatást az adatkezelés megkezdése előtt kell megadni és
				a tájékoztatáshoz való jog az adatkezelés során annak
				megszűnéséig megilleti az érintettet.</p>
				<p><br/>
				
				</p>
				<p>Az adatkezelő indokolatlan késedelem nélkül, de legkésőbb a
				kérelem beérkezésétől számított egy hónapon belül
				tájékoztatja az érintettet. Szükség esetén, figyelembe véve a
				kérelem összetettségét és a kérelmek számát, ez a határidő
				további két hónappal meghosszabbítható. A tájékoztatási
				kötelezettség biztosítható egy olyan biztonságos online rendszer
				üzemeltetésével, amelyen keresztül az érintett könnyen és
				gyorsan hozzáférhet a szükséges információhoz.</p>
				<p><br/>
				
				</p>
				<p> A személyes adat jogellenes kezelése vagy feldolgozása esetén
				bejelentési kötelezettség keletkezik a felügyelő hatóság felé.
				Az adatkezelőnek indokolatlan késedelem nélkül – ha lehetséges,
				legkésőbb 72 órával azután, hogy az adatvédelmi incidens a
				tudomására jutott, –  meg kell tenni a bejelentést a felügyeleti
				hatóságnak, kivéve akkor, ha az adatvédelmi incidens
				valószínűsíthetően nem jár kockázattal a természetes személy
				jogait tekintve. 
				</p>
				<p><br/>
				
				</p>
				<p><br/>
				
				</p>
				<h2>7. Adatbiztonság</h2>
				<p><br/>
				
				</p>
				<p>Az adatokat megfelelő intézkedésekkel védeni kell különösen a
				jogosulatlan hozzáférés, megváltoztatás, továbbítás,
				nyilvánosságra hozatal, törlés vagy megsemmisítés, valamint a
				véletlen megsemmisülés és sérülés, továbbá az alkalmazott
				technika megváltozásából fakadó hozzáférhetetlenné válás
				ellen.</p>
				<p><br/>
				
				</p>
				<p>A nyilvántartásokban elektronikusan kezelt adatállományok védelme
				érdekében megfelelő technikai megoldással biztosítani kell, hogy
				a nyilvántartásokban tárolt adatok közvetlenül ne legyenek
				összekapcsolhatók és az érintetthez rendelhetők. 
				</p>
				<p><br/>
				
				</p>
				<p>Az adatbiztonság megtervezésekor és alkalmazásakor tekintettel kell
				lenni a technika mindenkori fejlettségére. Több lehetséges
				adatkezelési megoldás közül azt kell választani, amely a
				személyes adatok magasabb szintű védelmét biztosítja, kivéve,
				ha az aránytalan nehézséget jelentene az adatkezelőnek.</p>
				<p><br/>
				
				</p>
				<p><br/>
				
				</p>
				<p><br/>
				
				</p>
				<h2>8. Adatvédelmi incidens</h2>
				<p><br/>
				
				</p>
				<p>Az adatvédelmi incidens a biztonság olyan sérülése, amely a
				továbbított, tárolt vagy más módon kezelt személyes adatok
				véletlen vagy jogellenes megsemmisítését, elvesztését,
				megváltoztatását, jogosulatlan közlését vagy az azokhoz való
				jogosulatlan hozzáférést eredményezi.</p>
				<p><br/>
				
				</p>
				<p>Az adatvédelmi incidens megfelelő és kellő idejű intézkedés
				hiányában fizikai, vagyoni vagy nem vagyoni károkat okozhat a
				természetes személyeknek, többek között a személyes adataik
				feletti rendelkezés elvesztését vagy a jogaik korlátozását, a
				hátrányos megkülönböztetést, a személyazonosság-lopást vagy
				a személyazonossággal való visszaélést.</p>
				<p><br/>
				
				</p>
				<p>Az adatvédelmi incidenst indokolatlan késedelem nélkül, legkésőbb
				72 órán belül be kell jelenteni az illetékes felügyeleti
				hatóságnál, kivéve, ha az elszámoltathatóság elvével
				összhangban bizonyítani lehet, hogy az adatvédelmi incidens
				valószínűleg nem jár kockázattal a természetes személyek
				jogaira és szabadságaira nézve. 
				</p>
				<p><br/>
				
				</p>
				<p>Az érintett személyt késedelem nélkül tájékoztatni kell, ha az
				adatvédelmi incidens valószínűsíthetően magas kockázattal jár
				a természetes személy jogaira és szabadságára nézve, annak
				érdekében, hogy megtehesse a szükséges óvintézkedéseket.</p>
				<p><br/>
				
				</p>
				<p><br/>
				
				</p>
				<h2>9. Ügyviteli célú adatkezelés</h2>
				<p><br/>
				
				</p>
				<p>Az adatkezelő a tevékenységéhez tartozó esetekben személyes
				adatokat is kezelhet.</p>
				<p><br/>
				
				</p>
				<h2>10. Milyen jogai vannak az Érintetteknek?</h2>
				<p><br/>
				
				</p>
				<p>Az Érintett ingyenes tájékoztatást kérhet személyes adatai
				kezelésének részleteiről, valamint jogszabályban meghatározott
				esetekben kérheti azok helyesbítését, törlését, zárolását,
				vagy kezelésének korlátozását, és tiltakozhat az ilyen
				személyes adatok kezelése ellen. A tájékoztatás kérését és a
				jelen pontban szereplő kérelmeket az Érintett a adatkezelő 2.
				pontban szereplő elérhetőségeire tudja címezni.</p>
				<p><br/>
				
				</p>
				<h3>10.1. Hozzáférési jog</h3>
				<p><br/>
				
				</p>
				<p>Az érintett visszajelzést kaphat a adatkezelőtől személyes
				adatainak kezeléséről és ezekhez a személyes adatokhoz, illetve
				kezelésük részleteihez hozzáférhet.</p>
				<p><br/>
				
				</p>
				<h3>10.2. Helyesbítéshez való jog</h3>
				<p><br/>
				
				</p>
				<p>Az Érintett kérésére a adatkezelő indokolatlan késedelem nélkül
				helyesbíti a rá vonatkozó pontatlan személyes adatokat, illetve
				jogosult kérni a hiányos személyes adatok – egyebek mellett
				kiegészítő nyilatkozat útján történő – kiegészítését.</p>
				<p><br/>
				
				</p>
				<h3>10.3. Törléshez való jog</h3>
				<p><br/>
				
				</p>
				<p>Az Érintett kérésére a adatkezelő törli a rá vonatkozó személyes
				adatokat, ha azok kezelésére a adatkezelőnek nincsen szüksége,
				vagy visszavonja hozzájárulását, vagy tiltakozik az adatkezelés
				ellen, vagy kezelésük jogellenes.</p>
				<p><br/>
				
				</p>
				<h3>10.4. Elfeledéshez való jog</h3>
				<p><br/>
				
				</p>
				<p>Az Érintett törlésre irányuló kérelméről – ha igényli – a
				adatkezelő igyekszik értesíteni minden olyan adatkezelőt, aki az
				Érintett esetlegesen nyilvánosságra került adatait megismerte,
				illetve megismerhette.</p>
				<p><br/>
				
				</p>
				<h3>10.5. Adatkezelés korlátozásához való jog</h3>
				<p><br/>
				
				</p>
				<p>Az adatkezelő – az Érintett kérésére – korlátozza az
				adatkezelést, ha a személyes adatok pontossága vitatott, vagy
				jogellenes az adatkezelés, vagy az Érintett tiltakozik az
				adatkezelés ellen, illetve ha a adatkezelőnek nincsen szüksége a
				továbbiakban a megadott személyes adatokra.</p>
				<p><br/>
				
				</p>
				<h3>10.6. Adathordozhatósághoz való jog</h3>
				<p><br/>
				
				</p>
				<p>Az érintett a rá vonatkozó, általa megadott személyes adatokat
				tagolt, széles körben használt, géppel olvasható formátumban
				megkaphatja, illetve ezeket továbbíthatja egy másik a
				adatkezelőnek.</p>
				<p><br/>
				
				</p>
				<h3>10.7. Reagálás a kérelmekre</h3>
				<p><br/>
				
				</p>
				<p>Az adatkezelő a kérelem benyújtásától számított legrövidebb
				időn belül, de legfeljebb 30 nap – tiltakozás esetén 15 nap –
				alatt megvizsgálja, és annak megalapozottsága kérdésében
				döntést hoz, amelyről a kérelmezőt írásban tájékoztatja. Ha
				a adatkezelő az Érintett kérelmét nem teljesíti, döntésében
				közli a kérelem elutasításának ténybeli és jogi indokait az
				Érintettel.</p>
				<h2>11. Értesítési és intézkedési kötelezettség</h2>
				<p><br/>
				
				</p>
				<h3>
				11.1. Címzettek értesítése</h3>
				<p>Helyesbítésről,
				törlésről, adatkezelés-korlátozásról a adatkezelő minden
				esetben értesíti azokat a címzetteket akikkel, illetve amelyekkel
				az Érintett személyes adatait közölték, kivéve, ha ez
				lehetetlennek bizonyul, vagy aránytalanul nagy erőfeszítést
				igényel. Az Érintett kérésére a adatkezelő tájékoztatást
				nyújt ezekről a címzettekről.</p>
				<p><br/>
				
				</p>
				<h3>11.2. Tájékoztatás módja, határideje</h3>
				<p>A 10. ponthoz kapcsolódó kérelmek nyomán hozott intézkedésekről
				legfeljebb a kérelem beérkezésétől számított egy hónapon
				belül – ha az Érintett másként nem kéri – elektronikus
				formában tájékoztatást kell nyújtani az Érintettnek. Ez a
				határidő szükség esetén – a kérelem összetettsége, illetve
				a kérelmek számára tekintettel – további két hónappal
				meghosszabbítható. A határidő meghosszabbításáról annak
				okainak megjelölésével a kérelem kézhezvételétől számított
				egy hónapon belül tájékoztatni kell az Érintettet.</p>
				<p>Az Érintett kérésére szóbeli tájékoztatás is adható, feltéve,
				hogy más módon igazolja személyazonosságát.</p>
				<p>Amennyiben a adatkezelő nem intézkedik a kérelem nyomán, legfeljebb annak
				beérkezésétől számított egy hónapon belül tájékoztatni kell
				az Érintettet ennek okairól, valamint arról, hogy panaszt nyújthat
				be a Nemzeti Adatvédelmi és Információszabadság Hatóságnál és
				élhet bírósági jogorvoslati jogával.</p>
				<p><br/>
				
				</p>
				<h3>11.3. Ellenőrzés</h3>
				<p>Kivételes
				esetben, ha a adatkezelőnek megalapozott kétségei vannak a
				kérelmet benyújtó természetes személy kilétével kapcsolatban,
				további, személyazonosság megerősítéséhez szükséges
				információk nyújtását kérjük. Ez az intézkedés a GDPR 5.
				cikk (1) bekezdés f) pontjában meghatározott, az adatkezelés
				bizalmasságának elősegítése, azaz a személyes adatokhoz való
				jogosulatlan hozzáférés megakadályozása céljából szükséges.</p>
				<p><br/>
				
				</p>
				<h3>11.4. Tájékoztatás és intézkedés költségei</h3>
				<p>A
				10. ponthoz kapcsolódó kérelmekre adott tájékoztatást, illetve
				az azok alapján megtett intézkedéseket díjmentesen kell
				biztosítani.</p>
				<p>Ha
				az Érintett kérelme egyértelműen megalapozatlan vagy –
				különösen ismétlődő jellege miatt – túlzó, – figyelemmel
				a kért információ vagy tájékoztatás nyújtásával vagy a kért
				intézkedés meghozatalával járó adminisztratív költségekre –
				ésszerű díj számítható fel, vagy megtagadjuk a kérelem alapján
				történő intézkedést.</p>
				<p><br/>
				
				</p>
				<h2>
				12. Egyéb célból történő adatkezelés</h2>
				<p><br/>
				
				</p>
				<p>Amennyiben
				a adatkezelő olyan adatkezelést kíván végezni, amely ebben a
				szabályzatban nem szerepel, előzetesen ezen belső szabályzatát
				kell megfelelően kiegészíteni, illetve az új adatkezelési célnak
				megfelelő rész-szabályokat hozzákapcsolni.</p>
				<p><br/>
				
				</p>
				<p><br/>
				
				</p>
				<h2>
				13. A szabályzathoz tartozó egyéb dokumentumok</h2>
				<p><br/>
				
				</p>
				<p>Az
				adatkezelési szabályzathoz kell kapcsolni és azzal együtt kezelni
				azokat a dokumentumokat és szabályozásokat, amelyek szorosan
				kapcsolódnak annak tartalmához.</p>
				<h2>
				14. A szabályzat hatálya</h2>
				<p><br/>
				
				</p>
				<p>E
				szabályzat visszavonásig érvényes.</p>
				<p><br/>
				
				</p>
				<p><br/>
				
				</p>
				<p>{ SIGNO }</p>
				<p><br/>
				
				</p>
				<h2>15. Egyes speciális adatkezelések</h2>
				<p><br/>
				
				</p>
				<h3>
				15.1 A { SITENAME} szolgáltatással kapcsolatban felmerülő
				speciális adatkezelések</h3>
				<p><br/>
				
				</p>
				<h4><b>A szolgáltatás elindítása</b></h4>
				<ul>
				<li><p>
				A rendszer fájljainak, könyvtárainak a szerverre másolása után
				ki kell alakítani a mysql adatbázist, annak eléréséhez egy
				“mysql user” fiókot kell létrehozni, akinek csak és kizárólag
				ezt az adatbázist van joga kezelni (grant jogokkal). Ennek adatait
				be kell írni az .env fájlba. Az adatbázis eléréséhez más sql
				user -nek ne legyen joga.</p>
				<p>
				A használandó SMTP kiszolgáló eléréséhez szükséges adatokat
				is be kell írni a config.php fájlba 
				</p>
				</li>
				<li><p>
				Csak és kizárólag a “web-szerver-user”
				(rendszerint www-data) rendelkezzen ehhez olvasási jogokkal, írási
				törlési jogokkal pedig senki.</p>
				</li>
				<li><p>
				az “images”  könyvtárra a
				“web-szerver-user” -nek legyen “rwd” jogosultsága, az
				összes többi könyvtárra és fájlra csak “r” jogosultság
				legyen. Más linux userek a fájlokhoz és könyvtárakhoz
				egyáltalán nem férhetnek hozzá.</p>
				</li>
				<li><p>
				Az üzembe helyezés fenti lépéseiről jegyzőkönyv készül amit
				a rendszergazda és a felelős adatkezelő személy ír alá, a
				jegyzőkönyvet a rendszer üzemeltetési idejéig meg kell őrizni.
				</p>
				</li>
				<li><p>
				Ezután offline adathordozóra egy biztonsági másolatot kell
				készíteni a tényleges konfigurációt tartalmazó  config.php fájlról
				és azt a felelős adatkezelő személynek elzártan védetten kell
				megöriznie. Erre akkor lehet szükség ha esetleg a szerver teljes
				összeomlása, üzemképtelenné válása miatt a rendszert újra
				kell telepíteni.</p>
				</li>
				</ul>
				<p><br/>
				
				</p>
				<h4>
				<b>Felhasználó által kért adattörlés</b></h4>
				<p><br/>
				
				</p>
				<p>
				Ezt maga a felhasználó végezheti el a rendszer web felületén.
				Amennyiben mégis más módon kéri, akkor a rendszergazda a linux
				konzolról mysql parancsokkal tudja a user rekord anonimizálását
				(név, email anonimizálása infok törlése ). Erről
				jegyzőkönyvet kell felvennie és tájékoztatnia a felelős
				adatkezelőt és az érintett felhasználót. A user rekord az
				adatbázis konzisztencia megörzése érdekében fizikailag nem
				törölhető!</p>
				<h4>
				<b>Felhasználó által kért tárolt adatok lekérése</b></h4>
				<p>
				A WEB oldalon a "Beállítások" / "Felhasználók" menüpontban végezhető el.
				</p>
				<p><br/>
				
				</p>
				<p><br/>
				
				</p>
				<h4>
				<b>Hatóság által kért adatigénylések teljesítése</b></h4>
				<h4>Technikailag
				ez ugyanúgy történik mint ahogy a felhasználó által kért adat
				igénylések teljesítése (lásd fentebb). Az akcióról
				jegyzőkönyvet kell felvenni és azt a rendszer üzemeltetési
				idejéig megőrizni.</h4>
				<h4>
				<b>Biztonsági adatmentések,  program mentések kezelése</b></h4>
				<p>
				A biztonsági adat mentéseket a rendszergazda a linux konzolon sql
				paranccsal, vagy cronetab -ban ütemezetten automatikusan készíti
				el. Az így készült mentések tartalmazzák a userek személyes
				adatait.</p>
				<p>
				A mentéseket offline adathordozóra kell menteni, a fájl név
				tartalmazza a mentés időpontját. A szerverről ezután le kell
				törölni. Az off-line adathordozót elzárt helyen kell tartani,
				hozzáférése a rendszergazdának és a felelős adatkezelőnek
				lehet.</p>
				<p><br/>
				
				</p>
				<p>
				Havonta egyszer a rendszer document_root könyvtáráról
				(programfájljairól) is biztonsági mentést kell készíteni
				offline adathordozóra, ezeket a rendszergazda tárolja és kezeli,
				ezek user adatokat, napló adatokat nem tartalmaznak.</p>
				<h4>
				<b>Adatbázis visszaállítás biztonsági adat mentésből</b></h4>
				<p>
				Erre csak rendkívüli esetben, súlyos üzemzavar esetén kerülhet
				sor, ha más mód nincs a szolgáltatás működésének
				helyreállítására. A műveletet a rendszergazda az off-line
				mentések felhasználásával a linux konzolon sql parancsokkal végzi
				el. Az akció <b>adatvédelmi incidensnek</b> minősül ugyanis
				előfordulhat, hogy olyan adatok kerülnek visszaállításra amiket
				a felhasználó vagy  a  rendszergazda időközben törölt vagy
				módosított. A GDPR ide vonatkozó előírásai szerint kell
				eljárni. Ennek megfelelően jegyzőkönyvet kell róla felvenni,
				és a GDPR ide vonatkozó rendelkezései szerint kell eljárni.</p>
				<h4>
				<b>Adatkezelési viták kezelése</b></h4>
				<p>
				Adatkezelési vitákban a { SITENAME } platformot a felelős adatkezelő
				vagy az általa megbízott személy képviseli. A körülmények
				kivizsgálása, a tényállás felderítése céljából szükség
				lehet az érintett napló adatok bemutatására, amit a fentebb leírt
				módon a rendszergazda és a felelős adatkezelő együttműködve
				tud létrehozni.</p>
				<p><br/>
				
				</p>
				<p><br/>
				
				</p>
				<p><br/>
				
				</p>
				<p><strong>{ SIGNO }</strong></p>
				<p><br/>
				
				</p>
				<p><br/>
				
				</p>
				<p><br/>
				
				</p>
				<p><br/>
				
				</p>
				
				<h2>Kapcsolódó dokumentumok:</h2>
				<p><a href="./policy">Adatkezelési leírás</a></p>
				<p><var id="policy3Link" className="link" onClick={ () => { setTask('policy3') }}>Adatkezelési nyilvántartás</var></p>
			</div>  
			}
        </div>    
	)
}
export default Policy2
